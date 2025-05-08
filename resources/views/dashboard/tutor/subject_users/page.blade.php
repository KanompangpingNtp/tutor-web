@extends('dashboard.layouts.master')
@section('title', 'จัดการครูประจำวิชา')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">จัดการครูประจำวิชา</h4><br>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subjectUsersModal">
                    กำหนดวิชาที่สอน
                </button>

                <div class="modal fade" id="subjectUsersModal" tabindex="-1" aria-labelledby="subjectUsersModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{route('SubjectTutorCreate')}}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="subjectUsersModalLabel">กำหนดวิชาที่สอนให้ผู้ใช้</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">เลือกผู้ใช้</label>
                                        <select id="user_id_display" class="form-select" disabled>
                                            <option selected>
                                                {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                            </option>
                                        </select>
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    </div>


                                    <div class="mb-3">
                                        <label for="subject_id" class="form-label">เลือกวิชาที่สอน</label>
                                        <select name="subject_id" id="subject_id" class="form-select" required>
                                            <option value="">-- เลือกวิชา --</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <br>
                <br>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th class="text-center">ชื่อผู้ใช้</th>
                            <th class="text-center">อีเมล</th>
                            <th class="text-center">ชื่อวิชา</th>
                            <th class="text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjectUsers as $subjectUser)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $subjectUser->user->name }}</td>
                                <td>{{ $subjectUser->user->email }}</td>
                                <td>{{ $subjectUser->subject->name }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $subjectUser->id }}">
                                            <i class='bx bx-edit'></i>
                                        </button>

                                        <form action="{{ route('SubjectTutorDelete', ['userId' => $subjectUser->user_id, 'subjectId' => $subjectUser->subject_id]) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบหรือไม่?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach($subjectUsers as $subjectUser)
                <div class="modal fade" id="editModal{{ $subjectUser->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $subjectUser->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $subjectUser->id }}">แก้ไขข้อมูลวิชาที่สอน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('SubjectTutorUpdate', $subjectUser->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">เลือกผู้ใช้</label>
                                        <select id="user_id_display" class="form-select" disabled>
                                            <option selected>
                                                {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                            </option>
                                        </select>
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="subject_id" class="form-label">เลือกวิชา</label>
                                        <select class="form-select" name="subject_id" required>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject->id == $subjectUser->subject_id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </div>
                                </form>
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
