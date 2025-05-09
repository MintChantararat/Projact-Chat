@extends('app')

@section('content')
<!-- การ์ดรวมทั้งหมด -->
<div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 justify-content-center align-items-center" id="photoGalleryCard" style="overflow-y: auto; display: none;">
    <div class="bg-white rounded-4 shadow-lg p-4 w-100" style="max-width: 800px;">
        <h5 class="mb-3">ประวัติการส่งรูปภาพ</h5>

        @if (!empty($groupedImages))
            @foreach ($groupedImages as $month => $images)
                <div class="mb-4">
                    <h6 class="fw-bold">{{ $month }}</h6>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($images as $img)
                            <div style="width: 100px; height: 100px; overflow: hidden; border-radius: 10px; cursor: pointer;" onclick="showFullImage('{{ $img }}')">
                                <img src="{{ $img }}" alt="image" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">ไม่มีประวัติการส่งรูปภาพ</p>
        @endif

        <div class="text-end mt-4">
            <button class="btn btn-secondary" onclick="hideGalleryCard()">ย้อนกลับ</button>
        </div>
    </div>
</div>

<!-- การ์ดแสดงรูปเต็ม -->
<div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 z-4 d-none d-flex justify-content-center align-items-center" id="fullImageCard">
    <div class="bg-white p-4 rounded-4 text-center shadow-lg" style="width: 100%; max-width: 500px; max-height: 90vh;">
        <h6 class="mb-3">ประวัติการส่งรูปภาพ</h6>
        
        <div style="width: 100%; height: auto;">
            <img id="fullImage" src=""
                style="max-width: 100%; height: auto; object-fit: contain; border-radius: 12px;">
        </div>

        <div class="mt-3">
            <a id="downloadBtn" href="#" download class="btn btn-success me-2">⬇️ บันทึก</a>
            <button class="btn btn-secondary" onclick="closeFullImage()">ย้อนกลับ</button>
        </div>
    </div>
</div>


<script>
    function showGalleryCard() {
        const card = document.getElementById('photoGalleryCard');
        if (card) {
            card.style.display = 'flex'; // ✅ แสดง
        }
    }

    function hideGalleryCard() {
        const card = document.getElementById('photoGalleryCard');
        if (card) {
            card.style.display = 'none'; // ✅ ซ่อน
        }
    }

    function showFullImage(src) {
        const fullImage = document.getElementById('fullImage');
        const downloadBtn = document.getElementById('downloadBtn');
        const fullImageCard = document.getElementById('fullImageCard');

        fullImage.src = src;
        downloadBtn.href = src;

        fullImageCard.style.display = 'flex'; // ✅ เปิด
    }

    function closeFullImage() {
        const fullImageCard = document.getElementById('fullImageCard');
        fullImageCard.style.display = 'none'; // ✅ ซ่อน
    }


    document.addEventListener('DOMContentLoaded', function () {
        const galleryBtn = document.getElementById('openGalleryBtn');
        if (galleryBtn) {
            galleryBtn.addEventListener('click', showGalleryCard);
        }
    });
</script>

<script>
function showFullImage(src) {
    const fullImage = document.getElementById('fullImage');
    const downloadBtn = document.getElementById('downloadBtn');

    // แสดงรูปในการ์ด
    fullImage.src = src;

    // ตั้งค่าปุ่มดาวน์โหลด
    downloadBtn.href = src;

    // ตั้งชื่อไฟล์แบบอัตโนมัติ (เช่นจาก path/to/filename.jpg)
    const filename = src.split('/').pop();
    downloadBtn.setAttribute('download', filename);

    // แสดงการ์ด
    document.getElementById('fullImageCard').classList.remove('d-none');
}

function closeFullImage() {
    document.getElementById('fullImageCard').classList.add('d-none');
}
</script>

@endsection

