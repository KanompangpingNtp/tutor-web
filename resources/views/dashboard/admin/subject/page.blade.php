@extends('dashboard.layouts.master')
@section('title', 'จัดการวิชาที่เปิดสอน')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h3 class="fw-bold py-3 mb-4 text-center">จัดการวิชาที่เปิดสอน</h3><br>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    เพิ่มวิชาที่สอน
                </button>

                <br>
                <br>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('SubjectCreate') }}" method="POST" id="createSubjectForm">
                                @csrf
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มวิชาที่สอน</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="subjectName" class="form-label">ชื่อวิชา</label>
                                        <input type="text" class="form-control" id="subjectName" name="name" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th class="text-center">ชื่อวิชา</th>
                            <th class="text-center">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $subject->id }}">
                                        <i class='bx bx-edit'></i>
                                    </button>

                                    <form action="{{ route('SubjectDelete', $subject->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบวิชานี้?')">
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

                @foreach($subjects as $subject)
                <div class="modal fade" id="editModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $subject->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('SubjectUpdate', $subject->id) }}" method="POST" id="editSubjectForm{{ $subject->id }}">
                                @csrf
                                @method('POST')
                                <!-- ใช้เมธอด POST -->
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editModalLabel{{ $subject->id }}">แก้ไขวิชาที่สอน</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="subjectName" class="form-label">ชื่อวิชา</label>
                                        <input type="text" class="form-control" id="subjectName" name="name" value="{{ $subject->name }}" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary">อัปเดต</button>
                                </div>
                            </form>
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
