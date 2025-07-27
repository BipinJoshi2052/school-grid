
<div class="table-responsive">
    <table id="staffTable" class="table border table-striped table-bordered text-nowrap" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Handicapped</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        var facultyId = $('#faculty-select').val();
        var batchId = $('#batch-select').val();
        var classId = $('#class-select').val();
        var sectionId = $('#section-select').val();

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
                "url": "{{ route('students.get-list') }}", // The route to get the staff data
                "type": "GET",
                "data": function(d) {
                    // Properly append the parameters to the data object
                    d.facultyId = facultyId;
                    d.batchId = batchId;
                    d.classId = classId;
                    d.sectionId = sectionId;
                    // Pass the 'draw' parameter (required by DataTables)
                    d.page = Math.ceil(d.start / d.length) + 1; // Calculate page number based on DataTable's start/length
                },
                "dataSrc": function (json) {
                    // You can process data here if needed, but DataTables expects the following structure:
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
                { "data": "user.name" },
                { "data": "user.email" },
                { "data": "user.phone" },
                {
                    "data": "handicapped",
                    "render": function(data, type, row) {
                        // Render 'Yes' for 1 and 'No' for 0
                        return data == 1 ? 'Yes' : 'No';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm editStaffBtn" data-id="${row.id}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteStaffBtn" data-id="${row.user_id}">Delete</button>`;
                    }
                }
            ]
        });
            // Edit Position
            $(document).on('click', '.deleteStaffBtn', function () {
                const userId = $(this).data('id'); // Get the user ID from data-id attribute
                var page = table.page();  // Get the current page index (zero-based)
                
                // Show confirmation dialog
                Swal.fire({
                    title: "Delete Student?",
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
                            url: '/students/' + userId,  // URL to your destroy route
                            method: 'DELETE',
                            data: { 
                                id: userId,
                                '_token': '{{ csrf_token() }}'
                            },  // Send the user ID
                            success: function(response) {
                                // On success, show success message
                                toastr.success('Student has been deleted.');
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
    });
</script>