
<div class="table-responsive">
    <table id="staffTable" class="table border table-striped table-bordered text-nowrap" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            {{-- @if (!empty($users)) --}}
                @foreach($users as $user)                                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->user->name }}</td>
                        <td>{{ $user->user->email }}</td>
                        <td>{{ $user->user->phone }}</td>
                        <td>{{ $user->department->title }}</td>
                        <td>{{ $user->position->title }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm editStaffBtn" data-id="{{ $user->id }}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteStaffBtn" data-id="{{ $user->user_id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach                
            {{-- @else
                <p>No Staffs</p>
            @endif --}}
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        var table = $('#staffTable').DataTable({
            "paging": true, // Enable pagination
            "pageLength": 10, // Number of rows per page (adjust as needed)
            "lengthChange": false, // Disable page length change (optional)
            "info": true, // Show information like "Showing 1 to 10 of X entries"
            "ordering": true, // Enable column sorting
            "processing": true, // Show processing indicator when data is being loaded
            "serverSide": true, // Enable server-side processing if you're handling pagination on the server
            "ajax": {
                "url": "{{ route('staffs.list-partial') }}", // The route to get the staff data
                "type": "GET",
                "data": function(d) {
                    // Pass the 'draw' parameter (required by DataTables)
                    d.page = Math.ceil(d.start / d.length) + 1; // Calculate page number based on DataTable's start/length
                },
                "dataSrc": function (json) {
                    // You can process data here if needed, but DataTables expects the following structure:
                    return json.data;
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "user.name" },
                { "data": "user.email" },
                { "data": "user.phone" },
                { "data": "department.title" },
                { "data": "position.title" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm editStaffBtn" data-id="${row.id}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteStaffBtn" data-id="${row.user_id}">Delete</button>`;
                    }
                }
            ]
        });
    });
</script>