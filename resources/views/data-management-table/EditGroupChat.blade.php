@extends('app')

<!-- ฉากพื้นหลังโปร่ง -->
<div id="edit-group-chat" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 justify-content-center align-items-center" style="z-index: 1050; display: none;">
    <div class="card shadow-lg" style="width: 600px; max-width: 90%;">
        <form id="editGroupChatForm" method="POST" enctype="multipart/form-data" action="{{ route('groupchat.update', ['groupId' => '__GROUP_ID__']) }}">
            @csrf
            @method('POST')

            <input type="hidden" name="group_id" id="edit_group_id">

            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">แก้ไขกลุ่มผู้ใช้</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="closeEditGroupChat()"></button>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- ซ้าย: รูปโปรไฟล์ -->
                    <div class="col-md-6 text-center">
                        <div class="profile-img mb-3 rounded" style="width: 80%; aspect-ratio: 1/1; margin: 0 auto; display: flex; align-items: center; justify-content: center; background-color: #f2f2f2;">
                            <img id="editProfileImagePreview" src="" alt="Group Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px; display: none;">
                            <i id="editDefaultIcon" class="bi bi-people-fill" style="font-size: 100px; color: #ccc;"></i>
                        </div>
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="document.getElementById('editFileInput').click();">
                            อัปโหลดรูปโปรไฟล์
                        </button>
                        <input type="file" id="editFileInput" name="conversation_photo" accept="image/*" style="display: none;" onchange="previewEditImage(event)">
                    </div>

                    <!-- ขวา: ชื่อกลุ่ม + สมาชิก -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="editGroupName" class="form-label">ชื่อกลุ่ม</label>
                            <input type="text" class="form-control" id="editGroupName" name="conversation_name" placeholder="ระบุชื่อกลุ่ม..." required>
                        </div>

                        <div class="mb-3">
                            <div class="border rounded p-2">
                                <h6 class="text-center mb-2">สมาชิกกลุ่ม</h6>
                                <ul class="list-group small mb-2" id="edit-selected-member-list" style="max-height: 150px; overflow-y: auto;">
                                    <li class="list-group-item d-flex align-items-center justify-content-between bg-light">
                                        <div>
                                            <i class="bi bi-person-fill me-2"></i> {{ session('user_name') ?? 'คุณ' }}
                                            <input type="hidden" name="push_ids[]" value="{{ session('firebase_uid') }}">
                                        </div>
                                        <span class="badge bg-secondary">คุณ</span>
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#editAddMemberModal">
                                    + เพิ่มสมาชิก
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-danger" onclick="closeEditGroupChat()">ยกเลิก</button>
                <button type="submit" class="btn btn-success">บันทึก</button>
            </div>
        </form>

        @include('data-management-table.CreateAddMemberChat', [
            'userList' => $userList,
            'modalId' => 'editAddMemberModal',
            'addMemberFunction' => 'addEditMemberToPreview',
            'targetListId' => 'edit-selected-member-list'
        ])
    </div>
</div>

<!-- ✨ Script -->
@push('scripts')
<script>
function previewEditImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('editProfileImagePreview');
        const defaultIcon = document.getElementById('editDefaultIcon');
        output.src = reader.result;
        output.style.display = 'block';
        if (defaultIcon) defaultIcon.style.display = 'none';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function closeEditGroupChat() {
    const popup = document.getElementById('edit-group-chat');
    popup.style.display = 'none';
    popup.classList.remove('d-flex');
}

const currentUserUid = '{{ session('firebase_uid') }}';

function showEditGroupCard(groupData, groupId) {
    const popup = document.getElementById('edit-group-chat');
    popup.style.display = 'flex';
    popup.classList.add('d-flex');

    document.getElementById('editGroupChatForm').action = '{{ url('groupchat') }}/' + groupId + '/update';
    document.getElementById('editGroupName').value = groupData.conversation_name || '';
    document.getElementById('editProfileImagePreview').src = groupData.conversation_photo || '/images/default-group.png';
    document.getElementById('edit_group_id').value = groupId;

    if (!groupData.conversation_photo) {
        document.getElementById('editProfileImagePreview').style.display = 'none';
        document.getElementById('editDefaultIcon').style.display = 'block';
    } else {
        document.getElementById('editProfileImagePreview').style.display = 'block';
        document.getElementById('editDefaultIcon').style.display = 'none';
    }

    const memberList = document.getElementById('edit-selected-member-list');
    memberList.innerHTML = '';

    selectedEditMembers.clear();

    if (groupData.group_member) {
        Object.entries(groupData.group_member).forEach(([uid, member]) => {
            if (uid === currentUserUid) {
                // ตัวเอง (แสดงพิเศษ)
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center justify-content-between bg-light';
                li.innerHTML = `
                    <div>
                        <i class="bi bi-person-fill me-2"></i> {{ session('user_name') ?? 'คุณ' }}
                        <input type="hidden" name="push_ids[]" value="${uid}">
                    </div>
                    <span class="badge bg-secondary">คุณ</span>
                `;
                memberList.appendChild(li);
                selectedEditMembers.add(uid);
            } else {
                const fullName = (member.first_name && member.last_name)
                    ? `${member.first_name} ${member.last_name}`
                    : (member.employee_id || 'ไม่ทราบชื่อ');

                addEditMemberToPreview(uid, fullName, 'edit-selected-member-list');
            }
        });
    }
}

let selectedEditMembers = new Set();

function addEditMemberToPreview(pushId, fullName, listId = 'edit-selected-member-list') {
    if (selectedEditMembers.has(pushId)) {
        alert('สมาชิกนี้ถูกเลือกไว้แล้ว');
        return;
    }

    selectedEditMembers.add(pushId);

    const memberList = document.getElementById(listId);
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex align-items-center justify-content-between';

    li.innerHTML = `
        <div>
            <i class="bi bi-person-fill me-2"></i> ${fullName}
            <input type="hidden" name="push_ids[]" value="${pushId}">
        </div>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeEditMember(this, '${pushId}', '${listId}')">ลบ</button>
    `;

    memberList.appendChild(li);
}

function removeEditMember(button, pushId, listId = 'edit-selected-member-list') {
    selectedEditMembers.delete(pushId);
    button.closest('li').remove();
}
</script>
@endpush
