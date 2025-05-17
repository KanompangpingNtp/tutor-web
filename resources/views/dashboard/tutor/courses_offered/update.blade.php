@extends('dashboard.layouts.master')
@section('content')
<!-- Card หรือ widget อื่น ๆ -->
<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">อัพเดทคอร์สที่เปิดสอน</h4>

                <form action="{{ route('TutorCoursesOfferedUpdate', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- ชื่อคอร์ส -->
                    <div class="mb-3">
                        <label class="form-label">ชื่อคอร์ส</label>
                        <input type="text" name="course_name" class="form-control" value="{{ old('course_name', $course->course_name) }}" required>
                    </div>

                    <!-- วิชา -->
                    <div class="mb-3">
                        <label class="form-label">วิชา</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- เลือกวิชา --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subject->id == $course->subject_id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ติวเตอร์ -->
                    <div class="mb-3">
                        <label class="form-label">ติวเตอร์</label>
                        <select name="tutor_id" class="form-select" required>
                            <option value="">-- เลือกติวเตอร์ --</option>
                            @foreach($users as $tutor)
                            <option value="{{ $tutor->id }}" {{ $tutor->id == $course->user_id ? 'selected' : '' }}>
                                {{ $tutor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- รายละเอียด -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="details_update" name="course_details">{{ $course->course_details }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ภาพหน้าปกคอร์ส</label>
                        <input type="file" name="course_files_title" class="form-control" multiple>
                    </div>

                    <!-- แสดงไฟล์หน้าปก (status = 1) -->
                    @if ($course->files->where('status', 1)->count())
                    <div class="mb-3">
                        <label class="form-label">ภาพหน้าปกที่มีอยู่</label>
                        @foreach ($course->files->where('status', 1) as $file)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="delete_files[]" value="{{ $file->id }}" id="deleteFile{{ $file->id }}">
                            <label class="form-check-label" for="deleteFile{{ $file->id }}">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">ไฟล์ {{ $file->file_path }}</a>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="file_post" class="form-label">แนบไฟล์ภาพและวิดีโอ</label>
                        <input type="file" class="form-control" id="file_post" name="course_files[]" multiple>
                        <small class="text-muted">ประเภทไฟล์ที่รองรับ: jpg, jpeg, png</small>
                        <div id="file-list" class="mt-1">
                            <div class="d-flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                    <!-- แสดงไฟล์ภาพ/วิดีโอ (status = 2) -->
                    @if ($course->files->where('status', 2)->count())
                    <div class="mb-3">
                        <label class="form-label">ไฟล์แนบที่มีอยู่</label>
                        @foreach ($course->files->where('status', 2) as $file)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="delete_files[]" value="{{ $file->id }}" id="deleteFile{{ $file->id }}">
                            <label class="form-check-label" for="deleteFile{{ $file->id }}">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">ไฟล์ {{ $file->file_path }}</a>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">ราคาคอร์ส (บาท)</label>
                        <input type="number" name="price" class="form-control" value="{{ $course->price }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">อธิบายระยะเวลาคอร์ส</label>
                        <input type="text" name="course_duration_hour" class="form-control" value="{{ old('course_duration_hour', $course->course_duration_hour) }}" required>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-amount-time">+ เพิ่มจำนวนชั่วโมง</button>

                    <div id="amount-time-container">
                        @foreach($course->amountTimes as $amount)
                        <div class="amount-time-set border p-3 mb-3 rounded">
                            <!-- ซ่อนไอดีไว้ -->
                            <input type="hidden" name="amount_time_hour_id[]" value="{{ $amount->id }}">
                            <label class="form-label">จำนวนชั่วโมง</label>
                            <input type="number" name="amount_time_hour[]" class="form-control" value="{{ $amount->amount_time_hour }}" required>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-amount-time">ลบชุดนี้</button>
                        </div>
                        @endforeach
                    </div>

                    <!-- template clone -->
                    <template id="amount-time-template">
                        <div class="amount-time-set border p-3 mb-3 rounded">
                            <input type="hidden" name="amount_time_hour_id[]" value="">
                            <label class="form-label">จำนวนชั่วโมง</label>
                            <input type="number" name="amount_time_hour[]" class="form-control" required>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-amount-time">ลบชุดนี้</button>
                        </div>
                    </template>

                    <!-- ปุ่มเพิ่ม -->
                    <button type="button" class="btn btn-secondary btn-sm mb-3 add-schedule-update">+ เพิ่มเวลาเรียน</button>

                    <!-- แสดงเวลาเรียนที่มีอยู่ -->
                    <div id="teaching-schedule-container">
                        @foreach($course->teachings as $teaching)
                        <div class="teaching-schedule-set border p-3 mb-3 rounded">
                            <!-- ซ่อน teaching_id -->
                            <input type="hidden" name="teaching_id[]" value="{{ $teaching->id }}">

                            <div class="mb-3">
                                <label class="form-label">วันสอน</label>
                                <select name="course_day_id[]" class="form-control" required>
                                    <option value="">-- เลือกวัน --</option>
                                    @foreach($courseDay as $day)
                                    <option value="{{ $day->id }}" {{ $teaching->course_day_id == $day->id ? 'selected' : '' }}>
                                        {{ $day->day_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">เวลาเริ่มสอน</label>
                                    <input type="time" name="course_starttime[]" class="form-control" value="{{ $teaching->course_starttime }}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">เวลาสิ้นสุดการสอน</label>
                                    <input type="time" name="course_endtime[]" class="form-control" value="{{ $teaching->course_endtime }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ค่าจ้างต่อชั่วโมง (บาท)</label>
                                <input type="number" step="0.01" name="hourly_rate[]" class="form-control" value="{{ $teaching->hourly_rate }}" required>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-schedule">ลบชุดนี้</button>
                        </div>
                        @endforeach
                    </div>

                    <!-- Template สำหรับ clone -->
                    <template id="schedule-template">
                        <div class="teaching-schedule-set border p-3 mb-3 rounded">
                            <div class="mb-3">
                                <label class="form-label">วันสอน</label>
                                <select name="course_day_id[]" class="form-control" required>
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
                    </template>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">อัปเดตคอร์ส</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ck-editor__editable {
        min-height: 300px !important;
    }

</style>

<script src="{{asset('js/multipart_files.js')}}"></script>
<script src="{{asset('js/datatable.js')}}"></script>

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
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-schedule-update')) {
            const template = document.querySelector('#schedule-template');
            const clone = template.content.cloneNode(true);
            const container = document.querySelector('#teaching-schedule-container');
            container.appendChild(clone);
        }

        if (e.target.classList.contains('remove-schedule')) {
            const scheduleSets = document.querySelectorAll('.teaching-schedule-set');
            if (scheduleSets.length > 1) {
                e.target.closest('.teaching-schedule-set').remove();
            }
        }
    });

</script>

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


@endsection
