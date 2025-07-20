@extends('layouts.admin')

@section('title')
    Departments & Positions
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css')}}">
    <style>
        .nav-pills{
            background: #d1d3f0;
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
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Departments & Positions!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Departments & Positions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                            <li class="nav-item">
                                <a href="#home1" data-bs-toggle="tab" aria-expanded="true" id="departments-tab"
                                    class="nav-link rounded-0 active">
                                    <i class="mdi mdi-home-variant d-lg-none d-block me-1"></i>
                                    <span class="d-none d-lg-block">Departments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile1" data-bs-toggle="tab" aria-expanded="false"  id="positions-tab"
                                    class="nav-link rounded-0">
                                    <i class="mdi mdi-account-circle d-lg-none d-block me-1"></i>
                                    <span class="d-none d-lg-block">Positions</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="home1">
                                <div class="spinner-div spinner-div-department">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div id="departments-container" class="p-2 text-center">Loading Departments...</div>
                            </div>
                            <div class="tab-pane" id="profile1">
                                <div class="spinner-div spinner-div-position">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div id="positions-container" class="p-2 text-center">Loading Positions...</div>
                            </div>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div class="modal fade" id="entityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="entityForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="entityModalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="entityModalBody"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveEntityBtn">Save</button>
                    </div>
                </form>
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
            loadDepartments();

            function loadDepartments() {
                $('.spinner-div-department').show();
                $.ajax({
                    url: "{{ route('departments.partial') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        setTimeout(() => {
                            $('.spinner-div-department').hide();
                            $('#departments-container').html(data);                            
                        }, 300);
                    }
                });
            }
            
            function loadPositions() {
                $('.spinner-div-position').show();
                $.ajax({
                    url: "{{ route('positions.partial') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        setTimeout(() => {
                            $('.spinner-div-position').hide();
                            $('#positions-container').html(data);                            
                        }, 300);
                    }
                });
            }

            // Load Positions on Tab Click
            $('#positions-tab').on('click', function () {
                if (!$('#positions-container').hasClass('loaded')) {
                    $('.spinner-div-position').show();
                    console.log('object')
                    $.ajax({
                        url: "{{ route('positions.partial') }}",
                        type: 'GET',
                        success: function (data) {
                            setTimeout(() => {
                                $('.spinner-div-position').hide();
                                $('#positions-container').html(data).addClass('loaded');                     
                            }, 300);
                        }
                    });
                }
            });

            // Open Modal for Create Department
            $(document).on('click', '#createDepartmentBtn', function () {
                $('#entityModalTitle').text('Create Department');
                $('#entityModalBody').html(`
                    <div class="form-group">
                        @csrf
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                        <input type="hidden" name="type" value="department">
                    </div>
                `);
                $('#entityModal').modal('show');
            });

            // Open Modal for Create Position
            $(document).on('click', '#createPositionBtn', function () {
                $('#entityModalTitle').text('Create Position');
                $('#entityModalBody').html(`
                    <div class="form-group">
                        @csrf
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                        <input type="hidden" name="type" value="position">
                    </div>
                `);
                $('#entityModal').modal('show');
            });

            // Edit Department
            $(document).on('click', '.editDepartmentBtn', function () {
                let id = $(this).data('id');
                $.get("{{ url('departments') }}/" + id + "/edit", function (data) {
                    $('#entityModalTitle').text('Edit Department');
                    $('#entityModalBody').html(`
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="${data.title}" required>
                            <input type="hidden" name="id" value="${id}">
                            <input type="hidden" name="type" value="department">
                        </div>
                    `);
                    $('#entityModal').modal('show');
                });
            });

            // Edit Position
            $(document).on('click', '.editPositionBtn', function () {
                console.log('s')
                let id = $(this).data('id');
                $.get("{{ url('positions') }}/" + id + "/edit", function (data) {
                    $('#entityModalTitle').text('Edit Position');
                    $('#entityModalBody').html(`
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="${data.title}" required>
                            <input type="hidden" name="id" value="${id}">
                            <input type="hidden" name="type" value="position">
                        </div>
                    `);
                    $('#entityModal').modal('show');
                });
            });

            // Save Department or Position (Create/Edit)
            $('#entityForm').submit(function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('entity.save') }}", // Single route for both
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        $('#entityModal').modal('hide');
                        if (response.type === 'department') {
                            loadDepartments();
                            toastr.success('Department updated');
                        } else {
                            loadPositions();
                            $('#positions-tab').trigger('click'); // reload positions
                            toastr.success('Position updated');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(`Error : ${error}`);  // Show error message with Toastr
                        console.error(`Error :`, error);
                    }
                });
            });

            // Delete Department
            $(document).on('click', '.deleteDepartmentBtn', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: "Delete Department ?",
                    text: `This is irreversible.`,
                    icon: "info",
                    buttons: ["Cancel", "Delete"],
                    dangerMode: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('departments') }}/" + id,
                            method: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function () {
                                loadDepartments();
                                toastr.success('Department has been deleted.');
                            },
                            error: function(xhr, status, error) {
                                toastr.error(`Error : ${error}`);  // Show error message with Toastr
                                console.error(`Error :`, error);
                            }
                        });
                    }
                });
            });

            // Delete Department
            $(document).on('click', '.deletePositionBtn', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: "Delete Position ?",
                    text: `This is irreversible.`,
                    icon: "info",
                    buttons: ["Cancel", "Delete"],
                    dangerMode: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('positions') }}/" + id,
                            method: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function () {
                                loadPositions();
                                toastr.success('Position has been deleted.');
                            },
                            error: function(xhr, status, error) {
                                toastr.error(`Error : ${error}`);  // Show error message with Toastr
                                console.error(`Error :`, error);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection