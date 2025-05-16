@extends('dashboard.layouts.master')

@section('title', 'ประวัติการสอน')

@section('content')
<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">ประวัติการสอน</h4>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ชื่อคอร์ส</th>
                            <th class="text-center">ชื่อผู้เรียน</th> <!-- เพิ่มหัวตาราง -->
                            <th class="text-center">วันที่สอน</th>
                            <th class="text-center">จำนวนชั่วโมงที่สอน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachingLogs as $log)
                        <tr>
                            <td>{{ $log->course->course_name ?? '-' }}</td>
                            <td class="text-center">
                                {{ $log->booking && $log->booking->user ? $log->booking->user->name ?? '-' : '-' }}
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($log->teaching_date)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ $log->taught_hours ?? '-' }} ชั่วโมง</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/datatable.js')}}"></script>

@endsection
