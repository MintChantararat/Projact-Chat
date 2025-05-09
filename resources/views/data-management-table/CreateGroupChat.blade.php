@extends ('app')

<!-- ฉากพื้นหลังโปร่ง -->
<div id="create-group-chat" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 justify-content-center align-items-center" style="z-index: 1050; display: none;">
    <div class="card shadow-lg" style="width: 600px; max-width: 90%;">
    <form action="{{ route('groupchat.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf

        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">สร้างกลุ่มผู้ใช้ใหม่</h5>
            <button type="button" class="btn-close" aria-label="Close" onclick="closeCreateGroupChat()"></button>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- ซ้าย: รูป -->
                <div class="col-md-6 text-center">
                    <div class="profile-img mb-3 rounded" style="width: 80%; aspect-ratio: 1 / 1; margin: auto; display: flex; align-items: center; justify-content: center; background-color: #f2f2f2;">
                        <img id="profileImagePreview" src="" alt="Group Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px; display: none;">
                        <i id="defaultIcon" class="bi bi-people-fill" style="font-size: 100px; color: #ccc;"></i>
                    </div>
                    <button type="button" class="btn btn-success w-100 mb-2" onclick="document.getElementById('fileInput').click();">
                        อัปโหลดรูปโปรไฟล์
                    </button>
                    <input type="file" id="fileInput" name="conversation_photo" accept="image/*" style="display: none;" onchange="previewImage(event)" autocomplete="off">
                </div>

                <!-- ขวา: ชื่อกลุ่ม + สมาชิก -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="groupName" class="form-label">ชื่อกลุ่ม</label>
                        <input type="text" class="form-control" id="groupName" name="conversation_name" placeholder="ระบุชื่อกลุ่ม..." required autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <div class="border rounded p-2">
                            <h6 class="text-center mb-2">สมาชิกกลุ่ม</h6>
                            <ul class="list-group small mb-2" id="selected-member-list" style="max-height: 150px; overflow-y: auto;">
                                <li class="list-group-item d-flex align-items-center justify-content-between bg-light">
                                    <div>
                                        <i class="bi bi-person-fill me-2"></i> {{ session('user_name') ?? 'คุณ' }}
                                        <input type="hidden" name="push_ids[]" value="{{ session('firebase_uid') }}" autocomplete="off">
                                    </div>
                                    <span class="badge bg-secondary">คุณ</span>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                + เพิ่มสมาชิก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-danger" onclick="cancelCreateGroup()">ยกเลิก</button>
            <button type="submit" class="btn btn-success">บันทึก</button>
        </div>
    </form>

        @include('data-management-table.CreateAddMemberChat', [
            'userList' => $userList,
            'modalId' => 'addMemberModal',
            'addMemberFunction' => 'addMemberToPreview'
        ])
    </div>
</div>

<!-- ✨ Script -->
<script>
function openCreateGroupChat() {
    const popup = document.getElementById('create-group-chat');
    popup.style.display = 'flex';
    popup.classList.add('d-flex');
}

function closeCreateGroupChat() {
    const popup = document.getElementById('create-group-chat');
    popup.style.display = 'none';
    popup.classList.remove('d-flex');
}

function cancelCreateGroup() {
    if (confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการสร้างกลุ่ม?')) {
        closeCreateGroupChat();
    }
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('profileImagePreview');
        const defaultIcon = document.getElementById('defaultIcon');
        output.src = reader.result;
        output.style.display = 'block';
        if (defaultIcon) {
            defaultIcon.style.display = 'none';
        }
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script>
let selectedMembers = new Set();

function addMemberToPreview(pushId, fullName, listId = 'selected-member-list') {
    if (selectedMembers.has(pushId)) {
        alert('สมาชิกนี้ถูกเลือกไว้แล้ว');
        return;
    }

    selectedMembers.add(pushId);

    const memberList = document.getElementById(listId);
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex align-items-center justify-content-between';

    li.innerHTML = `
        <div>
            <i class="bi bi-person-fill me-2"></i> ${fullName}
            <input type="hidden" name="push_ids[]" value="${pushId}" autocomplete="off">
        </div>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeMember(this, '${pushId}', '${listId}')">ลบ</button>
    `;

    memberList.appendChild(li);
}

function removeMember(button, pushId, listId = 'selected-member-list') {
    selectedMembers.delete(pushId);
    button.closest('li').remove();
}
</script>
