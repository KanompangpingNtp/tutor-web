@extends('dashboard.layouts.master')
@section('title', 'จัดการข้อมูลบุคคล')
@section('content')

<div class="row">
    <div class="">
        <div class="card">
            <div class="card-body">
                <h3 class="fw-bold py-3 mb-4 text-center">จัดการข้อมูลบุคคล</h3><br>

                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    เพิ่มติวเตอร์
                </button> --}}

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มติวเตอร์</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <br>

                <table class="table" id="data_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อ</th>
                            <th>อีเมล</th>
                            <th>ระดับผู้ใช้</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->level == 1)
                                แอดมิน
                                @elseif($user->level == 2)
                                ติวเตอร์ผู้สอน
                                @elseif($user->level == 3)
                                ผู้ใช้งานทั่วไป
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    @if($user->level == 1 || $user->level == 2)
                                    <a href="{{ route('TutorInformationDetailPage', $user->id) }}" class="btn btn-primary btn-sm me-2">
                                        <i class='bx bxs-user-detail'></i>
                                    </a>
                                    @endif

                                    <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                        <i class='bx bx-edit'></i>
                                    </button>

                                    <form action="{{route('deleteTutorInformation',$user->id)}}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบวิชานี้?')">
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

                @foreach ($users as $user)
                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('updateTutorInformation', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $user->id }}">แก้ไขข้อมูลติวเตอร์</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name{{ $user->id }}" class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email{{ $user->id }}" class="form-label">อีเมล</label>
                                        <input type="email" class="form-control" id="email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password{{ $user->id }}" class="form-label">รหัสผ่านใหม่ <span class="text-danger">(ถ้าไม่เปลี่ยนให้เว้นว่าง)</span></label>
                                        <input type="password" class="form-control" id="password{{ $user->id }}" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="level{{ $user->id }}" class="form-label">ระดับผู้ใช้</label>
                                        <select class="form-select" id="level{{ $user->id }}" name="level" required>
                                            <option value="{{ $user->level }}" selected hidden>
                                                {{ $user->level == '1' ? 'แอดมิน' : ($user->level == '2' ? 'ติวเตอร์ผู้สอน' : 'ไม่ทราบระดับ') }}
                                            </option>
                                            <option value="1">แอดมิน</option>
                                            <option value="2">ติวเตอร์ผู้สอน</option>
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
                @endforeach


            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/datatable.js')}}"></script>

@endsection
