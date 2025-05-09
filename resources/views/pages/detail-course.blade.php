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
                    <div class="ms-3 fs-6 mt-2">
                        {{$courses->course_duration_hour}} ชั่วโมง
                    </div>

                    <h3 class="text-center mb-0 mt-3">รายละเอียดคอร์ส</h3>
                    <div class="ms-3 fs-6 mt-2">
                        {!!$courses->course_details!!}
                    </div>
                </div>

                <div class="col-lg-5 d-flex flex-column justify-content-center align-items-center">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade mb-3 shadow" style="border-radius: 20px;">
                        <div class="carousel-inner">
                            @php
                            $images = $courses->files->filter(function($file) {
                            return in_array(strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']);
                            });
                            @endphp

                            @if ($images->isNotEmpty())
                            @foreach ($images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->file_path) }}" class="d-block w-100" alt="..." style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#imageGalleryModal" data-index="{{ $index }}">
                            </div>
                            @endforeach
                            @else
                            <div class="carousel-item active">
                                <img src="{{ asset('home/Bg.png') }}" class="d-block w-100" alt="...">
                            </div>
                            @endif
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @php
                    $videos = $courses->files->filter(function($file) {
                    return in_array(strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION)), ['mp4', 'webm']);
                    });
                    @endphp

                    <div class="ratio ratio-16x9 mb-3">
                        @if ($videos->isNotEmpty())
                        <video controls autoplay muted loop style="border-radius: 20px;" class="w-100">
                            @foreach ($videos as $video)
                            <source src="{{ asset('storage/' . $video->file_path) }}" type="video/{{ pathinfo($video->file_path, PATHINFO_EXTENSION) }}">
                            @endforeach
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <video controls style="border-radius: 20px;" class="w-100">
                            <source src="">
                            Your browser does not support the video tag.
                        </video>
                        @endif
                    </div>

                    @auth
                    <a href="{{ route('BookingPage', $courses->id) }}" class="btn-detail w-100">
                        จอง
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div id="modalCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="height: 650px;">
                            @foreach ($images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->file_path) }}" class="d-block object-fit-cover" style="object-fit: cover;" alt="...">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalCarousel = document.querySelector('#modalCarousel');
            const carouselInstance = new bootstrap.Carousel(modalCarousel);

            document.querySelectorAll('[data-bs-target="#imageGalleryModal"]').forEach(function(img, index) {
                img.addEventListener('click', function() {
                    carouselInstance.to(parseInt(this.getAttribute('data-index')));
                });
            });
        });

    </script>



</body>
@endsection
