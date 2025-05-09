<div class="search-bar">
    <input type="text" id="searchInput" placeholder="ค้นหารายชื่อผู้ติดต่อ...">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('searchInput');
        const contactItems = document.querySelectorAll('#contactList .chat-item');

        input.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            contactItems.forEach(item => {
                const name = item.querySelector('.chat-name')?.innerText.toLowerCase() || '';
                item.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    });
    </script>
</div>


<!-- ---------------------------------------กล่องข้อความ---------------------------------------" -->
<div class="chat-group">
    <h4>กลุ่มสนทนา</h4>
    @if ($groupChats)
        @foreach ($groupChats as $group)
            <div class="chat-item" onclick="window.location.href='{{ route('chat.conversation', ['groupId' => $group['id']]) }}'">
                <div class="avatar">
                <img src="{{ $group['conversation_photo'] ?? '/default-avatar.png' }}" width="40" height="40" style="border-radius: 50%;">
                </div>
                <div class="chat-details">
                    <p class="chat-name">{{ $group['conversation_name'] }}</p>
                    <p class="chat-preview">
                    {{ $group['last_message']['message'] ?? '' }}
                    </p>
                </div>
                <span class="chat-time">
                    @if(isset($group['last_message']['timestamp']))
                    {{ \Carbon\Carbon::parse($group['last_message']['timestamp'])->setTimezone('Asia/Bangkok')->format('H:i') }}น.
                    @endif
                </span>
            </div>
        @endforeach
    @else
        <p>ยังไม่มีการเข้าร่วมกลุ่ม</p>
    @endif
</div>

<!-- ---------------------------------------กล่องข้อความ---------------------------------------" -->
<div class="chat-group" id="contactList">
    <h4>รายชื่อผู้ติดต่อ</h4>
    @if ($employees)
        @foreach ($employees as $id => $employee)
            @if ($id !== session('firebase_uid')) {{-- ไม่แสดงตัวเอง --}}
                @php
                    $myUid = session('firebase_uid');
                    $conversationId1 = "{$myUid}_{$id}";
                    $conversationId2 = "{$id}_{$myUid}";

                    // ตรวจสอบว่า conversation ใดมีอยู่ใน chat_last_messages
                    $chatData = $chats[$conversationId1] ?? $chats[$conversationId2] ?? null;
                @endphp
                <div class="chat-item" onclick="window.location.href='{{ route('chat.private', ['uid' => $id]) }}'">
                    <div class="avatar">
                        <img src="{{ $employee['profile_photo'] ?? '/default-avatar.png' }}" width="40" height="40" style="border-radius: 50%;">
                    </div>
                    <div class="chat-details">
                        <p class="chat-name">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</p>
                        <p class="chat-preview">
                            {{ $chats[$id]['message'] ?? '' }}
                        </p>
                    </div>
                    <span class="chat-time">
                        @if (!empty($chats[$id]['timestamp']))
                            {{ \Carbon\Carbon::parse($chats[$id]['timestamp'])->setTimezone('Asia/Bangkok')->format('H:i') }}น.
                        @endif
                    </span>
                </div>
            @endif
        @endforeach
    @else
        <p>ไม่พบผู้ติดต่อ</p>
    @endif
</div>
