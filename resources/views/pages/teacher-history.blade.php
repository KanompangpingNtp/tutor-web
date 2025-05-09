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
                <!-- หัวข้อหลัก -->
                <h1 class="text-center fw-bold mb-5">ประวัติผู้สอน</h1>

                <div class="row g-4 justify-content-center justify-content-xl-start align-items-start">
                    <div class="col-lg-4 d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('teacher-history/รูปตัวอย่างคน.png') }}" alt="img-teacher"
                            class="img-fluid img-teacher mb-3">
                        {{-- <div class="row justify-content-evenly align-items-center">
                            <a href="#" class=" col-5">
                                <img src="{{ asset('teacher-history/image.png') }}" alt="course" class="img-fluid img-couse  mb-3">
                            </a>
                            <a href="#" class=" col-5">
                                <img src="{{ asset('teacher-history/image (1).png') }}" alt="course" class="img-fluid img-couse  mb-3">
                            </a>
                        </div> --}}
                    </div>
                    <div class="col-lg-8 d-flex flex-column justify-content-start align-items-start p-4 bg-white shadow rounded-4">
                        <h3 class="text-center mb-0">ชื่อผู้สอน</h3>
                        <div class="ms-3 fs-5">ดร. สมชาย อินทรักษ์</div>

                        <h3 class="text-center mb-0 mt-3">ประวัติผู้สอน</h3>
                        <div class="ms-3 fs-5">
                            ดร. สมชาย อินทรักษ์ เป็นผู้เชี่ยวชาญด้านเทคโนโลยีสารสนเทศ มีประสบการณ์การสอนในมหาวิทยาลัยชั้นนำมากกว่า 15 ปี และมีผลงานวิจัยตีพิมพ์ในวารสารระดับนานาชาติหลายฉบับ
                        </div>

                        <h3 class="text-center mb-0 mt-3">ประสบการณ์ทำงาน</h3>
                        <div class="ms-3 fs-5">
                            - อาจารย์ประจำภาควิชาวิทยาการคอมพิวเตอร์ มหาวิทยาลัยเชียงใหม่ (2553 - ปัจจุบัน)<br>
                            - ที่ปรึกษาด้านระบบฐานข้อมูลให้กับบริษัทซอฟต์แวร์เอกชน (2558 - 2564)
                        </div>

                        <h3 class="text-center mb-0 mt-3">การศึกษา</h3>
                        <div class="ms-3 fs-5">
                            - ปริญญาเอก: ด้านวิศวกรรมคอมพิวเตอร์ มหาวิทยาลัยโตเกียว ประเทศญี่ปุ่น<br>
                            - ปริญญาโท: เทคโนโลยีสารสนเทศ จุฬาลงกรณ์มหาวิทยาลัย<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                            - ปริญญาตรี: วิทยาการคอมพิวเตอร์ มหาวิทยาลัยเกษตรศาสตร์<br>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </body>
@endsection
