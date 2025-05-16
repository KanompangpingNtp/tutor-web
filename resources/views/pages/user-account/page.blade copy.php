@extends('layouts.app')
@section('title', 'Subject-category')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

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
        <div class="container py-1">
            <h1 class="mb-4 text-center">ประวัติการจองคอร์ส</h1><br>

            <table class="table table-striped" id="data_table" style="font-size: 16px;">
                <thead>
                    <tr>
                        <th class="text-center">ชื่อคุณครู</th>
                        <th class="text-center">ชื่อคอร์ส</th>
                        <th class="text-center">วันที่จอง</th>
                        <th class="text-center">วันเวลาเรียน</th>
                        <th class="text-center">หมายเหตุ</th>
                        <th class="text-center">การจอง</th>
                        <th class="text-center">การชำระเงิน</th>
                        <th class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking as $item)
                    <tr class="text-center">
                        <td>{{ $item->course->user->name ?? '-' }}</td>
                        <td>{{ $item->course->course_name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }}</td>
                        <td>
                            @if ($item->teachings)
                            {{ $item->teachings->day->day_name ?? '-' }} :
                            {{ \Carbon\Carbon::parse($item->teachings->course_starttime)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->teachings->course_endtime)->format('H:i') }}
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $item->note ?? '-' }}</td>
                        <td>
                            @if (is_null($item->scheduled_datetime) && $item->payment_status === '1')
                            <span class="text-muted">-</span>

                            @elseif (is_null($item->scheduled_datetime) && $item->payment_status === '2')
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $item->id }}">
                                จอง
                            </button>

                            @elseif (!is_null($item->scheduled_datetime) && $item->payment_status === '2')
                            <span class="text-success">จองแล้ว</span>

                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->payment_status === '2')
                            <span class="text-success">ชำระแล้ว</span>
                            @elseif($item->payment_status === '1')
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

                                @php
                                $days = $item->course->teachings->groupBy('day.day_name');
                                @endphp

                                <script>
                                    $('#scheduled_datetime').selectpicker();

                                </script>

                                <div class="form-group mb-3 col-md-12">
                                    <label for="scheduled_datetime">ช่วงเวลาที่ต้องการเรียน</label>
                                    <select id="scheduled_datetime" name="scheduled_datetime[]" class="form-control selectpicker" multiple data-live-search="true" required>
                                        <option value="">-- กรุณาเลือกเวลา --</option>
                                        @foreach ($item->course->teachings as $teaching)
                                        @php
                                        $start = \Carbon\Carbon::parse($teaching->course_starttime)->format('H:i');
                                        $end = \Carbon\Carbon::parse($teaching->course_endtime)->format('H:i');
                                        $dayName = $teaching->day->day_name ?? 'ไม่ทราบวัน';
                                        @endphp
                                        <option value="{{ $teaching->id }}" data-day="{{ $dayName }}">
                                            {{ $dayName }} {{ $start }} - {{ $end }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group mb-3">
                                    <label>รูปแบบการเรียน</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="learning_style" id="long_term" value="1">
                                        <label class="form-check-label" for="long_term">เรียนทุกอาทิตย์</label>
                                    </div>
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

    <script src="{{asset('js/datatable.js')}}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const daySelector = document.getElementById("day_selector");
            const timeSelector = document.getElementById("scheduled_datetime");

            daySelector.addEventListener("change", function() {
                const selectedDay = this.value;

                // ซ่อน option ทั้งหมดก่อน แล้วแสดงเฉพาะที่ตรงวัน
                Array.from(timeSelector.options).forEach(option => {
                    if (!option.value) {
                        option.hidden = false; // "-- กรุณาเลือกเวลา --"
                        option.disabled = false;
                        return;
                    }

                    if (option.getAttribute('data-day') === selectedDay) {
                        option.hidden = false;
                        option.disabled = false;
                    } else {
                        option.hidden = true;
                        option.disabled = true;
                    }
                });

                // รีเซ็ตการเลือกเวลา
                timeSelector.value = "";
            });
        });

    </script>

</body>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@endsection
