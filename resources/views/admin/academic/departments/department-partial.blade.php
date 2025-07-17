<style>
    #positionsTable_wrapper .row .col-sm-12{
        text-align: left;
    }
</style>
<div class="d-flex justify-content-between mb-2">
    <h4>Departments</h4>
    <button class="btn btn-primary btn-sm" id="createDepartmentBtn">+ Department</button>
</div>

<div class="table-responsive">
    <table id="positionsTable" class="table border table-bordered text-nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dept->title }}</td>
                    <td>{{ $dept->user->name ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm editDepartmentBtn" data-id="{{ $dept->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteDepartmentBtn" data-id="{{ $dept->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#positionsTable').DataTable();
    });
</script>
