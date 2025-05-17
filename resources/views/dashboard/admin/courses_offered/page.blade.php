@extends('dashboard.layouts.master')
@section('title', 'จัดการคอร์สที่เปิดสอน')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">จัดการคอร์สที่เปิดสอน</h4>

                <a href="{{route('CoursesOfferedCreatePage')}}" class="btn btn-primary">เพิ่มคอร์ส</a> <br><br>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th>ชื่อคอร์ส</th>
                            <th>วิชา</th>
                            <th>ติวเตอร์</th>
                            <th>ระยะเวลา (ชม.) การสอน</th>
                            <th>รายละเอียด</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->course_name }}</td>
                            <td>{{ $course->subject->name ?? '-' }}</td>
                            <td>{{ $course->user->name ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#teachingModal{{ $course->id }}">
                                    <i class='bx bx-time-five'></i>
                                </button>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#courseDetailsModal{{ $course->id }}">
                                    {{ Str::limit(strip_tags($course->course_details), 30) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#filesModal{{ $course->id }}">
                                        <i class='bx bxs-file'></i>
                                    </button>

                                    {{-- <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $course->id }}">
                                    <i class='bx bx-edit'></i>
                                    </button> --}}
                                    <a href="{{route('CoursesOfferedUpdatePage',$course->id)}}" class="btn btn-warning btn-sm me-1"> <i class='bx bx-edit'></i></a>

                                    <form action="{{ route('deleteCourse', $course->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบหรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-1">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach($courses as $course)
                <div class="modal fade" id="filesModal{{ $course->id }}" tabindex="-1" aria-labelledby="filesModalLabel{{ $course->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filesModalLabel{{ $course->id }}">ไฟล์คอร์ส: {{ $course->course_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            </div>
                            <div class="modal-body">
                                @foreach($course->files as $file)
                                @if($file->status == 1)
                                <p><strong class="text-primary">[ไฟล์ปก]</strong></p>
                                @endif

                                @if(Str::startsWith($file->file_type, 'mp4') || Str::startsWith($file->file_type, 'webm'))
                                <video src="{{ asset('storage/' . $file->file_path) }}" width="100%" controls class="mb-3"></video>
                                @else
                                <!-- ปรับขนาดภาพ -->
                                <img src="{{ asset('storage/' . $file->file_path) }}" class="img-fluid mb-3" style="max-width: 80%; height: auto;" />
                                @endif
                                @endforeach
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="courseDetailsModal{{ $course->id }}" tabindex="-1" aria-labelledby="courseDetailsModalLabel{{ $course->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="courseDetailsModalLabel{{ $course->id }}">ข้อมูลคอร์สเพิ่มเติม</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h4>ระยะเวลาคอร์ส</h4>
                                <p>{{$course->course_duration_hour}}</p><br>
                                <h4>รายละเอียดคอร์ส</h4>
                                <p>{!! $course->course_details !!}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="teachingModal{{ $course->id }}" tabindex="-1" aria-labelledby="teachingModalLabel{{ $course->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="teachingModalLabel{{ $course->id }}">
                                    รายละเอียดการสอนของคอร์ส: {{ $course->course_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            </div>
                            <div class="modal-body">
                                @if ($course->teachings->isEmpty())
                                <p>ไม่มีข้อมูลการสอน</p>
                                @else
                                <table class="table table-bordered" id="data_table">
                                    <thead>
                                        <tr>
                                            <th>วันสอน</th>
                                            <th>เวลาเริ่ม</th>
                                            <th>เวลาสิ้นสุด</th>
                                            <th>รวมระยะเวลา (ชม.)</th>
                                            <th>ค่าจ้าง/ชม. (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->teachings as $teaching)
                                        <tr>
                                            <td>{{ $teaching->day->day_name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($teaching->course_starttime)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($teaching->course_endtime)->format('H:i') }}</td>
                                            <td>
                                                @php
                                                $start = \Carbon\Carbon::parse($teaching->course_starttime);
                                                $end = \Carbon\Carbon::parse($teaching->course_endtime);
                                                $totalHours = $start->diffInHours($end);
                                                @endphp
                                                {{ $totalHours }} ชม.
                                            </td>
                                            <td>{{ number_format($teaching->hourly_rate, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                                {{-- แสดงจำนวนชั่วโมงที่เปิดสอน --}}
                                @if ($course->amountTimes->isNotEmpty())
                                <h6 class="mt-4">จำนวนชั่วโมงที่เปิดสอน</h6>
                                <ul>
                                    @foreach($course->amountTimes as $amountTime)
                                    <li>{{ $amountTime->amount_time_hour }} ชั่วโมง</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/datatable.js')}}"></script>

@endsection
