const fileInput = document.getElementById('file_post');
const fileListContainer = document.querySelector('#file-list .d-flex');

// อัปเดตรายการไฟล์
fileInput.addEventListener('change', function() {
    fileListContainer.innerHTML = ''; // เคลียร์รายการเก่า
    Array.from(fileInput.files).forEach((file, index) => {
        // สร้าง wrapper สำหรับรูปภาพหรือไอคอน PDF
        const fileWrapper = document.createElement('div');
        fileWrapper.className = 'position-relative d-inline-block text-center';

        // ตรวจสอบประเภทไฟล์
        let previewElement;
        if (file.type.startsWith('image/')) {
            // สร้างภาพตัวอย่างสำหรับไฟล์รูปภาพ
            previewElement = document.createElement('img');
            previewElement.src = URL.createObjectURL(file);
            previewElement.alt = file.name;
        } else if (file.type === 'application/pdf') {
            // แสดงไอคอนแทนไฟล์ PDF
            previewElement = document.createElement('img');
            previewElement.src =
                'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg';
            previewElement.alt = 'PDF File';
        } else {
            previewElement = document.createElement('div');
            previewElement.textContent = 'ไฟล์ไม่รองรับ';
        }

        // กำหนดขนาดและสไตล์ของรูปภาพ/ไอคอน
        previewElement.style.width = '100px';
        previewElement.style.height = '100px';
        previewElement.style.objectFit = 'cover';
        previewElement.className = 'border rounded';

        // ปุ่มลบ
        const removeButton = document.createElement('button');
        removeButton.textContent = '×';
        removeButton.className = 'btn btn-danger btn-sm position-absolute';
        removeButton.style.top = '0';
        removeButton.style.right = '0';
        removeButton.setAttribute('data-index', index);

        removeButton.addEventListener('click', () => {
            removeFile(index);
        });

        // ชื่อไฟล์
        const fileName = document.createElement('p');
        fileName.textContent = file.name;
        fileName.className = 'mt-2 text-truncate';
        fileName.style.width = '100px';

        // รวมทุกอย่างเข้ากับ wrapper
        fileWrapper.appendChild(previewElement);
        fileWrapper.appendChild(removeButton);
        fileWrapper.appendChild(fileName);

        fileListContainer.appendChild(fileWrapper);
    });
});


// ลบไฟล์ออกจากรายการ
function removeFile(index) {
    const fileArray = Array.from(fileInput.files);
    fileArray.splice(index, 1); // ลบไฟล์จากอาร์เรย์

    // สร้าง FileList ใหม่
    const dataTransfer = new DataTransfer();
    fileArray.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;

    // อัปเดตรายการใน UI
    fileInput.dispatchEvent(new Event('change'));
}
