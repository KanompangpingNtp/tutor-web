@extends('dashboard.layouts.master')

@section('title', 'จัดการข้อมูลส่วนตัว')

@section('content')

<style>
    .ck-editor__editable {
        min-height: 150px !important;
    }

</style>

<!-- Card หรือ widget อื่น ๆ -->
<div class="row">
    <!-- ตัวอย่าง Card -->
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">จัดการข้อมูลส่วนตัว</h4>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <!-- รูปภาพตรงกลาง -->
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $users->profile_image ? asset('storage/' . $users->profile_image) : asset('navbar/user-unknow.png') }}" alt="Profile" class="rounded-circle img-thumbnail mb-3" width="150">
                                </div>

                                <!-- ข้อความชิดซ้าย -->
                                <div class="mt-2 text-start">
                                    <h5>ชื่อ: {{$users->name}}</h5>
                                    <p class="text-muted">อีเมล: {{$users->email}}</p>
                                    <p class="text-muted">เบอร์ติดต่อ: {{$users->phone}}</p>
                                </div>

                                <!-- ปุ่มเปิด Modal -->
                                <div class="mt-3 text-center">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#userModal{{$users->id}}">
                                        แก้ไขข้อมูล
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="userModal{{$users->id}}" tabindex="-1" aria-labelledby="userModalLabel{{$users->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('AdminTutorUpdateDetails', $users->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userModalLabel{{$users->id}}">แก้ไขข้อมูลผู้ใช้</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- ชื่อ -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">ชื่อ</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $users->name }}" required>
                                        </div>
                                        <!-- อีเมล -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">อีเมล</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $users->email }}" required>
                                        </div>
                                        <!-- เบอร์โทร -->
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">เบอร์ติดต่อ</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $users->phone }}">
                                        </div>
                                        <!-- รูปโปรไฟล์ -->
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">รูปโปรไฟล์</label>
                                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                            @if($users->profile_image)
                                            <img src="{{ asset('storage/' . $users->profile_image) }}" alt="Current Profile" class="img-thumbnail mt-2" width="100">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">บันทึก</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('AdminTeacherResumeCreate', $users->id) }}" method="POST" class="container mt-2">
                                    @csrf
                                    <!-- รางวัลที่ได้รับ -->
                                    <div class="mb-3">
                                        <label class="form-label">รางวัลที่ได้รับ:</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-awards-btn">+ เพิ่มรางวัล</button>
                                        <div id="awards-group">
                                            @if($teacherResume && $teacherResume->awards)
                                            @foreach(json_decode($teacherResume->awards) as $award)
                                            <div class="input-group mb-2 awards-set">
                                                <input type="text" name="awards[]" class="form-control" value="{{ $award }}" placeholder="ระบุรางวัลที่คุณเคยได้รับ เช่น รางวัลครูดีเด่น...">
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="input-group mb-2 awards-set">
                                                <input type="text" name="awards[]" class="form-control" placeholder="ระบุรางวัลที่คุณเคยได้รับ เช่น รางวัลครูดีเด่น...">
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- วุฒิบัตร / ใบรับรอง -->
                                    <div class="mb-3">
                                        <label class="form-label">วุฒิบัตร / ใบรับรอง:</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-certificates-btn">+ เพิ่มวุฒิบัตร / ใบรับรอง</button>
                                        <div id="certificates-group">
                                            @if($teacherResume && $teacherResume->certificates)
                                            @foreach(json_decode($teacherResume->certificates) as $certificate)
                                            <div class="input-group mb-2 certificates-set">
                                                <input type="text" name="certificates[]" class="form-control" value="{{ $certificate }}" placeholder="เช่น การอบรม, ใบรับรอง, ประกาศนียบัตร...">
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="input-group mb-2 certificates-set">
                                                <input type="text" name="certificates[]" class="form-control" placeholder="เช่น การอบรม, ใบรับรอง, ประกาศนียบัตร...">
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- การศึกษา -->
                                    <div class="mb-3">
                                        <label class="form-label">การศึกษา:</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-education-btn">+ เพิ่มการศึกษา</button>
                                        <div id="education-group">
                                            @if($teacherResume && $teacherResume->educations)
                                            @foreach(json_decode($teacherResume->educations) as $education)
                                            <div class="row g-2 mb-2 education-set">
                                                <div class="col-md-4">
                                                    <input type="text" name="education_level[]" class="form-control" value="{{ $education[0] ?? '' }}" placeholder="ระดับการศึกษา (เช่น ป.ตรี)">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="institution[]" class="form-control" value="{{ $education[1] ?? '' }}" placeholder="ชื่อสถาบัน">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="year[]" class="form-control" value="{{ $education[2] ?? '' }}" placeholder="ปีที่จบ">
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="row g-2 mb-2 education-set">
                                                <div class="col-md-4">
                                                    <input type="text" name="education_level[]" class="form-control" placeholder="ระดับการศึกษา (เช่น ป.ตรี)">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="institution[]" class="form-control" placeholder="ชื่อสถาบัน">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="year[]" class="form-control" placeholder="ปีที่จบ">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- ประสบการณ์สอน -->
                                    <div class="mb-3">
                                        <label class="form-label">ประสบการณ์สอน:</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-teaching-btn">+ เพิ่มประสบการณ์สอน</button>
                                        <div id="teaching-group">
                                            @if($teacherResume && $teacherResume->teachings)
                                            @foreach(json_decode($teacherResume->teachings) as $teaching)
                                            <div class="row g-2 mb-2 teaching-set">
                                                <div class="col-md-4">
                                                    <input type="text" name="teaching_place[]" class="form-control" value="{{ $teaching[0] ?? '' }}" placeholder="โรงเรียน/สถาบัน">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="subject[]" class="form-control" value="{{ $teaching[1] ?? '' }}" placeholder="รายวิชา">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="teaching_duration[]" class="form-control" value="{{ $teaching[2] ?? '' }}" placeholder="ระยะเวลา (เช่น 2 ปี)">
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="row g-2 mb-2 teaching-set">
                                                <div class="col-md-4">
                                                    <input type="text" name="teaching_place[]" class="form-control" placeholder="โรงเรียน/สถาบัน">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="subject[]" class="form-control" placeholder="รายวิชา">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="teaching_duration[]" class="form-control" placeholder="ระยะเวลา (เช่น 2 ปี)">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- ความสำเร็จในการสอน -->
                                    <div class="mb-3">
                                        <label for="teaching_success" class="form-label">ความสำเร็จในการสอน:</label>
                                        <textarea id="teaching_success" name="teaching_success" class="form-control" rows="4" placeholder="เช่น นักเรียนได้รางวัล, คะแนนสูง, สอบติด ฯลฯ">{{ $teacherResume->teaching_success ?? old('teaching_success') }}</textarea>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editorIds = ["teaching_success"];

        editorIds.forEach(id => {
            ClassicEditor
                .create(document.querySelector("#" + id))
                .catch(error => {
                    console.error("CKEditor error (" + id + "):", error);
                });
        });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ฟังก์ชันที่ใช้ลบชุดที่ไม่มีข้อมูล
        function removeEmptySets(group) {
            group.querySelectorAll('.input-group').forEach(function(inputGroup) {
                const input = inputGroup.querySelector('input');
                if (!input.value.trim()) {
                    inputGroup.remove(); // ลบชุด input ที่ไม่มีข้อมูล
                }
            });
        }

        // รางวัลที่ได้รับ
        const maxAwards = 5;
        const addAwardsBtn = document.getElementById("add-awards-btn");
        const awardsGroup = document.getElementById("awards-group");
        const awardsSet = awardsGroup.querySelector(".awards-set"); // เรียก .awards-set ที่มีอยู่ใน DOM

        if (awardsSet) {
            addAwardsBtn.addEventListener("click", function() {
                const currentSets = awardsGroup.querySelectorAll(".awards-set").length;

                if (currentSets < maxAwards) {
                    const clone = awardsSet.cloneNode(true);
                    clone.querySelector("input").value = ""; // ล้างค่าชุดใหม่
                    awardsGroup.appendChild(clone);
                } else {
                    alert("สามารถเพิ่มได้สูงสุด 5 ช่องเท่านั้น");
                }
            });

            // ลบชุดที่ไม่มีข้อมูล
            removeEmptySets(awardsGroup);
        }

        // วุฒิบัตร / ใบรับรอง
        const maxCertificates = 5;
        const addCertificatesBtn = document.getElementById("add-certificates-btn");
        const certificatesGroup = document.getElementById("certificates-group");
        const certificatesSet = certificatesGroup.querySelector(".certificates-set"); // เรียก .certificates-set ที่มีอยู่ใน DOM

        if (certificatesSet) {
            addCertificatesBtn.addEventListener("click", function() {
                const currentSets = certificatesGroup.querySelectorAll(".certificates-set").length;

                if (currentSets < maxCertificates) {
                    const clone = certificatesSet.cloneNode(true);
                    clone.querySelector("input").value = ""; // ล้างค่าชุดใหม่
                    certificatesGroup.appendChild(clone);
                } else {
                    alert("สามารถเพิ่มได้สูงสุด 5 ช่องเท่านั้น");
                }
            });

            // ลบชุดที่ไม่มีข้อมูล
            removeEmptySets(certificatesGroup);
        }

        // การศึกษา
        const maxEducation = 5;
        const addEducationBtn = document.getElementById("add-education-btn");
        const educationGroup = document.getElementById("education-group");
        const educationSet = educationGroup.querySelector(".education-set"); // เรียก .education-set ที่มีอยู่ใน DOM

        if (educationSet) {
            addEducationBtn.addEventListener("click", function() {
                const currentSets = educationGroup.querySelectorAll(".education-set").length;

                if (currentSets < maxEducation) {
                    const clone = educationSet.cloneNode(true);
                    // ล้างค่าชุดใหม่
                    clone.querySelectorAll("input").forEach(input => input.value = "");
                    educationGroup.appendChild(clone);
                } else {
                    alert("สามารถเพิ่มได้สูงสุด 5 ชุดเท่านั้น");
                }
            });

            // ลบชุดที่ไม่มีข้อมูล
            removeEmptySets(educationGroup);
        }

        // ประสบการณ์สอน
        const maxTeaching = 5;
        const addTeachingBtn = document.getElementById("add-teaching-btn");
        const teachingGroup = document.getElementById("teaching-group");
        const teachingSet = teachingGroup.querySelector(".teaching-set"); // เรียก .teaching-set ที่มีอยู่ใน DOM

        if (teachingSet) {
            addTeachingBtn.addEventListener("click", function() {
                const currentSets = teachingGroup.querySelectorAll(".teaching-set").length;

                if (currentSets < maxTeaching) {
                    const clone = teachingSet.cloneNode(true);
                    // ล้างค่าชุดใหม่
                    clone.querySelectorAll("input").forEach(input => input.value = "");
                    teachingGroup.appendChild(clone);
                } else {
                    alert("สามารถเพิ่มได้สูงสุด 5 ชุดเท่านั้น");
                }
            });

            // ลบชุดที่ไม่มีข้อมูล
            removeEmptySets(teachingGroup);
        }
    });

</script>

@endsection
