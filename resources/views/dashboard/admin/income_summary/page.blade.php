@extends('dashboard.layouts.master')
@section('title', 'สรุปรายได้ของติวเตอร์')
@section('content')

<style>
    /* เปลี่ยนสีของ option ที่ disabled ให้เป็นสีเทาอ่อน */
    select option:disabled {
        color: #888;
        /* สีเทา */
        background-color: #f0f0f0;
        /* สีพื้นหลังเทาอ่อน */
    }

</style>

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">สรุปรายได้ของติวเตอร์</h4>

                <form method="GET" action="{{ route('IncomeSummaryPage') }}" class="row g-2 mb-3">
                    <div class="col-md-2">
                        <label for="month" class="form-label">เลือกเดือน</label>
                        <select name="month" class="form-select" required>
                            <option value="">-- เลือกเดือน --</option>
                            @foreach($allMonths as $month)
                            @php
                            $isDisabled = !in_array($month, $monthsAvailable);
                            @endphp
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->locale('th')->isoFormat('MMMM') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="year" class="form-label">เลือกปี</label>
                        <select name="year" class="form-select" required>
                            <option value="">-- เลือกปี --</option>
                            @foreach($allYears as $year)
                            @php
                            $isDisabled = !in_array($year, $yearsAvailable);
                            @endphp
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                                {{ $year + 543 }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>

                <br>

                <table class="table" id="data_table">
                    <thead>
                        <tr>
                            <th>ติวเตอร์</th>
                            <th>คอร์ส</th>
                            <th>จำนวนชั่วโมงที่สอน</th>
                            <th>รายได้รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $row)
                        <tr>
                            <td>{{ $row['tutor']->name }}</td>
                            <td>{{ $row['course']->course_name }}</td>
                            <td>{{ number_format($row['total_hours'], 2) }} ชั่วโมง</td>
                            <td>{{ number_format($row['total_income'], 2) }} บาท</td>
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
