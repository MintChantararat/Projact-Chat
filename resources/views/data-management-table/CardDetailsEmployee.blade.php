<!-- resources/views/data-management-table/CardDetailsEmployee.blade.php -->

<div id="employee-details-card" class="position-absolute top-0 start-0 bg-dark bg-opacity-50 d-none justify-content-center align-items-center z-3" style="width: 100%; height: 100%;">
    <div class="bg-white rounded-4 shadow-lg p-4 w-75 position-relative">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <img id="emp-photo" src="/path/to/profile-placeholder.png" class="img-fluid rounded" alt="Profile Photo" style="max-height: 250px;">
            </div>
            <div class="col-md-8">
                <h4 class="mb-3">รายละเอียดพนักงาน</h4>
                <div class="mb-2"><strong>รหัสพนักงาน:</strong> <span id="emp-id"></span></div>
                <div class="mb-2"><strong>ชื่อ-สกุล:</strong> <span id="emp-name"></span></div>
                <div class="mb-2"><strong>วัน/เดือน/ปีเกิด:</strong> <span id="emp-birthday"></span></div>
                <div class="mb-2"><strong>แผนก:</strong> <span id="emp-department"></span></div>
                <div class="mb-2"><strong>อีเมล:</strong> <span id="emp-email"></span></div>
                <div class="mb-2"><strong>เบอร์โทรศัพท์:</strong> <span id="emp-phone"></span></div>
            </div>
        </div>

        <button onclick="hideCard()" class="btn btn-outline-secondary position-absolute bottom-0 end-0 m-3">ปิด</button>
    </div>
</div>

<script>
function showCard(data) {
    document.getElementById('emp-id').innerText = data.employee_id;
    document.getElementById('emp-name').innerText = data.first_name + " " + data.last_name;
    document.getElementById('emp-birthday').innerText = data.birthday ?? '-';
    document.getElementById('emp-department').innerText = data.department ?? '-';
    document.getElementById('emp-email').innerText = data.email ?? '-';
    document.getElementById('emp-phone').innerText = data.phone_number ?? '-';
    document.getElementById('emp-photo').src = data.profile_photo ?? '/path/to/default.png';
    document.getElementById('employee-details-card').classList.remove('d-none');
    document.getElementById('employee-details-card').classList.add('d-flex');
}

function hideCard() {
    document.getElementById('employee-details-card').classList.add('d-none');
    document.getElementById('employee-details-card').classList.remove('d-flex');
}
</script>
