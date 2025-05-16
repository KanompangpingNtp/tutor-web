@extends('dashboard.layouts.master')
@section('title', 'รายละเอียดการสอน')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">รายละเอียดการสอนในวัน </h4>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ชื่อคอร์ส</th>
                            <th class="text-center">วันที่สอน</th>
                            <th class="text-center">เวลา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ $bookings->course->course_name ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($bookings->teaching_schedule_day)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $bookings->scheduled_datetime ?? '-' }}</td>
                            <td class="text-center">
                                @if($bookings->status == 2)
                                <span class="text-success">สอนแล้ว</span>
                                @elseif($bookings->status == 1)
                                <span class="text-warning">ยังไม่ได้สอน</span>
                                @else
                                <span class="text-muted">ไม่ทราบสถานะ</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{route('TeachingScheduleUpdateStatus',$bookings->id)}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm" {{ $bookings->status == 2 ? 'disabled' : '' }}>
                                        ยืนยันการสอน
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>

<script src="{{asset('js/datatable.js')}}"></script>

@endsection
