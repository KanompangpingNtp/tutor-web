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
                            <th class="text-center">วันที่สอน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachingLogs as $log)
                        <tr>
                            <td>{{ $log->course->course_name ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($log->log_date_time)->format('Y-m-d H:i') }}</td>
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
