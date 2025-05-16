@extends('dashboard.layouts.master')
@section('title', 'ประวัติการจองคอร์ส')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">ประวัติการจองคอร์ส</h4>

                <table class="table table-striped" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ชื่อผู้จอง</th>
                            <th class="text-center">ชื่อคอร์ส</th>
                            <th class="text-center">วันที่จอง</th>
                            <th class="text-center">วันเรียน</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">การชำระเงิน</th>
                            <th class="text-center">หมายเหตุ</th>
                            <th class="text-center">acction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking as $item)
                        <tr>
                            <td class="text-center">{{ $item->user->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->course->course_name ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if ($item->teachings)
                                {{ \Carbon\Carbon::parse($item->teachings->course_starttime)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($item->teachings->course_endtime)->format('H:i') }}
                                @else
                                <span class="text-center">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status === '2')
                                <span class="text-success">อนุมัติแล้ว</span>
                                @elseif($item->status === '1')
                                <span class="text-warning">รอดำเนินการ</span>
                                @else
                                <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentStatusModal{{ $item->id }}">
                                    ชำระแล้ว
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="note-preview" data-note="{{ $item->note ?? '-' }}">
                                    {{ Str::limit($item->note ?? '-', 20) }}
                                </span>
                            </td>
                            <td class="d-flex justify-content-center">
                                @if($item->payment_status === '1')
                                <form action="{{ route('BookingHistoryUpdatePayment', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm me-1">ยืนยันการชำระเงิน</button>
                                </form>
                                @endif

                                @if($item->status === '1')
                                <form action="{{ route('BookingHistoryUpdateStatus', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm me-1">ยืนยัน</button>
                                </form>
                                @endif

                                <form action="{{ route('AdminBookingHistoryDelete', $item->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบหรือไม่?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach ($booking as $item)
                <div class="modal fade" id="slipModal{{ $item->id }}" tabindex="-1" aria-labelledby="slipModalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="slipModalLabel{{ $item->id }}">หลักฐานการโอนเงิน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if ($item->transfer_slip)
                                <img src="{{ asset('storage/' . $item->transfer_slip) }}" class="img-fluid rounded" alt="ใบโอนเงิน">
                                @else
                                <p class="text-muted">ไม่มีใบโอนเงินที่แนบไว้</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                 @endforeach

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


                @foreach ($booking as $item)
                <div class="modal fade" id="paymentStatusModal{{ $item->id }}" tabindex="-1" aria-labelledby="paymentStatusModalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentStatusModalLabel{{ $item->id }}">รายละเอียดการชำระเงิน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($item->transfer_slip)
                                <img src="{{ asset('storage/' . $item->transfer_slip) }}" alt="หลักฐานการโอน" class="img-fluid rounded" style="max-height: 400px;">
                                @else
                                <p class="text-center">ไม่มีไฟล์แนบ</p>
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
