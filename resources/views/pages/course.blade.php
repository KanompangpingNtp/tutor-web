@extends('layouts.app')
@section('title', 'Subject-category')
@section('content')
<style>
    .bg-home {
        background: url('{{ asset('home/Bg.png') }}') no-repeat center center;
        background-size: cover;
        min-height: 85.2vh;
        padding: 2rem 2rem 2rem 2rem;
    }

    .course-card {
        border-radius: 16px;
        padding: 10px 15px;
        border: 0px solid #0000;
        overflow: hidden;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
    }

    .course-card img {
        border-radius: 20px;
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .btn-detail {
        background: linear-gradient(to right, #91d5ff, #1186fc);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 20px;
        border-radius: 20px;
        text-decoration: none;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background: linear-gradient(to left, #91d5ff, #1186fc);
        box-shadow: 0 6px 16px rgba(17, 134, 252, 0.45);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>

<body>
    <div class="bg-home d-flex align-items-start justify-content-center">
        <div class="container py-5">
            <!-- หัวข้อหลัก -->
            <h1 class="text-center fw-bold mb-5">เลือกคอร์สเรียน ในหมวด {{$subject->name}}</h1>

            <!-- ตารางหมวดหมู่ -->
            <div class="row g-4 justify-content-center justify-content-xl-start">
                @foreach ( $courses as $course )
                @php
                $cover = $course->files->where('status', 1)->first();
                @endphp

                <div class="col-md-6 col-lg-4  mb-4">
                    <div class="card course-card">
                        @if($cover)
                        <img src="{{ asset('storage/' . $cover->file_path) }}" class="card-img-top rounded-top" alt="รูปปกคอร์ส">
                        @else
                        <img src="{{ asset('images/default-course.png') }}" class="card-img-top rounded-top" alt="ไม่มีรูป">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-bold line-clamp-2">{{$course->course_name}}</h5>

                            <p class="card-text text-muted fs-6 line-clamp-2">
                                {{ strip_tags($course->course_details) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold" style="font-size: 13px;">
                                    {{$course->price}} บาท / {{ $course->course_duration_hour }} ชั่วโมง
                                </span>
                                {{-- <a href="{{route('TeacherHistoryPage',$course->id)}}" class="text-primary fw-semibold" style="font-size: 13px; text-decoration: none;">
                                    <span style="color: black;">ผู้สอน </span>{{$course->user->name}}
                                </a> --}}
                            </div>

                            <a href="{{route('CourseDetail',$course->id)}}" class="btn-detail w-100">
                                <img src="{{asset('course/icon.png')}}" alt="icon" class="me-2 d-none d-xl-block" style="height: 40px; width: 40px;"> รายละเอียดคอร์สเรียน
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</body>
@endsection
