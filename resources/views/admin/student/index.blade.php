@extends('layouts.admin')

@section('title')
    Students
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css')}}">

    <style>
        #studentTable_wrapper .row .col-sm-12{
            text-align: left;
        }
        /* .filter-option{
            display: flex;
        } */
        #student-container{
            margin-top: 20px;
        }
    </style>

@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Students!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Students</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-end">
                     <button class="btn btn-primary btn-sm" id="createStaffBtn">+ Student</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="filter-option row">
                            <!-- Faculty Filter -->
                            <div class="col-md-3 form-group">
                                <label for="faculty-select">Select Faculty:</label>
                                <select id="faculty-select" class="form-control">
                                    <option value="">Select Faculty</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 form-group">
                                <!-- Batch Filter (Initially Disabled) -->
                                <label for="batch-select">Select Batch:</label>
                                <select id="batch-select" class="form-control" disabled>
                                    <option value="">Select Batch</option>
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <!-- Class Filter (Initially Disabled) -->
                                <label for="class-select">Select Class:</label>
                                <select id="class-select" class="form-control">
                                    <option value="">Select Class</option>
                                    <option value="all">All</option>
                                    @foreach($classesWithNoBatch as $class)
                                        <option value="{{ $class->id }}">{{ $class->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <!-- Section Filter (Initially Disabled) -->
                                <label for="section-select">Select Section:</label>
                                <select id="section-select" class="form-control" disabled>
                                    <option value="">Select Section</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>

                        <div id="student-container">
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
                    <h5 class="modal-title" id="entityModalTitle">Create Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                {{-- <div class="spinner-div spinner-div-edit-staff">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div> --}}
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
        $(document).ready(function() {
            var table = '';
            $(document).ready(function() {
                var facultyId = '';
                var batchId = '';
                var classId = '';
                var sectionId = '';
                // When Faculty is selected
                $('#faculty-select').on('change', function() {
                    facultyId = $(this).val();
                    batchId = '';
                    classId = '';
                    sectionId = '';
                    

                    // Disable batch, class, and section initially
                    $('#batch-select').prop('disabled', true);
                    $('#class-select').prop('disabled', true);
                    $('#section-select').prop('disabled', true);
                    // Reset the class and section selects to empty values
                    $('#class-select').empty().append('<option value="">Select Class</option>');
                    $('#section-select').empty().append('<option value="">Select Section</option>');

                    if(facultyId) {
                        // Enable batch dropdown
                        $('#batch-select').prop('disabled', false);

                        // Get batches based on selected faculty
                        $.ajax({
                            url: '/get-batches/' + facultyId,  // Route to get batches by faculty
                            type: 'GET',
                            success: function(data) {
                                $('#batch-select').empty().append('<option value="">Select Batch</option>');
                                $.each(data, function(key, value) {
                                    $('#batch-select').append('<option value="' + value.id + '">' + value.title + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#batch-select').empty().append('<option value="">Select Batch</option>');
                        $.ajax({
                            url: '/get-classes-without-batch',  // Route to get classes without a batch
                            type: 'GET',
                            success: function(data) {
                                $('#class-select').empty().append('<option value="">Select Class</option>');
                                $.each(data, function(key, value) {
                                    $('#class-select').append('<option value="' + value.id + '">' + value.title + '</option>');
                                });

                                // Enable class dropdown
                                $('#class-select').prop('disabled', false);
                            }
                        });
                    }
                });

                // When Batch is selected
                $('#batch-select').on('change', function() {
                    var batchId = $(this).val();
                    classId = '';
                    sectionId = '';

                    // Disable class and section filters initially
                    $('#class-select').prop('disabled', true);
                    $('#section-select').prop('disabled', true);

                    if(batchId) {
                        // Enable class dropdown
                        $('#class-select').prop('disabled', false);

                        // Get classes based on selected batch
                        $.ajax({
                            url: '/get-classes/' + batchId,  // Route to get classes by batch
                            type: 'GET',
                            success: function(data) {
                                $('#class-select').empty().append('<option value="">Select Class</option>');
                                $.each(data, function(key, value) {
                                    $('#class-select').append('<option value="' + value.id + '">' + value.title + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#class-select').empty().append('<option value="">Select Class</option>');
                    }
                });

                // When Class is selected
                $('#class-select').on('change', function() {
                    var classId = $(this).val();
                    sectionId = '';

                    // Disable section filter initially
                    $('#section-select').prop('disabled', true);

                    if(classId) {
                        // Enable section dropdown
                        $('#section-select').prop('disabled', false);

                        // Get sections based on selected class
                        $.ajax({
                            url: '/get-sections/' + classId,  // Route to get sections by class
                            type: 'GET',
                            success: function(data) {
                                $('#section-select').empty().append('<option value="">Select Section</option><option value="all">All</option>');
                                $.each(data, function(key, value) {
                                    $('#section-select').append('<option value="' + value.id + '">' + value.title + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#section-select').empty().append('<option value="">Select Section</option><option value="all">All</option>');
                    }
                });


                // Listen for changes on the faculty, batch, class, and section dropdowns
                $('#faculty-select, #batch-select, #class-select, #section-select').on('change', function() {
                    // Get the selected values
                    facultyId = $('#faculty-select').val();
                    batchId = $('#batch-select').val();
                    classId = $('#class-select').val();
                    sectionId = $('#section-select').val();
                    // If all necessary filters are selected, make an AJAX request
                    if ((facultyId && batchId && classId && sectionId) || (classId && sectionId)) {
                        // Show a loading indicator or something similar if needed
                        $('#student-container').html('<p>Loading...</p>');

                        // Make the AJAX request to fetch filtered students
                        $.ajax({
                            url: '{{ route("students.list-partial") }}',
                            // data: {
                            //     'facultyId' : facultyId,
                            //     'batchId' : batchId,
                            //     'classId' : classId,
                            //     'sectionId' : sectionId
                            // },
                            type: 'GET',
                            success: function(response) {
                                // Replace the content of the student container with the received data
                                $('#student-container').html(response);
                            },
                            error: function(xhr) {
                                // Handle errors
                                $('#student-container').html('<p>Error loading student data.</p>');
                            }
                        });
                    }
                });
            });

            // $('#studentTable').DataTable();

            $(document).on('click', '#createStaffBtn', function () {
                $('#entityModalTitle').html('Create Student');
                $.ajax({
                    url: "{{ route('students.create') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        // console.log('object')
                        // setTimeout(() => {
                        // $('.spinner-div-edit-staff').hide();
                        $('.create-form').html(data);                            
                        // }, 300);
                    }
                });
                $('#entityModal').modal('show');
            });

            // Edit Department
            $(document).on('click', '.editStaffBtn', function () {
                let id = $(this).data('id');
                $('#entityModalTitle').html('Edit Student');

                $.ajax({
                    url: "{{ route('students.edit', ':id') }}".replace(':id', id), // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        // setTimeout(() => {
                        // $('.spinner-div-edit-staff').hide();
                        $('.create-form').html(data);                         
                        // }, 300);
                    }
                });
                $('#entityModal').modal('show');
                // console.log('object')
            });

        });
    </script>
@endsection