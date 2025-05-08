@extends('dashboard.layouts.master')

@section('title', 'จัดการข้อมูลส่วนตัว')

@section('content')
<!-- Card หรือ widget อื่น ๆ -->
<div class="row">
    <!-- ตัวอย่าง Card -->
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4 text-center">จัดการข้อมูลส่วนตัว</h4>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <img src="{{ $users->profile_image ? asset('storage/' . $users->profile_image) : asset('assets/img/avatars/1.png') }}" alt="Profile" class="rounded-circle img-thumbnail mb-3" width="150">
                                <h5 class="mt-2">{{$users->name}}</h5>
                                <p class="text-muted">{{$users->email}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">ประวัติ</h5>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
