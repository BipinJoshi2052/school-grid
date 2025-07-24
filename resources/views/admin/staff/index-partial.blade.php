
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
        $('#staffTable').DataTable();
    });
</script>