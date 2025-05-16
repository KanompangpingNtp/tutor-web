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

                <form method="GET" action="{{ route('IncomeSummaryPage') }}" class="row mb-4">
                    <div class="col-md-2">
                        <label for="month">เดือน</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">-- เลือกเดือน --</option>
                            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ (request('month', $month) == $m) ? 'selected' : '' }} {{ !in_array($m, $availableMonths) ? 'disabled' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('th')->isoFormat('MMMM') }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="year">ปี</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">-- เลือกปี --</option>
                            @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ (request('year', $year) == $y) ? 'selected' : '' }} {{ !in_array($y, $availableYears) ? 'disabled' : '' }}>
                                {{ $y + 543 }}
                            </option>
                            @endfor
                        </select>
                    </div>


                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt-2'></i></button>
                    </div>
                </form>


                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ชื่อ</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">จำนวนชั่วโมงที่สอน</th>
                            <th class="text-center">รายได้รวม (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tutors as $tutor)
                        <tr>
                            <td class="text-center">{{ $tutor->name }}</td>
                            <td class="text-center">{{ $tutor->email }}</td>
                            <td class="text-center">{{ $tutor->total_teaching_hours }} ชั่วโมง</td>
                            <td class="text-center">{{ $tutor->total_income }}</td>
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
