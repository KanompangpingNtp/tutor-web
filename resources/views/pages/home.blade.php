@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <style>
        .bg-home {
            background: url('{{ asset('home/Bg.png') }}') no-repeat center center;
            background-size: cover;
            min-height: 85.2vh;
            padding: 2rem 2rem 2rem 2rem;
        }

        .feature-list i {
            color: #1d9bf0;
            margin-right: 0.5rem;
        }

        .btn-search {
            background: linear-gradient(to bottom, #91d5ff, #1186fc);
            color: white;
            font-size: 2rem;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            border: none;
            transition: 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.7);
        }

        .btn-search:hover {
            transform: scale(1.05);
            background: linear-gradient(to right, #1186fc, #005ecb);
        }

        .toppic {
            font-size: 38px;
        }

        .detail {
            font-size: 30px;
        }

        .option {
            font-size: 25px;
        }

        @media (max-width: 1200px) {

            .toppic {
                font-size: 30px;
            }
        }

        @media (max-width: 1200px) {

            .bg-home {
                padding: 2rem 2rem 10rem 2rem;
            }
        }

        @media (max-width: 500px) {
            .toppic {
                font-size: 22px;
            }

            .btn-search {
                font-size: 1.5rem;
                padding: 0.6rem 1.5rem;
            }

            .detail {
                font-size: 20px;
            }

            .option {
                font-size: 18px;
            }

            .bg-home {
                padding: 2rem 2rem 4rem 2rem;
            }
        }

        @keyframes featureAnimate {
            0% {
                transform: translateX(0) scale(1);
                color: #000;
                opacity: 1;
            }

            50% {
                transform: translateX(10px) scale(1.02);
                color: #1186fc;
            }

            100% {
                transform: translateX(0) scale(1);
                color: #000;
            }
        }

        .option li {
            animation-name: featureAnimate;
            animation-duration: 3s;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            opacity: 1;
        }

        /* ไล่ลำดับการแสดง */
        .option li:nth-child(1) {
            animation-delay: 1s;
        }

        .option li:nth-child(2) {
            animation-delay: 2s;
        }

        .option li:nth-child(3) {
            animation-delay: 3s;
        }
    </style>

    <body>
        <div class="bg-home d-flex align-items-center justify-content-center">
            <div class="container ">
                <div class="row align-items-center justify-content-center">
                    <!-- Left content -->
                    <div class="col-lg-6 mb-4 mb-lg-0 ">
                        <h1 class="fw-bold mt-4 toppic">ระบบจองรายคอร์สเรียนพิเศษ<br>เรารวบรวมไว้ครบ จบในที่เดียว</h1>
                        <p class="text-dark fw-semibold detail">สะดวก - รวดเร็ว - ปลอดภัย</p>

                        <ul class="list-unstyled feature-list option">
                            <li><i class="fas fa-check-circle"></i> ดูข้อมูลผู้สอนได้อย่างละเอียด</li>
                            <li><i class="fas fa-check-circle"></i> ดูรายละเอียดคอร์สเรียนชัดเจน</li>
                            <li><i class="fas fa-check-circle"></i> แสกนจ่ายค่าเรียนได้อย่างปลอดภัย</li>
                        </ul>

                        {{-- <button class="btn-search mt-4">เริ่มการค้นหา <img src="{{asset('home/star.png')}}" alt="star" width="45"></button> --}}
                        <a href="{{route('SubjectCategory')}}" class="btn-search mt-4" style="text-decoration: none;">เริ่มการค้นหา <img src="{{asset('home/star.png')}}" alt="star" width="45"></a>
                    </div>

                    <!-- Right image -->
                    <div class="col-lg-6 text-center">
                        <!-- รูปภาพแบนเนอร์ -->
                        <img src="{{ asset('home/ป้ายโปรโมชั่น.png') }}" alt="Banner Placeholder" class="img-fluid"
                            style="max-height: 400px;">
                        <!-- ใส่ path รูปภายหลัง -->
                    </div>
                </div>
            </div>
        </div>

    </body>
@endsection
