@extends('dashboard.layouts.master')
@section('title', 'รายละเอียดการสอน')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">รายละเอียดการสอนในวัน {{$mainBooking->booking_date}}</h4>

                <table class="table table-striped" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ชื่อคอร์ส</th>
                            <th class="text-center">วันที่จอง</th>
                            <th class="text-center">วันเรียน</th>
                            <th class="text-center">หมายเหตุ</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">acction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                        <tr>
                            <td class="text-center">{{ $booking->course->course_name ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if ($booking->teachings)
                                {{ \Carbon\Carbon::parse($booking->teachings->course_starttime)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($booking->teachings->course_endtime)->format('H:i') }}
                                @else
                                <span class="text-center">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="note-preview" data-note="{{ $booking->note ?? '-' }}">
                                    {{ Str::limit($booking->note ?? '-', 20) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span>
                                    @if($booking->tutor_status === null)
                                    <span class="text-danger">ยังไม่ได้สอน</span>
                                    @elseif($booking->tutor_status == 1)
                                     <span class="text-success">สอนแล้ว</span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    @if($booking->tutor_status != 1)
                                    <!-- เช็คว่า tutor_status ไม่เป็น 1 -->
                                    <form action="{{ route('TeachingScheduleUpdateStatus', $booking->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการอัปเดตสถานะการสอนนี้?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class='bx bx-check'></i> อัปเดตสถานะ
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

                <!-- Modal Structure -->
                <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="noteModalLabel">รายละเอียดเพิ่มเติม</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p id="fullNoteText">ข้อมูลทั้งหมดจะปรากฏที่นี่...</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>

<script>
    // เมื่อคลิกที่ข้อความ note
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('note-preview')) {
            // ดึงข้อมูลทั้งหมดจาก data-note
            const fullNote = e.target.getAttribute('data-note');

            // ตั้งค่าข้อความใน modal
            document.getElementById('fullNoteText').textContent = fullNote;

            // สร้าง instance ของ modal และเปิดมัน
            var myModal = new bootstrap.Modal(document.getElementById('noteModal'));
            myModal.show();
        }
    });

</script>

<script src="{{asset('js/datatable.js')}}"></script>

@endsection
