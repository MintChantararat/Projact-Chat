<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Carbon\Carbon;

class ChatController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    public function chatList()
    {
        $uid = session('firebase_uid');

        $employees = $this->database->getReference('employee')->getValue() ?? [];
        $groupChats = $this->database->getReference('group_chat')->getValue() ?? [];
        $privateChats = $this->database->getReference('private_chat')->getValue() ?? [];

        $userGroups = [];
        foreach ($groupChats as $groupId => $group) {
            if (!empty($group['group_member'][$uid])) {
                $group['id'] = $groupId;
                $group['last_message'] = $group['last_message'] ?? [
                    'message' => '',
                    'timestamp' => null,
                ];
                $userGroups[] = $group;
            }
        }

        $chatMap = [];
        foreach ($privateChats as $conversationId => $chatData) {
            $participants = $chatData['participants'] ?? [];
            if (array_key_exists($uid, $participants)) {
                foreach ($participants as $participantId => $_) {
                    if ($participantId !== $uid && isset($employees[$participantId])) {
                        if (isset($chatData['last_message'])) {
                            $chatMap[$participantId] = $chatData['last_message'];
                        }
                    }
                }
            }
        }

        return view('chat', [
            'employees'   => $employees,
            'chats'       => $chatMap,
            'groupChats'  => $userGroups,
            'groupId'     => null,
            'group'       => null,
            'receiver'    => null,
            'messages'    => [],
        ]);
    }

    public function chatConversation($groupId)
    {
        $group = $this->database->getReference("group_chat/{$groupId}")->getValue();
        $messages = $this->database->getReference("group_chat/{$groupId}/messages")->getValue() ?? [];

        $employees = $this->database->getReference('employee')->getValue() ?? [];


        $imageMessages = array_filter($messages, function ($msg) {
            return !empty($msg['image_url']);
        });

        $groupedImages = [];
        foreach ($imageMessages as $msg) {
            $month = Carbon::parse($msg['timestamp'])->translatedFormat('M Y'); // เช่น ต.ค. 2024
            $groupedImages[$month][] = $msg['image_url'];
        }

        $groupChats = $this->database->getReference('group_chat')->getValue() ?? [];
        $userGroups = [];
        foreach ($groupChats as $id => $gc) {
            if (!empty($gc['group_member'][session('firebase_uid')])) {
                $gc['id'] = $id;
                $userGroups[] = $gc;
            }
        }

        $uid = session('firebase_uid');
        $privateChats = $this->database->getReference('private_chat')->getValue() ?? [];

        $chatMap = [];
        foreach ($privateChats as $conversationId => $chatData) {
            $participants = $chatData['participants'] ?? [];
            if (array_key_exists($uid, $participants)) {
                foreach ($participants as $participantId => $_) {
                    if ($participantId !== $uid && isset($employees[$participantId])) {
                        if (isset($chatData['last_message'])) {
                            $chatMap[$participantId] = $chatData['last_message'];
                        }
                    }
                }
            }
        }

        return view('chat', [
            'groupId'       => $groupId,
            'group'         => $group,
            'messages'      => $messages,
            'employees'     => $employees,
            'chats'         => $chatMap,
            'groupChats'    => $userGroups,
            'groupedImages' => $groupedImages,
        ]);
    }

    public function privateChat($uid)
    {
        $myUid = session('firebase_uid');
        $conversationId = $myUid < $uid ? "{$myUid}_{$uid}" : "{$uid}_{$myUid}";

        $conversationRef = $this->database->getReference("private_chat/{$conversationId}");
        $messages = $conversationRef->getChild('messages')->getValue() ?? [];

        $receiver = $this->database->getReference("employee/{$uid}")->getValue();
        $employees = $this->database->getReference('employee')->getValue() ?? [];

        $chatData = $this->database->getReference("private_chat/{$conversationId}/last_message")->getValue() ?? [];

        $groupChats = $this->database->getReference('group_chat')->getValue() ?? [];
        $userGroups = [];
        foreach ($groupChats as $id => $group) {
            if (!empty($group['group_member'][$myUid])) {
                $group['id'] = $id;
                $userGroups[] = $group;
            }
        }


        $imageMessages = array_filter($messages, function ($msg) {
            return !empty($msg['image_url']);
        });

        $groupedImages = [];
        foreach ($imageMessages as $msg) {
            $month = Carbon::parse($msg['timestamp'])->translatedFormat('M Y');
            $groupedImages[$month][] = $msg['image_url'];
        }

        return view('chat', [
            'groupChats'     => $userGroups,
            'employees'      => $employees,
            'chats'          => [$uid => $chatData],
            'messages'       => $messages,
            'receiver'       => $receiver,
            'conversationId' => $conversationId,
            'groupedImages'  => $groupedImages,
        ]);
    }

    public function sendPrivateMessage(Request $request, $conversationId)
    {
        $senderId = session('firebase_uid');
        $receiverId = explode('_', $conversationId);
        $receiverId = $receiverId[0] === $senderId ? $receiverId[1] : $receiverId[0];

        $chatRef = $this->database->getReference("private_chat/{$conversationId}");
        $existingChat = $chatRef->getValue();

        if (!$existingChat) {
            $chatRef->set([
                'participants' => [
                    $senderId => true,
                    $receiverId => true,
                ],
            ]);
        }

        $sender = $this->database->getReference("employee/{$senderId}")->getValue();
        $messageData = [
            'sender_id' => $senderId,
            'timestamp' => now()->toDateTimeString(),
            'sender_name' => ($sender['first_name'] ?? '') . ' ' . ($sender['last_name'] ?? ''),
            'profile_photo' => $sender['profile_photo'] ?? 'images/default-avatar.png',
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('chat_images', 'public');
            $messageData['image_url'] = '/storage/' . $path;
        }

        if ($request->filled('message')) {
            $messageData['text'] = $request->input('message');
        }

        if (empty($messageData['text']) && empty($messageData['image_url'] ?? null)) {
            return back()->with('error', 'กรุณาพิมพ์ข้อความหรือเลือกรูปภาพ');
        }

        $this->database->getReference("private_chat/{$conversationId}/messages")->push($messageData);
        $this->database->getReference("private_chat/{$conversationId}/last_message")->set([
            'message' => $messageData['text'] ?? '[รูปภาพ]',
            'timestamp' => $messageData['timestamp'],
        ]);

        return redirect()->route('chat.private', ['uid' => $receiverId]);
    }

    public function sendGroupMessage(Request $request, $groupId)
    {
        $uid = session('firebase_uid');
        $sender = $this->database->getReference("employee/{$uid}")->getValue();

        $messageData = [
            'sender_id' => $uid,
            'timestamp' => now()->toDateTimeString(),
            'sender_name' => ($sender['first_name'] ?? '') . ' ' . ($sender['last_name'] ?? ''),
            'profile_photo' => $sender['profile_photo'] ?? 'images/default-avatar.png',
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('chat_images', 'public');
            $messageData['image_url'] = '/storage/' . $path;
        }

        if ($request->filled('message')) {
            $messageData['text'] = $request->input('message');
        }

        if (empty($messageData['text']) && empty($messageData['image_url'] ?? null)) {
            return back()->with('error', 'กรุณาพิมพ์ข้อความหรือเลือกรูปภาพ');
        }

        $this->database->getReference("group_chat/{$groupId}/messages")->push($messageData);
        $this->database->getReference("group_chat/{$groupId}/last_message")->set([
            'message' => $messageData['text'] ?? '[รูปภาพ]',
            'timestamp' => $messageData['timestamp'],
        ]);

        return redirect()->route('chat.conversation', ['groupId' => $groupId]);
    }


    public function pollPrivateMessages($conversationId)
    {
        $messages = $this->database->getReference("private_chat/{$conversationId}/messages")
            ->orderByKey()
            ->getValue() ?? [];

        return view('chat.partials.chat-messages', compact('messages'))->render();
    }


    public function pollGroupMessages($groupId)
    {
        $messages = $this->database->getReference("group_chat/{$groupId}/messages")
            ->orderByKey()
            ->getValue() ?? [];

        return view('chat.partials.chat-messages', compact('messages'))->render();
    }
    public function loadMessages($id)
    {
        $uid = session('firebase_uid');

        if (str_contains($id, '_')) {
            $conversationId = $id;
            $messages = $this->database->getReference("private_chat/{$conversationId}/messages")->getValue() ?? [];
            $receiverId = explode('_', $conversationId);
            $receiverId = $receiverId[0] === $uid ? $receiverId[1] : $receiverId[0];
            $receiver = $this->database->getReference("employee/{$receiverId}")->getValue();

            return view('chat.partials.chat-messages', [
                'messages' => $messages,
                'receiver' => $receiver,
            ]);
        } else {
            $groupId = $id;
            $messages = $this->database->getReference("group_chat/{$groupId}/messages")->getValue() ?? [];
            $group = $this->database->getReference("group_chat/{$groupId}")->getValue();

            return view('chat.partials.chat-messages', [
                'messages' => $messages,
                'group' => $group,
            ]);
        }
    }
    public function getChatMessagesJson($id)
    {
        $uid = session('firebase_uid');

        if (str_contains($id, '_')) {
            $messages = $this->database->getReference("private_chat/{$id}/messages")->getValue() ?? [];
        } else {
            $messages = $this->database->getReference("group_chat/{$id}/messages")->getValue() ?? [];
        }

        return response()->json(['messages' => $messages]);
    }

}
