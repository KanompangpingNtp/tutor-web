@extends('dashboard.layouts.master')
@section('title', 'จัดการคอร์สที่เปิดสอน')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">จัดการคอร์สที่เปิดสอน</h4>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    เพิ่มคอร์ส
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{route('TutorCoursesOfferedCreate')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มคอร์ส</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
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
                                        <select id="user_id_display" class="form-select" disabled>
                                            <option selected>
                                                {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                            </option>
                                        </select>
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">ระยะเวลาคอร์ส (ชั่วโมง)</label>
                                        <input type="number" name="course_duration_hour" class="form-control" step="0.1" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">ราคาคอร์ส (บาท)</label>
                                        <input type="number" name="course_price" class="form-control" step="0.01" min="0" required>
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
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary">บันทึกคอร์ส</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <br>
                <br>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th>ชื่อคอร์ส</th>
                            <th>วิชา</th>
                            <th>ติวเตอร์</th>
                            <th>ระยะเวลา (ชม.)</th>
                            <th>ราคา (บาท)</th>
                            <th>รายละเอียด</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->course_name }}</td>
                            <td>{{ $course->subject->name ?? '-' }}</td>
                            <td>{{ $course->user->name ?? '-' }}</td>
                            <td>{{ $course->course_duration_hour }} ชม.</td>
                            <td>{{ number_format($course->course_price, 2) }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#courseDetailsModal{{ $course->id }}">
                                    {{ Str::limit(strip_tags($course->course_details), 30) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#filesModal{{ $course->id }}">
                                        <i class='bx bxs-file'></i>
                                    </button>

                                    <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $course->id }}">
                                        <i class='bx bx-edit'></i>
                                    </button>

                                    <form action="{{ route('TutordeleteCourse', $course->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบหรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-1">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach($courses as $course)
                <div class="modal fade" id="filesModal{{ $course->id }}" tabindex="-1" aria-labelledby="filesModalLabel{{ $course->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filesModalLabel{{ $course->id }}">ไฟล์คอร์ส: {{ $course->course_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            </div>
                            <div class="modal-body">
                                @foreach($course->files as $file)
                                @if($file->status == 1)
                                <p><strong class="text-primary">[ไฟล์ปก]</strong></p>
                                @endif

                                @if(Str::startsWith($file->file_type, 'mp4') || Str::startsWith($file->file_type, 'webm'))
                                <video src="{{ asset('storage/' . $file->file_path) }}" width="100%" controls class="mb-3"></video>
                                @else
                                <!-- ปรับขนาดภาพ -->
                                <img src="{{ asset('storage/' . $file->file_path) }}" class="img-fluid mb-3" style="max-width: 80%; height: auto;" />
                                @endif
                                @endforeach
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="courseDetailsModal{{ $course->id }}" tabindex="-1" aria-labelledby="courseDetailsModalLabel{{ $course->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="courseDetailsModalLabel{{ $course->id }}">รายละเอียดคอร์ส</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- แสดงรายละเอียดทั้งหมด -->
                                <p>{!! $course->course_details !!}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

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
        const editorIds = ["#details", "#details_update"];

        editorIds.forEach(id => {
            const element = document.querySelector(id);
            if (element) {
                ClassicEditor
                    .create(element)
                    .then(editor => {
                        // Optional: เก็บ editor instance ไว้ใช้งานต่อได้ที่นี่
                    })
                    .catch(error => {
                        console.error("CKEditor error for", id, ":", error);
                    });
            }
        });
    });

</script>

@endsection
