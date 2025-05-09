// ✅ Firebase config (ใช้เฉพาะ v8.x แบบ CDN)
const firebaseConfig = {
  apiKey: "XXXXXXXXXXXXXXXXXXXXXXX",
  authDomain: "XXXXXXXXXXXXXXXXXXXXXXX",
  databaseURL: "XXXXXXXXXXXXXXXXXXXXXXX",
  projectId: "XXXXXXXXXXXXXXXXXXXXXXX",
  storageBucket: "XXXXXXXXXXXXXXXXXXXXXXX",
  messagingSenderId: "XXXXXXXXXXXXXXXXXXXXXXX",
  appId: "XXXXXXXXXXXXXXXXXXXXXXX",
};

// ✅ ป้องกัน initialize ซ้ำ
if (!firebase.apps.length) {
  firebase.initializeApp(firebaseConfig);
}

const db = firebase.database();
const loadedKeys = new Set();

// ✅ ตรวจสอบสถานะการเชื่อมต่อ Firebase
firebase.database().ref(".info/connected").on("value", function (snapshot) {
  console.log("[🔌 Firebase Connection]", snapshot.val());
});

// ✅ ฟังก์ชันสร้าง message DOM
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
    time.textContent = new Date(msg.timestamp).toLocaleTimeString("th-TH", { hour: "2-digit", minute: "2-digit" }) + "น.";
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
    time.textContent = new Date(msg.timestamp).toLocaleTimeString("th-TH", { hour: "2-digit", minute: "2-digit" }) + "น.";
    bubble.appendChild(time);

    wrapper.appendChild(avatar);
    wrapper.appendChild(bubble);
  }

  return wrapper;
}

// ✅ ฟังก์ชันหลัก: ฟังแบบเรียลไทม์
window.listenChat = function (path) {
  const chatBox = document.getElementById("chatBox");
  if (!chatBox) {
    console.error("[❌ ไม่พบ chatBox]");
    return;
  }

  console.log("[📡 Listening]", path);

  const ref = db.ref(path);

  // ดึงข้อความล่าสุด 10 รายการ (ครั้งแรก)
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

    // ✅ เริ่มฟังแบบ realtime
    ref.on("child_added", snapshot => {
      const key = snapshot.key;
      if (loadedKeys.has(key)) return;
      loadedKeys.add(key);

      const msg = snapshot.val();
      console.log("[📨 Received]", key, msg.text || "[image]");
      const isSender = msg.sender_id === window.firebaseUid;
      const el = createMessageElement(msg, isSender);
      chatBox.appendChild(el);
      chatBox.scrollTop = chatBox.scrollHeight;
    });
  });
};
