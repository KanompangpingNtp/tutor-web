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

        

        .qr-code{
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
    </style>

    <body>
        <div class="bg-home d-flex align-items-start justify-content-center">
            <div class="container py-5">
                <!-- หัวข้อหลัก -->
                <h1 class="text-center fw-bold mb-5">หน้าการชำระ</h1>
                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-start gap-3">
                    <div class="bg-white rounded-4 shadow py-4 px-4">
                        <h3 class="text-center fw-bold   mb-4">สรุปรายละเอียดคำสั่งซื้อ</h3>
                        <!-- กล่องที่ควบคุมความสูง -->
                        <div class="d-flex flex-column justify-content-start align-items-center py-2 px-4"
                            style="max-height: 500px; overflow-y: auto;">
                            <div class="rounded-4 shadow py-2 ps-4 mb-3" style="padding-right: 5rem;">
                                <p>1. วิชาอะไร : ภาษาไทย</p>
                                <p>เรียนกับใคร: ดร.อดิศร ก้อนเงิน</p>
                                <p>วันเรียน: 1/4/2568 - 30/4/2568</p>
                                <p>เวลาเรียน: 09:00 - 10:00</p>
                                <p>ราคาคอร์ส: 1500 บาท</p>
                            </div>
                            <div class="rounded-4 shadow py-2 ps-4 mb-3" style="padding-right: 5rem;">
                                <p>2. วิชาอะไร : คณิตศาสตร์</p>
                                <p>เรียนกับใคร: ผศ.ดร.วรัญญู คงศรี</p>
                                <p>วันเรียน: 2/4/2568 - 29/4/2568</p>
                                <p>เวลาเรียน: 10:30 - 11:30</p>
                                <p>ราคาคอร์ส: 1800 บาท</p>
                            </div>
                            <div class="rounded-4 shadow py-2 ps-4 mb-3" style="padding-right: 5rem;">
                                <p>3. วิชาอะไร : วิทยาศาสตร์</p>
                                <p>เรียนกับใคร: ครูปิยวัฒน์ แซ่ตั้ง</p>
                                <p>วันเรียน: 3/4/2568 - 28/4/2568</p>
                                <p>เวลาเรียน: 13:00 - 14:00</p>
                                <p>ราคาคอร์ส: 1600 บาท</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-4 shadow py-4 px-4">
                        <div class="d-flex flex-column justify-content-start align-items-center">
                            <img src="{{ asset('payment/qr-code 3.png') }}" alt="qr-code" class="img-fluid img-couse mb-3 qr-code" >
                    
                            <!-- กล่องจัดแนวนอน ราคา + ชื่อผู้สอน -->
                            <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                <p class="mb-0 fw-bold text-primary fs-2">1500 บาท</p>
                                <p class="mb-0 fs-3">ดร.อดิศร ก้อนเงิน</p>
                            </div>
                            <a href="#" class="btn-detail w-100">
                                <img src="{{asset('course/icon.png')}}" alt="icon" class="me-2 d-none d-xl-block" style="height: 40px; width: 40px;"> ปริ้นใบเสร็จ
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>

    </body>
@endsection
