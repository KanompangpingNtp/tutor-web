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
            <h1 class="mb-4 text-center">ตารางเรียน คอร์สเรียนวิชา {{ $courseBooking->course->course_name }}</h1><br>

            <a href="{{route('SubjectCategory')}}" class="btn btn-primary">กลับหน้าหลัก</a>

            <br>
            <br>

            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ผู้สอน {{$courseBooking->course->user->name}}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_table" style="font-size: 16px;">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">วันที่เรียน</th>
                                    <th class="text-center">เวลา</th>
                                    {{-- <th class="text-center">ชื่อวิชา</th>
                                    <th class="text-center">ผู้สอน</th> --}}
                                    <th class="text-center">จำนวนชั่วโมง</th>
                                    <th class="text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookingLogs as $log)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($log->teaching_schedule_day)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $log->scheduled_datetime }}</td>
                                    <td class="text-center">
                                        @php
                                        $timeRange = explode(' - ', $log->scheduled_datetime);

                                        if (count($timeRange) === 2) {
                                        $startTime = \Carbon\Carbon::createFromFormat('H:i', trim($timeRange[0]));
                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', trim($timeRange[1]));
                                        $duration = $startTime->diffInHours($endTime);
                                        } else {
                                        $duration = '-';
                                        }
                                        @endphp

                                        {{ is_numeric($duration) ? $duration . ' ชั่วโมง' : '-' }}
                                    </td>
                                    {{-- <td class="text-center">{{ $log->course->course_name ?? '-' }}</td>
                                    <td class="text-center">{{ $log->course->user->name ?? '-' }}</td> --}}
                                    <td class="text-center">
                                        @if($log->status == 1)
                                        <span class="badge bg-warning">รอเรียน</span>
                                        @elseif($log->status == 2)
                                        <span class="badge bg-success">เรียนแล้ว</span>
                                        @else
                                        <span class="badge bg-secondary">อื่นๆ</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{asset('js/datatable.js')}}"></script>



</body>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@endsection
