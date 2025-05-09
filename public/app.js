import { initializeApp } from "https://www.gstatic.com/firebasejs/11.3.1/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.3.1/firebase-analytics.js";
import { getFirestore, collection, getDocs } from "https://www.gstatic.com/firebasejs/11.3.1/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyAn5Nrnk7W4iwqlkc17yzWRiBTK3dcikyM",
  authDomain: "projact-chat-business.firebaseapp.com",
  databaseURL: "https://projact-chat-business-default-rtdb.firebaseio.com",
  projectId: "projact-chat-business",
  storageBucket: "projact-chat-business.firebasestorage.app",
  messagingSenderId: "17151370482",
  appId: "1:17151370482:web:3f51844ae90e04ddd81c6b",
  measurementId: "G-4P2YNMNM2R"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const db = getFirestore (app);

async function getEmployees(db){
    const empCol = collection(db,'employees')
    const empSnapshot = await getDocs(empCol)
    return empSnapshot
}

//ดึงกลุ่ม Document
const data = await getEmployees(db)
console.log(data)