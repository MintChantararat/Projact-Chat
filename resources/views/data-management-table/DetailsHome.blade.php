@extends('app')

@section('title', 'โปรไฟล์พนักงาน')

@section('content')
<style>
    .container-post {
        overflow-y: auto;
        max-height: calc(100vh - {{ session('role') === 'admin' ? '400px' : '320px' }});
    }
    .chat-input {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 10px;
        padding-bottom: 0px;
        display: flex;
        background: white;
        border-top: 1px solid #ccc;
    }
    .icon-large {
        font-size: 35px;   /* ขนาดไอคอน */
        color:rgb(58, 58, 58);
        margin-right: 10px; /* ระยะห่างจากรูป */
        margin-left: 10px;
    }
</style>

<div class="container py-4">
    <div class="container-post">
        <!-- โพสต์ที่ปักหมุด -->
        <h5 class="mb-3" style="margin-left: 15px;">ปักหมุดโพสต์</h5>
        <div id="pinned-posts">
        @foreach ($pinnedPosts as $post)
            @php
                $poster = getEmployeeByPushId($employees, $post['employee_push_id'] ?? '');
                $fullName = $poster ? $poster['first_name'] . ' ' . $poster['last_name'] : 'ไม่ทราบชื่อ';
                $profilePhoto = $poster && isset($poster['profile_photo']) ? asset($poster['profile_photo']) : asset('images/placeholder.jpg');
                $formattedDate = \Carbon\Carbon::parse($post['timestamp'])->timezone('Asia/Bangkok')->translatedFormat('วันที่ j F Y เวลา H:i น.');
            @endphp
            <div class="card mb-3 position-relative">
                
                {{-- 🔧 กล่องรวมปุ่มด้านขวาบน --}}
                <div class="position-absolute d-flex align-items-center" style="top: 10px; right: 10px; gap: 10px;">
                    {{-- 🗑 ปุ่มลบ (เฉพาะเจ้าของโพสต์) --}}
                    @if (session('firebase_uid') === $post['employee_push_id'])
                        <button type="button"
                            onclick="if(confirm('คุณแน่ใจว่าต้องการลบโพสต์นี้?')) { document.getElementById('delete-form-{{ $post['post_id'] }}').submit(); }"
                            style="background: transparent; border: none; padding: 0;">
                            <i class="bi bi-trash3-fill"
                                style="color: #b60708; font-size: 1.1rem;" title="ลบโพสต์"></i>
                        </button>

                        <form id="delete-form-{{ $post['post_id'] }}" method="POST" action="{{ route('home.deletePost') }}" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="employee_push_id" value="{{ $post['employee_push_id'] }}">
                            <input type="hidden" name="post_id" value="{{ $post['post_id'] }}">
                        </form>
                    @endif

                    {{-- 📌 ปุ่มปักหมุด (เฉพาะ admin) --}}
                    <button class="pin-btn btn p-1"
                        data-post-id="{{ $post['post_id'] }}"
                        data-employee-push-id="{{ $post['employee_push_id'] }}"
                        style="background: transparent; border: none;
                            font-size: 1.1rem; color: #555;
                            {{ session('role') !== 'admin' ? 'pointer-events: none; cursor: default;' : '' }}">
                        <i class="bi {{ ($post['pinned'] ?? false) ? 'bi-pin-angle-fill' : 'bi-pin-angle' }}"
                        data-pinned="{{ ($post['pinned'] ?? false) ? 'true' : 'false' }}"></i>
                    </button>
                </div>

                <div class="card-body d-flex">
                    <img src="{{ $profilePhoto }}" class="rounded-circle me-3" width="50" height="50">
                    <div>
                        <h6 class="mb-0">
                            {{ $fullName }}
                            <small class="text-muted">{{ $formattedDate }}</small>
                        </h6>
                        <p class="mb-0">{{ $post['message'] }}</p>

                        {{-- แสดงภาพทั้งหมด --}}
                        @php $i = 1; @endphp
                        @while (!empty($post["images{$i}"]))
                            <img src="{{ $post["images{$i}"] }}" class="img-fluid my-2" style="max-height: 200px;">
                            @php $i++; @endphp
                        @endwhile
                    </div>
                </div>
            </div>
        @endforeach

        </div>
        

        <!-- โพสต์ทั่วไป -->
        <h5 class="mb-3" style="margin-left: 15px;">โพสต์</h5>
        <div id="normal-posts"></div>
            @php
                function getEmployeeByPushId($employees, $pushId) {
                    return $employees[$pushId] ?? null;
                }
            @endphp
            @foreach ($normalPosts as $post)
            @php
                $poster = getEmployeeByPushId($employees, $post['employee_push_id'] ?? '');
                $fullName = $poster ? $poster['first_name'] . ' ' . $poster['last_name'] : 'ไม่ทราบชื่อ';
                $profilePhoto = $poster && isset($poster['profile_photo']) ? asset($poster['profile_photo']) : asset('images/placeholder.jpg');
                $formattedDate = \Carbon\Carbon::parse($post['timestamp'])->translatedFormat('วันที่ j F Y เวลา H:i น.');
            @endphp
            <div class="card mb-3 position-relative">
                
                {{-- 🔧 กล่องรวมปุ่มด้านขวาบน --}}
                <div class="position-absolute d-flex align-items-center" style="top: 10px; right: 10px; gap: 10px;">
                    {{-- 🗑 ปุ่มลบ (เฉพาะเจ้าของโพสต์) --}}
                    @if (session('firebase_uid') === $post['employee_push_id'])
                        <button type="button"
                            onclick="if(confirm('คุณแน่ใจว่าต้องการลบโพสต์นี้?')) { document.getElementById('delete-form-{{ $post['post_id'] }}').submit(); }"
                            style="background: transparent; border: none; padding: 0;">
                            <i class="bi bi-trash3-fill"
                                style="color: #b60708; font-size: 1.1rem;" title="ลบโพสต์"></i>
                        </button>

                        <form id="delete-form-{{ $post['post_id'] }}" method="POST" action="{{ route('home.deletePost') }}" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="employee_push_id" value="{{ $post['employee_push_id'] }}">
                            <input type="hidden" name="post_id" value="{{ $post['post_id'] }}">
                        </form>
                    @endif

                    {{-- 📌 ปุ่มปักหมุด (เฉพาะ admin) --}}
                    <button class="pin-btn btn p-1"
                        data-post-id="{{ $post['post_id'] }}"
                        data-employee-push-id="{{ $post['employee_push_id'] }}"
                        style="background: transparent; border: none;
                            font-size: 1.1rem; color: #555;
                            {{ session('role') !== 'admin' ? 'pointer-events: none; cursor: default;' : '' }}">
                        <i class="bi {{ ($post['pinned'] ?? false) ? 'bi-pin-angle-fill' : 'bi-pin-angle' }}"
                        data-pinned="{{ ($post['pinned'] ?? false) ? 'true' : 'false' }}"></i>
                    </button>
                </div>

                <div class="card-body d-flex">
                    <img src="{{ $profilePhoto }}" class="rounded-circle me-3" width="50" height="50">
                    <div>
                        <h6 class="mb-0">
                            {{ $fullName }}
                            <small class="text-muted">{{ $formattedDate }}</small>
                        </h6>
                        <p class="mb-0">{{ $post['message'] }}</p>

                        {{-- แสดงภาพทั้งหมด --}}
                        @php $i = 1; @endphp
                        @while (!empty($post["images{$i}"]))
                            <img src="{{ $post["images{$i}"] }}" class="img-fluid my-2" style="max-height: 200px;">
                            @php $i++; @endphp
                        @endwhile
                    </div>
                </div>
            </div>
        @endforeach


        </div>
    </div>

    <!-- แสดงพรีวิวภาพที่เลือก -->
    <div id="image-preview-container" class="position-absolute w-100 d-flex flex-wrap gap-2 p-2" style="bottom: 65px; z-index: 999;"></div>

    <!-- พื้นที่พิมพ์ข้อความ -->
    @if (session('role') === 'admin')
    <div class="chat-input">
        <div class="card-body w-100">
            <form action="{{ route('home.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-center">
                    <!-- ปุ่มเลือกรูป -->
                    <label for="image-upload" class="me-2 mb-0" style="cursor: pointer;">
                        <i class="bi bi-image icon-large fs-4"></i>
                    </label>
                    <input type="file" name="images[]" id="image-upload" accept="image/*" multiple hidden>
                    <input type="text" name="message" class="form-control" placeholder="พิมพ์ข้อความ..." required>
                    <button type="submit" class="btn btn-primary ms-2">โพสต์</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!--การเลือกและพรีวิวรูปภาพ-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageUpload = document.getElementById('image-upload');
    const previewContainer = document.getElementById('image-preview-container');
    let selectedFiles = [];

    imageUpload.addEventListener('change', function (event) {
        const files = Array.from(event.target.files);
        selectedFiles = [...selectedFiles, ...files];

        renderPreviews();
    });

    function renderPreviews() {
        previewContainer.innerHTML = ''; // ล้างพรีวิวทั้งหมดก่อน

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                img.style.marginRight = '10px';

                // ปุ่มลบ
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '&times;';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '0';
                removeBtn.style.right = '0';
                removeBtn.style.background = 'rgba(255, 255, 255, 0.8)';
                removeBtn.style.border = '1px solid #ccc';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.fontSize = '14px';
                removeBtn.style.width = '20px';
                removeBtn.style.height = '20px';
                removeBtn.style.display = 'flex';
                removeBtn.style.alignItems = 'center';
                removeBtn.style.justifyContent = 'center';

                removeBtn.addEventListener('click', function () {
                    selectedFiles.splice(index, 1);
                    renderPreviews();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });

        // อัปเดต input file (สำคัญมาก)
        updateFileInput();
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageUpload.files = dataTransfer.files;
    }
});
</script>


