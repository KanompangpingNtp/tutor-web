@extends('dashboard.layouts.master')

@section('title', 'รายละเอียด')

@section('content')
<!-- Card หรือ widget อื่น ๆ -->
<div class="row">
    <!-- ตัวอย่าง Card -->
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">รายละเอียดของ</h4>

                <form action="">
                    @csrf
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="กรอกข้อมูล" id="details" name="details"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file_post" class="form-label">แนบไฟล์ภาพและ PDF</label>
                        <input type="file" class="form-control" id="file_post" name="file_post[]" multiple>
                        <small class="text-muted">ประเภทไฟล์ที่รองรับ: jpg, jpeg, png, pdf</small>
                        <div id="file-list" class="mt-1">
                            <div class="d-flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary mt-2" type="submit">บันทึก</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
    .ck-editor__editable {
    min-height: 500px !important;
    height: 400px !important;
}

</style>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector("#details"))
            .then(editor => {
            })
            .catch(error => {
                console.error("CKEditor error:", error);
            });
    });

</script>
@endsection
