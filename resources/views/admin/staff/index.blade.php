@extends('layouts.admin')

@section('title')
    Staffs
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css')}}">

    <style>
        #staffTable_wrapper .row .col-sm-12{
            text-align: left;
        }
        .spinner-div{
            width: 100%;
            text-align: center;
            margin-top: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Staffs!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Staffs</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-end">
                     <button class="btn btn-primary btn-sm" id="createStaffBtn">+ Staff</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="spinner-div spinner-div-staff">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="staff-container">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div class="modal fade" id="entityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="entityModalTitle">Create Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body create-form">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')  
    <script src="{{ asset('admin/assets//extra-libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/assets//extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('admin//dist/js/pages/datatable/datatable-basic.init.js')}}"></script>

    <script>
        $(document).ready(function () {
            // Load Departments on Page Load
            loadStaffs();

            function loadStaffs() {
                $('.spinner-div-staff').show();
                $.ajax({
                    url: "{{ route('staffs.list-partial') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        setTimeout(() => {
                            $('.spinner-div-staff').hide();
                            $('#staff-container').html(data);                            
                        }, 300);
                    }
                });
            }

            $(document).on('click', '#createStaffBtn', function () {
                $.ajax({
                    url: "{{ route('staffs.create-partial') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        setTimeout(() => {
                            $('.spinner-div-staff').hide();
                            $('.create-form').html(data);                            
                        }, 300);
                    }
                });
                $('#entityModal').modal('show');
            });

            // Edit Department
            $(document).on('click', '.editStaffBtn', function () {
                let id = $(this).data('id');
                console.log('object')
            });

            // Edit Position
            $(document).on('click', '.deleteStaffBtn', function () {
                Swal.fire({
                    title: "Delete Staff ?",
                    text: `This is irreversible.`,
                    icon: "info",
                    buttons: ["Cancel", "Delete"],
                    dangerMode: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        toastr.success('Staff has been deleted.')
                    }
                });
            });
        });
    </script>
@endsection