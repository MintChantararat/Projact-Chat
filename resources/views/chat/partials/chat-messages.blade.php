@php
    $isPrivate = isset($receiver);
@endphp

<style>
    .message {
        max-width: 70%;
        margin-bottom: 16px;
        padding: 10px;
        border-radius: 12px;
        position: relative;
        word-break: break-word;
    }
    .user-message {
        align-self: flex-end;
        background-color: #007aff;
        color: white;
        text-align: left;
    }
    .received-message {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
    }
    .received-message .bubble {
        background-color: #e5e5ea;
        color: black;
        border-radius: 12px;
        padding: 10px;
        max-width: 100%;
    }
    .sender-info {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .sender-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }
    .message-meta {
        font-size: 11px;
        color: #666;
        margin-top: 4px;
        text-align: right;
    }
    .chat-image {
        max-width: 140px;
        max-height: 140px;
        margin-top: 5px;
        border-radius: 8px;
        display: block;
    }
</style>

@forelse ($messages as $message)
    @php
        $isSender = $message['sender_id'] === session('firebase_uid');
        $sender = !$isPrivate ? ($group['group_member'][$message['sender_id']] ?? null) : null;
        $senderName = $isPrivate
            ? trim(($receiver['first_name'] ?? '') . ' ' . ($receiver['last_name'] ?? ''))
            : ($sender ? trim(($sender['first_name'] ?? '') . ' ' . ($sender['last_name'] ?? '')) : 'ไม่ทราบชื่อ');

        $profilePath = $isPrivate
            ? ($receiver['profile_photo'] ?? '')
            : ($sender['profile_photo'] ?? '');

        $senderAvatar = !empty($profilePath) && file_exists(public_path('images/profile/' . basename($profilePath)))
            ? asset('images/profile/' . basename($profilePath))
            : asset('images/profile/default-avatar.png');
    @endphp

    @if ($isSender)
        <div class="message user-message" style="align-self: flex-end;">
            @if (!empty($message['text']))
                <div>{{ $message['text'] }}</div>
            @endif
            @if (!empty($message['image_url']))
                <img src="{{ $message['image_url'] }}" class="chat-image">
            @endif
            <div class="message-meta">
                {{ \Carbon\Carbon::parse($message['timestamp'])->setTimezone('Asia/Bangkok')->format('H:i') }}น.

            </div>
        </div>
    @else
        <div class="received-message">
            <img class="sender-avatar" src="{{ $senderAvatar }}" alt="Avatar">
            <div class="bubble">
                <div class="sender-info">{{ $senderName }}</div>
                @if (!empty($message['text']))
                    <div>{{ $message['text'] }}</div>
                @endif
                @if (!empty($message['image_url']))
                    <img src="{{ $message['image_url'] }}" class="chat-image">
                @endif
                <div class="message-meta">
                    {{ \Carbon\Carbon::parse($message['timestamp'])->setTimezone('Asia/Bangkok')->format('H:i') }}น.
                </div>
            </div>
        </div>
    @endif
@empty
    <div style="color: #888;">ยังไม่มีข้อความ</div>
@endforelse
