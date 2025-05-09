<!-- chat-conversation.blade.php -->
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
            overflow-y: auto;
        }
        .chat-box {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 10px;
            padding-bottom: 70px;
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
        .sender-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .bubble {
            background-color: #e5e5ea;
            padding: 10px;
            border-radius: 12px;
            max-width: 70%;
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
        .message.user-message {
            align-self: flex-end;
            background-color: #007aff;
            color: white;
            padding: 10px;
            border-radius: 12px;
            max-width: 70%;
            margin-bottom: 12px;
        }
        .message.received-message {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }
    </style>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script>
        window.firebaseUid = "{{ session('firebase_uid') }}";

        const firebaseConfig = {
            apiKey: "XXXXXXXXXXXXXXXXXXXXXXX",
            authDomain: "XXXXXXXXXXXXXXXXXXXXXXX",
            databaseURL: "XXXXXXXXXXXXXXXXXXXXXXX",
            projectId: "XXXXXXXXXXXXXXXXXXXXXXX",
            storageBucket: "XXXXXXXXXXXXXXXXXXXXXXX",
            messagingSenderId: "XXXXXXXXXXXXXXXXXXXXXXX",
            appId: "XXXXXXXXXXXXXXXXXXXXXXX"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        firebase.database().ref(".info/connected").on("value", function(snapshot) {
            console.log("[🔌 Firebase Connected?]", snapshot.val());
        });

        const db = firebase.database();
        const loadedKeys = new Set();
    </script>
</head>
<body>

@if (isset($receiver) || isset($groupId))
    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            {{-- ลบ Blade SSR แสดงข้อความ เพื่อป้องกันซ้ำซ้อนกับ realtime --}}
        </div>

        <div class="chat-input">
            <form method="POST"
                  action="{{ isset($receiver) ? route('chat.private.send', ['conversationId' => $conversationId]) : route('chat.group.send', ['groupId' => $groupId]) }}"
                  enctype="multipart/form-data" style="display: flex; width: 100%;">
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
    <div style="display: flex; justify-content: center; align-items: center; height: 100%; color: #999;">
        <h3>โปรดเริ่มต้นการสนทนา</h3>
    </div>
@endif

<script>
    document.getElementById('imageUpload')?.addEventListener('change', function () {
        if (this.files.length > 0) this.closest('form').submit();
    });

    function scrollToBottom(force = false) {
        const chatBox = document.getElementById("chatBox");
        if (!chatBox) return;
        const nearBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 100;
        if (force || nearBottom) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    function createMessageElement(msg, isSender = false) {
        const wrapper = document.createElement("div");
        wrapper.className = isSender ? "message user-message" : "message received-message";

        if (isSender) {
            if (msg.text) wrapper.appendChild(document.createTextNode(msg.text));
            if (msg.image_url) {
                const img = document.createElement("img");
                img.src = msg.image_url;
                img.className = "chat-image";
                wrapper.appendChild(img);
            }
            const time = document.createElement("div");
            time.className = "message-meta";
            const date = new Date(msg.timestamp);
            date.setHours(date.getHours() + 7); // แปลง UTC → GMT+7
            time.textContent = date.toLocaleTimeString("th-TH", {
                hour: '2-digit',
                minute: '2-digit'
            }) + "น.";

            wrapper.appendChild(time);
        } else {
            const avatar = document.createElement("img");
            avatar.className = "sender-avatar";
            avatar.src = msg.profile_photo || "/images/profile/default-avatar.png";

            const bubble = document.createElement("div");
            bubble.className = "bubble";

            const name = document.createElement("div");
            name.className = "sender-info";
            name.textContent = msg.sender_name || "ไม่ทราบชื่อ";
            bubble.appendChild(name);

            if (msg.text) bubble.appendChild(document.createTextNode(msg.text));
            if (msg.image_url) {
                const img = document.createElement("img");
                img.src = msg.image_url;
                img.className = "chat-image";
                bubble.appendChild(img);
            }

            const time = document.createElement("div");
            time.className = "message-meta";
            const date = new Date(msg.timestamp);
            date.setHours(date.getHours() + 7); // แปลง UTC → GMT+7
            time.textContent = date.toLocaleTimeString("th-TH", {
                hour: '2-digit',
                minute: '2-digit'
            }) + "น.";

            bubble.appendChild(time);

            wrapper.appendChild(avatar);
            wrapper.appendChild(bubble);
        }

        return wrapper;
    }

    function listenChat(path) {
        const chatBox = document.getElementById("chatBox");
        if (!chatBox) {
            console.error("[❌ chatBox ไม่พบ]");
            return;
        }

        const ref = db.ref(path);
        console.log("[📡 Listening]", path);

        ref.limitToLast(10).once("value", snapshot => {
            snapshot.forEach(child => {
                const key = child.key;
                if (!loadedKeys.has(key)) {
                    loadedKeys.add(key);
                    const msg = child.val();
                    const isSender = msg.sender_id === window.firebaseUid;
                    const el = createMessageElement(msg, isSender);
                    chatBox.appendChild(el);
                }
            });
            scrollToBottom(true);

            ref.on("child_added", snapshot => {
                const key = snapshot.key;
                if (loadedKeys.has(key)) return;
                loadedKeys.add(key);

                const msg = snapshot.val();
                console.log("[📨 Received]", key, msg.text || "[image]");
                const isSender = msg.sender_id === window.firebaseUid;
                const el = createMessageElement(msg, isSender);
                chatBox.appendChild(el);
                scrollToBottom();
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        scrollToBottom(true);

        const isPrivate = {!! isset($receiver) ? 'true' : 'false' !!};
        const conversationId = "{{ $conversationId ?? '' }}";
        const groupId = "{{ $groupId ?? '' }}";

        console.log("[👤 My UID]", window.firebaseUid);
        console.log("[🧭 Mode]", isPrivate ? "Private" : "Group");

        const path = isPrivate
            ? `private_chat/${conversationId}/messages`
            : `group_chat/${groupId}/messages`;

        listenChat(path);
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("messageSearchInput");

    input?.addEventListener("input", function () {
        const keyword = this.value.trim().toLowerCase();
        const chatBox = document.getElementById("chatBox");

        if (!chatBox) return;

        // ลบ <mark> เดิมทั้งหมด
        chatBox.querySelectorAll("mark").forEach(el => {
            const parent = el.parentNode;
            parent.replaceChild(document.createTextNode(el.textContent), el);
            parent.normalize();
        });

        if (!keyword) return;

        const textNodes = [];
        const walker = document.createTreeWalker(chatBox, NodeFilter.SHOW_TEXT, null, false);

        while (walker.nextNode()) {
            const node = walker.currentNode;
            if (node.nodeValue.toLowerCase().includes(keyword)) {
                textNodes.push(node);
            }
        }

        if (textNodes.length > 0) {
            const firstMatchOffset = [];

            textNodes.forEach((node, idx) => {
                const parent = node.parentNode;
                const text = node.nodeValue;
                const regex = new RegExp(`(${keyword})`, "gi");
                const html = text.replace(regex, `<mark>$1</mark>`);
                const temp = document.createElement("span");
                temp.innerHTML = html;

                // สลับ node เดิม
                parent.replaceChild(temp, node);
                parent.normalize();

                if (idx === 0) {
                    // scroll ถึง match แรก
                    const mark = temp.querySelector("mark");
                    if (mark) mark.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });
        }
    });
});
</script>



</body>
</html>
