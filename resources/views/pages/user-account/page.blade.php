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
        <div class="container py-5 col-md-12">
            <h1 class="mb-4 text-center">ประวัติการจองคอร์ส</h1><br>

            <table class="table table-striped" id="data_table">
                <thead>
                    <tr>
                        <th class="text-center">ชื่อคอร์ส</th>
                        <th class="text-center">วันที่จอง</th>
                        <th class="text-center">วันเรียน</th>
                        <th class="text-center">หมายเหตุ</th>
                        <th class="text-center">จองวันเวลาเรียน</th>
                        <th class="text-center">การชำระเงิน</th>
                        <th class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking as $item)
                    <tr class="text-center">
                        <td>{{ $item->course->course_name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }}</td>
                        <td>
                            @if ($item->teachings)
                            {{ \Carbon\Carbon::parse($item->teachings->course_starttime)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->teachings->course_endtime)->format('H:i') }}
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $item->note ?? '-' }}</td>
                        <td>
                            @if ($item->payment_status === 'pending' && $item->scheduled_datetime === null)
                            {{-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $item->id }}">
                            จอง
                            </button> --}}
                            <a href="{{ route('UsersAccountBooking', $item->id) }}" class="btn btn-sm btn-primary">จอง</a>
                            @else
                            <span class="text-success">จองแล้ว</span>
                            @endif
                        </td>
                        <td>
                            @if($item->payment_status === 'pending')
                            <span class="text-success">ชำระแล้ว</span>
                            @elseif($item->payment_status === 'confirmed')
                            <span class="text-danger">ยังไม่ชำระ</span>
                            @else
                            <span class="text-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === '3')
                            <span class="text-success">อนุมัติแล้ว</span>
                            @elseif($item->status === '2')
                            <span class="text-warning text-dark">รอดำเนินการ</span>
                            @elseif($item->status === '1')
                            <span class="text-danger">ยังไม่อนุมัติ</span>
                            @else
                            <span class="text-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @foreach ($booking as $item)
            <div class="modal fade" id="bookingModal{{ $item->id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('booking.schedule', $item->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">จองเวลาเรียน: {{ $item->course->course_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            </div>
                            <div class="modal-body row">
                                <div class="form-group mb-3 col-md-6">
                                    <label for="booking_date">เลือกวันที่ต้องการเรียน</label>
                                    <input type="date" id="booking_date" class="form-control" name="booking_date" required>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="scheduled_datetime">เวลาที่ต้องการเรียน</label>
                                    <select id="scheduled_datetime" name="scheduled_datetime" class="form-control" required>
                                        <option value="">กรุณาเลือกช่วงเวลาที่ต้องการเรียน</option>
                                        @foreach ($item->course->teachings as $teaching)
                                        @php
                                        $start = \Carbon\Carbon::parse($teaching->course_starttime);
                                        $end = \Carbon\Carbon::parse($teaching->course_endtime);
                                        @endphp
                                        <option value="{{ $teaching->id }}">
                                            {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="note">หมายเหตุ (ถ้ามี)</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">บันทึกการจอง</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        const bookings = @json($bookingMap); // ใช้ตัวแปรใหม่ที่มาจาก controller

        document.addEventListener('DOMContentLoaded', function() {
            const bookingDateInput = document.getElementById('booking_date');
            const timeSelect = document.getElementById('scheduled_datetime');

            bookingDateInput.addEventListener('change', function() {
                const selectedDate = this.value;

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

    <script src="{{asset('js/datatable.js')}}"></script>

</body>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@endsection
