@extends('layouts.app')
@section('title', 'Subject-category')
@section('content')
<style>
    .bg-home {
        background: url('{{ asset('home/Bg.png') }}') no-repeat center center;
        background-size: cover;
        min-height: 85.2vh;
        padding: 2rem 2rem 5rem 2rem;
    }

    .img-teacher {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        width: 100%;
        height: 350px;
        object-fit: cover;
    }

    .img-couse {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        width: 250px;

        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .img-couse:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.5);
    }

</style>

<body>
    <div class="bg-home d-flex align-items-start justify-content-center">
        <div class="container py-5">
            <h1 class="text-center fw-bold mb-5">ประวัติผู้สอน</h1>

            <div class="row g-4 justify-content-center justify-content-xl-start align-items-start">
                <div class="col-lg-4">
                    @if ($teacherResume && $teacherResume->user)
                    <div class="card bg-white shadow rounded-4 text-center">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="{{ $teacherResume->user->profile_image ? asset('storage/' . $teacherResume->user->profile_image) : asset('teacher-history/รูปตัวอย่างคน.png') }}" alt="img-teacher" class="img-fluid img-teacher mb-3" style="max-height: 250px; width: 250px; object-fit: cover;">
                            <div class="text-start w-100 px-3">
                                <p><strong>ชื่อผู้สอน:</strong> {{ $teacherResume->user->name ?? '-' }}</p>
                                {{-- <p><strong>อีเมล:</strong> {{ $teacherResume->user->email ?? '-' }}</p>
                                <p><strong>เบอร์ติดต่อ:</strong> {{ $teacherResume->user->phone ?? '-' }}</p> --}}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">ไม่พบข้อมูลประวัติผู้สอน</div>
                    @endif
                </div>

                <div class="col-lg-8 d-flex flex-column justify-content-start align-items-start p-4 bg-white shadow rounded-4">
                    <h4 class="text-center mb-0 mt-3">รางวัลที่ได้รับ</h4>
                    <div class="ms-3 fs-5">
                        @if($teacherResume && $teacherResume->awards)
                        @foreach(json_decode($teacherResume->awards) as $award)
                        • {{ $award }}<br>
                        @endforeach
                        @else
                        -
                        @endif
                    </div>

                    <h4 class="text-center mb-0 mt-3">วุฒิบัตร / ใบรับรอง</h4>
                    <div class="ms-3 fs-5">
                        @if($teacherResume && $teacherResume->certificates)
                        @foreach(json_decode($teacherResume->certificates) as $certificate)
                        • {{ $certificate }}<br>
                        @endforeach
                        @else
                        -
                        @endif
                    </div>

                    <h4 class="text-center mb-0 mt-3">การศึกษา</h4>
                    <div class="ms-3 fs-5">
                        @if($teacherResume && $teacherResume->educations)
                        @foreach(json_decode($teacherResume->educations) as $education)
                        • {{ $education[0] ?? '' }} | {{ $education[1] ?? '' }} | {{ $education[2] ?? '' }}<br>
                        @endforeach
                        @else
                        -
                        @endif
                    </div>

                    <h4 class="text-center mb-0 mt-3">ประสบการณ์สอน</h4>
                    <div class="ms-3 fs-5">
                        @if($teacherResume && $teacherResume->teachings)
                        @foreach(json_decode($teacherResume->teachings) as $teaching)
                        • {{ $teaching[0] ?? '' }} | {{ $teaching[1] ?? '' }} | {{ $teaching[2] ?? '' }}<br>
                        @endforeach
                        @else
                        -
                        @endif
                    </div>

                    <h4 class="text-center mb-0 mt-3">ความสำเร็จในการสอน</h4>
                    <div class="ms-3 fs-5">
                        {!! $teacherResume->teaching_success ?? '-' !!}
                    </div>
                </div>


            </div>
        </div>
    </div>

</body>
@endsection
