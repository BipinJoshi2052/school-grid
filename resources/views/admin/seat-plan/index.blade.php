@extends('layouts.admin')

@section('title')
    Seat Plan List
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
        .seat-plan-create-btn:hover{
            color: white;
        }
        .unassigned-list{
            cursor: pointer;
        }
        .nav-tabs{
            margin-bottom: 10px;
        }
        .nav-item .active{
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            color: white!important;
        }
        /* Styling for table cells */
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Seat Plans!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Seat Plans</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-end">
                    <a class="btn btn-primary btn-sm seat-plan-create-btn" href="{{route('seat-plan.create')}}">+ Seat Plan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="staff-container">
                            <div class="table-responsive">
                                <table id="staffTable" class="table border table-striped table-bordered text-nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Seat Plan</th>
                                            <th>Invigilator Plan</th>
                                            <th>Unassigned</th>
                                            {{-- <th>Invigilator Plan</th>
                                            <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="unassignedListModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('scripts')  
    <script src="{{ asset('admin/assets//extra-libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/assets//extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('admin//dist/js/pages/datatable/datatable-basic.init.js')}}"></script>

    <script>
        var table;

        $(document).ready(function () {
            table = $('#staffTable').DataTable({
                "paging": true, // Enable pagination
                "lengthChange": true, // Enable the length menu (10/20/30/all)
                "pageLength": 10, // Default number of rows per page
                "lengthMenu": [10, 20, 30], // Show options for 10, 20, 30, and All
                "info": true, // Show information about the number of entries
                "ordering": true, // Enable sorting
                "processing": true, // Show a processing indicator while loading data
                "searching": true, // Enable the search box
                "serverSide": true, // Enable server-side processing
                "ajax": {
                    "url": "{{ route('seat-plan.list-partial') }}", // The route to get the staff data
                    "type": "GET",
                    "data": function(d) {
                        d.page = Math.ceil(d.start / d.length) + 1;  // Pass page number to the server
                        d.pageLength = d.length; // Send the selected length to the server
                    },
                    "dataSrc": function(json) {
                        return json.data;
                    }
                },
                "columns": [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            // Calculate the global row number
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        },
                        orderable: false
                    },
                    { "data": "title" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `<button class="btn btn-primary btn-sm editSeatplanBtn" data-id="${row.id}">View</button>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `<button class="btn btn-secondary btn-sm editInvigplanBtn" data-id="${row.id}">View</button>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            // Parse unassigned students and staff to count them
                            const unassignedStudents = row.unassigned_students ? JSON.parse(row.unassigned_students) : [];
                            const unassignedStaffs = row.unassigned_staffs ? JSON.parse(row.unassigned_staffs) : [];

                            // console.log(unassignedStudents)
                            // console.log(row)
                            // Get the counts
                            const studentCount = unassignedStudents.length;
                            const staffCount = unassignedStaffs.length;

                            return `<span class="unassigned-list" data-id="${row.id}">Student - ${studentCount} <br> Invigilator - ${staffCount}</span>`;
                            // return `Student - ${studentCount} <br> Invigilator - ${staffCount}`;
                        }
                    }
                    // {
                    //     "data": null,
                    //     "render": function(data, type, row) {
                    //         return `<button class="btn btn-danger btn-sm deleteSeatplanBtn" data-id="${row.id}">Delete</button>`;
                    //     }
                    // }
                ]
            });

            $(document).on('click', '.editSeatplanBtn', function () {
                let id = $(this).data('id');
                window.location.href = '/seat-plan/' + id;
            });

            $(document).on('click', '.editInvigplanBtn', function () {
                let id = $(this).data('id');
                window.location.href = '/seat-plan/invigilator/' + id;
            });

            // Edit Position
            $(document).on('click', '.deleteSeatplanBtn', function () {
                const userId = $(this).data('id'); // Get the user ID from data-id attribute
                var page = table.page();  // Get the current page index (zero-based)
                
                // Show confirmation dialog
                Swal.fire({
                    title: "Delete Seat plan?",
                    text: "This is irreversible.",
                    icon: "info",
                    showCancelButton: true,  // Show Cancel button
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    dangerMode: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Make AJAX request to delete staff and user
                        $.ajax({
                            url: '/staffs/' + userId,  // URL to your destroy route
                            method: 'DELETE',
                            data: { 
                                id: userId,
                                '_token': '{{ csrf_token() }}'
                            },  // Send the user ID
                            success: function(response) {
                                // On success, show success message
                                toastr.success('Staff has been deleted.');
                                // loadStaffs(page);
                                // Preserve the search term when reloading the table data
                                var searchValue = table.search();  // Get current search term
                                table.ajax.reload(function() {
                                    table.search(searchValue).draw();  // Apply the previous search term
                                }, false);  // `false` to prevent page reset
                            },
                            error: function(xhr, status, error) {
                                // If error, show an error message
                                toastr.error('An error occurred. Please try again.');
                            }
                        });
                    }
                });
            });


            $(document).on('click', '.unassigned-list', function () {
                let seatPlanId = $(this).data('id');

                // Make AJAX request
                $.ajax({
                    url: '/unassigned-list/' + seatPlanId,
                    type: 'GET',
                    success: function(response) {
                        // Update modal content and show
                        $('#unassignedListModal .modal-content').html(response.modal_content);
                        $('#unassignedListModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error fetching unassigned list:', xhr);
                        alert('Failed to load unassigned list. Please try again.');
                    }
                });
            });

        });
    </script>
@endsection