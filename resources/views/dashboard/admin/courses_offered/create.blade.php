@extends('dashboard.layouts.master')
@section('content')
<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">

                <h4 class="fw-bold py-3 mb-4 text-center">สร้างคอร์สที่เปิดสอน</h4>

                <form action="{{route('CoursesOfferedCreate')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">ชื่อคอร์ส</label>
                        <input type="text" name="course_name" class="form-control" required></input>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">วิชา</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- เลือกวิชา --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ติวเตอร์</label>
                        <select name="tutor_id" class="form-select" required>
                            <option value="">-- เลือกติวเตอร์ --</option>
                            @foreach($users as $tutor)
                            <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="รายละเอียดคอร์ส" id="details" name="course_details"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ภาพหน้าปกคอร์ส</label>
                        <input type="file" name="course_files_title" class="form-control" multiple>
                    </div>

                    <div class="mb-3">
                        <label for="file_post" class="form-label">แนบไฟล์ภาพและวิดีโอ</label>
                        <input type="file" class="form-control" id="file_post" name="course_files[]" multiple>
                        <small class="text-muted">ประเภทไฟล์ที่รองรับ: jpg, jpeg, png</small>
                        <div id="file-list" class="mt-1">
                            <div class="d-flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ราคาคอร์ส (บาท)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ระยะเวลาคอร์ส</label>
                        <input type="text" name="course_duration_hour" class="form-control" required>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-amount-time">+ เพิ่มจำนวนชั่วโมง</button>

                    <!-- กล่องเก็บ input -->
                    <div id="amount-time-container">
                        <div class="amount-time-set border p-3 mb-3 rounded">
                            <label class="form-label">จำนวนชั่วโมง</label>
                            <input type="text" name="amount_time_hour[]" class="form-control" required>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-amount-time">ลบชุดนี้</button>
                        </div>
                    </div>

                    <!-- template clone -->
                    <template id="amount-time-template">
                        <div class="amount-time-set border p-3 mb-3 rounded">
                            <label class="form-label">จำนวนชั่วโมง</label>
                            <input type="text" name="amount_time_hour[]" class="form-control" required>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-amount-time">ลบชุดนี้</button>
                        </div>
                    </template>

                    <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-schedule">+ เพิ่มเวลาเรียน</button>

                    <div id="teaching-schedule-container">
                        <div class="teaching-schedule-set border p-3 mb-3 rounded">
                            <div id="result" class="mt-3 text-primary"></div>

                            <div class="mb-3">
                                <label class="form-label">เลือกวันสอน</label>
                                <select name="course_day_id[]" class="form-select" required>
                                    <option value="">-- เลือกวัน --</option>
                                    @foreach($courseDay as $day)
                                    <option value="{{ $day->id }}">{{ $day->day_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">เวลาเริ่มสอน</label>
                                    <input type="time" name="course_starttime[]" class="form-control" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">เวลาสิ้นสุดการสอน</label>
                                    <input type="time" name="course_endtime[]" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ค่าจ้างต่อชั่วโมง (บาท)</label>
                                <input type="number" step="0.01" name="hourly_rate[]" class="form-control" required>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-schedule">ลบชุดนี้</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">บันทึกคอร์ส</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-amount-time').addEventListener('click', function() {
        const template = document.getElementById('amount-time-template').content.cloneNode(true);
        document.getElementById('amount-time-container').appendChild(template);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-amount-time')) {
            e.target.closest('.amount-time-set').remove();
        }
    });

</script>

<style>
    .ck-editor__editable {
        min-height: 300px !important;
    }

</style>

<script src="{{asset('js/multipart_files.js')}}"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("#details, #details_update").forEach(textarea => {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    const editable = editor.ui.view.editable.element;
                    editable.style.resize = "none";
                    editable.style.overflow = "auto";
                })
                .catch(error => {
                    console.error("CKEditor error:", error);
                });
        });
    });

</script>

<script>
    document.getElementById('add-schedule').addEventListener('click', function() {
        const container = document.getElementById('teaching-schedule-container');
        const newSet = container.firstElementChild.cloneNode(true);

        // เคลียร์ค่าที่ถูก clone
        newSet.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(newSet);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-schedule')) {
            const scheduleSets = document.querySelectorAll('.teaching-schedule-set');
            if (scheduleSets.length > 1) {
                e.target.closest('.teaching-schedule-set').remove();
            }
        }
    });

</script>

<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-schedule-update')) {
            const modalBody = e.target.closest('.modal-body');
            const template = modalBody.querySelector('#schedule-template');
            const clone = template.content.cloneNode(true);
            modalBody.querySelector('#teaching-schedule-container').appendChild(clone);
        }

        if (e.target.classList.contains('remove-schedule')) {
            const scheduleSets = e.target.closest('.modal-body').querySelectorAll('.teaching-schedule-set');
            if (scheduleSets.length > 1) {
                e.target.closest('.teaching-schedule-set').remove();
            }
        }
    });

</script>

@endsection