<!--ปักหมุดโพสต์-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pin-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const employeePushId = this.dataset.employeePushId;
            const icon = this.querySelector('i');
            const isPinned = icon.dataset.pinned === 'true';
            const card = this.closest('.card');

            // แสดงไอคอนโหลด (optional)
            icon.classList.remove('bi-pin-angle', 'bi-pin-angle-fill');
            icon.classList.add('spinner-border', 'spinner-border-sm');

            fetch(`/home/pin-post`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    post_id: postId,
                    employee_push_id: employeePushId,
                    pinned: !isPinned
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // เปลี่ยนไอคอนปักหมุด
                    icon.classList.remove('spinner-border', 'spinner-border-sm');
                    icon.classList.toggle('bi-pin-angle-fill', !isPinned);
                    icon.classList.toggle('bi-pin-angle', isPinned);
                    icon.dataset.pinned = (!isPinned).toString();

                    // ย้ายการ์ด
                    const pinnedContainer = document.getElementById('pinned-posts');
                    const normalContainer = document.getElementById('normal-posts');
                    if (!isPinned) {
                        pinnedContainer.prepend(card); // ไปหมวดปักหมุด
                    } else {
                        normalContainer.prepend(card); // ไปหมวดทั่วไป
                    }
                }
            })
            .catch(error => {
                console.error('เกิดข้อผิดพลาด:', error);
                alert('ไม่สามารถปักหมุดได้');
                icon.classList.remove('spinner-border', 'spinner-border-sm');
                icon.classList.add(isPinned ? 'bi-pin-angle-fill' : 'bi-pin-angle');
            });
        });
    });
});
</script>





@endsection