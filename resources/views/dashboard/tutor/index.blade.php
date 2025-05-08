@extends('dashboard.layouts.master')

@section('title', 'Dashboard')

@section('content')
<!-- Card หรือ widget อื่น ๆ -->
<div class="row">
    <!-- ตัวอย่าง Card -->
    <div class="">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
                Welcome to Sneat Dashboard!

                <br>
                <br>

                {{-- <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch demo modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</div>
@endsection
