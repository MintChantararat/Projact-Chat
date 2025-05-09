<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat UI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            height: 100%;
        }
        .chat-container {
            width: 100%;
            height: 100vh;
            background: white;
            display: flex;
            flex-direction: column;
            overflow-y: auto; /* เลื่อนเนื้อหาภายในได้ */
        }
        .chat-box {
            flex: 1;
            height: 400px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding-bottom: 70px; /*เพื่อไม่ให้ข้อความโดนบัง */
        }
        .message {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 70%;
            position: relative;
        }
        .user-message {
            align-self: flex-end;
            background-color: #007aff;
            color: white;
        }
        .received-message {
            align-self: flex-start;
            background-color: #e5e5ea;
        }
        .sender-info {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }
        .received-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .sender-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #ccc;
        }
        .message-meta {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
            text-align: right;
        }
        .chat-input {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            display: flex;
            background: white;
            border-top: 1px solid #ccc;
        }
        .chat-input input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
            font-size: 16px;
        }
        .chat-input button {
            padding: 10px 15px;
            border: none;
            background: #007aff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
@if (isset($receiver))
    {{-- Private Chat --}}
    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            @forelse ($messages as $message)
                @php $isSender = $message['sender_id'] === session('firebase_uid'); @endphp
                <div class="message {{ $isSender ? 'user-message' : 'received-message' }}">
                    @if (!$isSender)
                        <div style="margin-bottom: 5px; font-size: 13px; color: #3c3d3c;">
                            {{ ($receiver['first_name'] ?? '') . ' ' . ($receiver['last_name'] ?? '') ?: 'ไม่ทราบชื่อ' }}
                        </div>
                    @endif

                    @if (!empty($message['text']))
                        <span>{{ $message['text'] }}</span>
                    @endif

                    @if (!empty($message['image_url']))
                        <div style="margin-top: 5px;">
                            <img src="{{ $message['image_url'] }}" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif

                    <span class="message-meta">
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}น.
                    </span>
                </div>
            @empty
                <div style="color: #888;">ยังไม่มีข้อความ</div>
            @endforelse
        </div>

        <div class="chat-input">
            <form method="POST" action="{{ route('chat.private.send', ['conversationId' => $conversationId]) }}" enctype="multipart/form-data" style="display: flex; width: 100%;">
                @csrf
                <label for="imageUpload" style="cursor: pointer; margin-right: 10px;">
                    <i class="bi bi-image icon-large"></i>
                </label>
                <input type="file" name="image" id="imageUpload" accept="image/*" style="display: none;">
                <input type="text" name="message" placeholder="พิมพ์ข้อความของคุณที่นี่..." class="form-control" autocomplete="off">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>

@elseif (isset($groupId))
    {{-- Group Chat --}}
    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            @foreach ($messages as $message)
                @php $isSender = $message['sender_id'] === session('firebase_uid'); @endphp
                <div class="message {{ $isSender ? 'user-message' : 'received-message' }}">
                    @if (!$isSender)
                        <div style="margin-bottom: 5px; font-size: 13px; color: #3c3d3c;">
                            {{ 
                                ($group['group_member'][$message['sender_id']]['first_name'] ?? '') 
                                . ' ' . 
                                ($group['group_member'][$message['sender_id']]['last_name'] ?? '') 
                                ?: 'ไม่ทราบชื่อ'
                            }}
                        </div>
                    @endif

                    @if (!empty($message['text']))
                        <span>{{ $message['text'] }}</span>
                    @endif

                    @if (!empty($message['image_url']))
                        <div style="margin-top: 5px;">
                            <img src="{{ $message['image_url'] }}" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif

                    <span class="message-meta">
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}น.
                    </span>
                </div>
            @endforeach
        </div>

        <div class="chat-input">
            <form method="POST" action="{{ route('chat.group.send', ['groupId' => $groupId]) }}" enctype="multipart/form-data" style="display: flex; width: 100%;">
                @csrf
                <label for="imageUpload" style="cursor: pointer; margin-right: 10px;">
                    <i class="bi bi-image icon-large"></i>
                </label>
                <input type="file" name="image" id="imageUpload" accept="image/*" style="display: none;">
                <input type="text" name="message" placeholder="พิมพ์ข้อความของคุณที่นี่..." class="form-control" autocomplete="off">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>

@else
    {{-- ยังไม่เลือกใคร --}}
    <div style="display: flex; justify-content: center; align-items: center; height: 100%; color: #999;">
        <h3>โปรดเริ่มต้นการสนทนา</h3>
    </div>
@endif

<script>
document.getElementById('imageUpload').addEventListener('change', function () {
    if (this.files.length > 0) {
        this.closest('form').submit();
    }
});
</script>

<!--กำหนดสกอ ให้อยู่ด้านล่างเสมอ-->
<script>
    function scrollToBottom() {
        const chatBox = document.getElementById('chatBox');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    document.addEventListener('DOMContentLoaded', scrollToBottom);
    window.addEventListener('load', scrollToBottom);
</script>

<!--การค้นหาข้อความ-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const messageSearch = document.getElementById('messageSearchInput');
    const messageBoxes = document.querySelectorAll('#chatBox .message');

    messageSearch.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        let firstMatchScrolled = false;

        messageBoxes.forEach(box => {
            const span = box.querySelector('span');
            if (!span) return;

            const originalText = span.innerText;
            const lowerText = originalText.toLowerCase();

            if (keyword && lowerText.includes(keyword)) {
                const regex = new RegExp(`(${keyword})`, 'gi');
                span.innerHTML = originalText.replace(regex, '<mark>$1</mark>');

                // ✅ Scroll ไปหา match แรก
                if (!firstMatchScrolled) {
                    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstMatchScrolled = true;
                }
            } else {
                span.innerHTML = originalText; // ล้างไฮไลต์เมื่อไม่ตรง
            }
        });
    });
});

</script>

</html>
