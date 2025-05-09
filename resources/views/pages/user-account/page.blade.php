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
                        <th>ชื่อคอร์ส</th>
                        <th>วันที่จอง</th>
                        <th>วันเรียน</th>
                        <th>สถานะ</th>
                        <th>การชำระเงิน</th>
                        <th>หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking as $item)
                    <tr>
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
                        <td>
                            @if($item->status === '3')
                            <span class="badge bg-success">อนุมัติแล้ว</span>
                            @elseif($item->status === '2')
                            <span class="badge bg-warning text-dark">รอดำเนินการ</span>
                            @elseif($item->status === '1')
                            <span class="badge bg-danger">ยังไม่อนุมัติ</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->payment_status === 'pending')
                            <span class="badge bg-success">ชำระแล้ว</span>
                            @elseif($item->payment_status === 'confirmed')
                            <span class="badge bg-danger">ยังไม่ชำระ</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $item->note ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

@endsection
