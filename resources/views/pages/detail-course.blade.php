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

        .carousel-inner {
            height: 300px;
            width: 100%;
        }

        .carousel-item {
            height: 100%;
            width: 100%;
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        .btn-detail {
            background: linear-gradient(to right, #91d5ff, #1186fc);
            box-shadow: 0 3px 8px rgba(255, 255, 255, 0.5);
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
    </style>

    <body>
        <div class="bg-home d-flex align-items-start justify-content-center">
            <div class="container py-5">
                <!-- หัวข้อหลัก -->
                <h1 class="text-center fw-bold mb-5">รายละเอียดคอร์ส</h1>

                <div class="row g-4 justify-content-center justify-content-xl-start align-items-start">
                    <div class="col-lg-7 d-flex flex-column justify-content-start align-items-start p-4 bg-white shadow rounded-4">
                        <h3 class="text-center mb-0">ระยะเวลาคอร์ส</h3>
                        <div class="ms-3 fs-6">
                          18:00 - 20:00 น. ทุกวันเสาร์-อาทิตย์ (รวม 8 สัปดาห์ เริ่มเรียน 1 มิถุนายน - 20 กรกฎาคม 2568)
                        </div>
                      
                        <h3 class="text-center mb-0 mt-3">รายละเอียดคอร์ส</h3>
                        <div class="ms-3 fs-6">
                          คอร์สนี้เหมาะสำหรับผู้ที่สนใจเริ่มต้นเรียนรู้เกี่ยวกับปัญญาประดิษฐ์ (AI) และการประยุกต์ใช้ในโลกจริง 
                          โดยเนื้อหาครอบคลุมตั้งแต่พื้นฐานของ Machine Learning, การเตรียมข้อมูล, การสร้างโมเดลด้วย Python 
                          และการนำโมเดลไปใช้ในแอปพลิเคชันจริง<br><br>
                      
                          🔹 เรียนรู้แนวคิดพื้นฐานของ AI และ Machine Learning<br>
                          🔹 ใช้งาน Python และเครื่องมือยอดนิยม เช่น Pandas, Scikit-learn, TensorFlow<br>
                          🔹 ทำเวิร์กช็อปจริง เช่น การทำนายราคาบ้าน, การจำแนกรูปภาพ และการทำ Chatbot<br>
                          🔹 มีแบบฝึกหัดและโปรเจกต์จริงท้ายคอร์ส เพื่อเสริมความเข้าใจ<br>
                          🔹 เหมาะกับผู้ไม่มีพื้นฐานมาก่อน และมีทีมผู้ช่วยตอบคำถามในกลุ่มเรียน<br><br>
                      
                          ผู้เรียนจะได้รับไฟล์เอกสารประกอบการสอน, โค้ดตัวอย่าง และใบประกาศนียบัตรหลังจบคอร์ส
                        </div>
                      </div>
                      
                    <div class="col-lg-5 d-flex flex-column justify-content-center align-items-center">
                        <div id="carouselExampleFade" class="carousel slide carousel-fade mb-3 shadow"
                            style="border-radius: 20px;">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('home/Bg.png') }}" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('home/ป้ายโปรโมชั่น.png') }}" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('home/Bg.png') }}" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe
                                src="https://www.youtube.com/embed/Mub1tUwqfxI?autoplay=1&mute=1&controls=0&loop=1&playlist=Mub1tUwqfxI"
                                title="YouTube video" allowfullscreen allow="autoplay; encrypted-media" style="border-radius: 20px;">
                            </iframe>
                        </div>
                        <a href="#" class="btn-detail w-100">
                            จอง
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </body>
@endsection
