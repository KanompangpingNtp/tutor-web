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
        <div class="container py-5 col-md-6">
            <h1 class="mb-4 text-center">รายละเอียดการจองคอร์ส <br> <h2 class="text-center">วิชา {{$courses->course_name}}</h2> </h1> <br><br>
            <form action="{{route('BookingCreate')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <input type="hidden" name="course_id" value="{{ $courses->id }}">

                    <div class="form-group mb-3 col-md-6">
                        <label for="booking_date">เลือกวันที่ต้องการเรียน</label>
                        <input type="date" id="booking_date" class="form-control" name="booking_date" required>
                    </div>

                    <div class="form-group mb-3 col-md-6">
                        <label for="scheduled_datetime">เวลาที่ต้องการเรียน</label>
                        <select id="scheduled_datetime" name="scheduled_datetime" class="form-control" required>
                            <option value="">กรุณาเลือกช่วงเวลาที่ต้องการเรียน</option>
                            @foreach ($courses->teachings as $teaching)
                            @php
                            $start = \Carbon\Carbon::parse($teaching->course_starttime);
                            $end = \Carbon\Carbon::parse($teaching->course_endtime);
                            @endphp

                            <option value="{{ $teaching->id }}" data-hourly-rate="{{ $teaching->hourly_rate }}">
                                {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                            </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="payment_status">ประเภทการชำระเงิน</label>
                    <select id="payment_status" name="payment_status" class="form-control">
                        <option value="pending">ชำระเงินจ่ายก่อนเรียน</option>

                        @if(auth()->check() && auth()->user()->level == 1)
                        <option value="confirmed">ชำระเงินจ่ายหลังเรียน</option>
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <p>บัญชี : 123-4567-89-0 ธนาคารไทยกรุง</p>
                    {{-- <p>ราคาคอร์ส : <span id="hourlyRateDisplay">-</span> บาท</p> --}}
                </div>

                <div class="form-group mb-3">
                    <label for="transfer_slip">แนบใบโอนเงิน</label>
                    <input type="file" id="transfer_slip" name="transfer_slip" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="note">หมายเหตุ (ถ้ามี)</label>
                    <textarea id="note" name="note" class="form-control" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary">จองหลักสูตร</button>
                </div>
            </form>
        </div>
    </div>

    @php
    $bookingMap = [];
    foreach ($bookings as $booking) {
    $date = \Carbon\Carbon::parse($booking->booking_date)->toDateString();
    $bookingMap[$date][] = $booking->scheduled_datetime;
    }
    @endphp

    <script>
        const bookings = @json($bookingMap);

        document.addEventListener('DOMContentLoaded', function() {
            const bookingDateInput = document.getElementById('booking_date');
            const timeSelect = document.getElementById('scheduled_datetime');

            bookingDateInput.addEventListener('change', function() {
                const selectedDate = this.value;

                // Enable และ reset ทุก option ก่อน
                Array.from(timeSelect.options).forEach(option => {
                    option.disabled = false;
                    option.textContent = option.textContent.replace(' (เต็มแล้ว)', '');
                });

                if (bookings[selectedDate]) {
                    bookings[selectedDate].forEach(teachingId => {
                        const optionToDisable = timeSelect.querySelector(`option[value='${teachingId}']`);
                        if (optionToDisable) {
                            optionToDisable.disabled = true;
                            optionToDisable.textContent += ' (เต็มแล้ว)';
                        }
                    });
                }
            });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('scheduled_datetime');
            const rateDisplay = document.getElementById('hourlyRateDisplay');

            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const rate = selectedOption.getAttribute('data-hourly-rate');
                rateDisplay.textContent = rate ? rate : '-';
            });
        });

    </script>

</body>
@endsection
