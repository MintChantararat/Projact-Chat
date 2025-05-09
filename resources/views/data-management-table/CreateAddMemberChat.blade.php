@php
  $modalId = $modalId ?? 'addMemberModal';
  $addMemberFunction = $addMemberFunction ?? 'addMemberToPreview';
  $targetListId = $targetListId ?? 'selected-member-list'; // ✅ ระบุ id ของ ul
@endphp

<!-- Modal เพิ่มสมาชิก -->
<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="false" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">เพิ่มสมาชิก</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-3" placeholder="ค้นหาบุคคล">
        <h6>รายชื่อติดต่อ</h6>
        <ul class="list-group">
            @foreach ($userList as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $user['first_name'] }} {{ $user['last_name'] }}
                    <button type="button" class="btn btn-outline-success btn-sm"
                      onclick="{{ $addMemberFunction }}('{{ $user['push_id'] }}', '{{ $user['first_name'] }} {{ $user['last_name'] }}', '{{ $targetListId }}')">
                        เพิ่ม
                    </button>
                </li>
            @endforeach
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">เสร็จสิ้น</button>
      </div>
    </div>
  </div>
</div>
