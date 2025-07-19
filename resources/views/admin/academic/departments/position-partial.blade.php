<style>
    #departmentsTable_wrapper .row .col-sm-12{
        text-align: left;
    }
</style>
<div class="d-flex justify-content-between mb-2">
    <h4>Positions</h4>
    <button class="btn btn-primary btn-sm" id="createPositionBtn">+ Position</button>
</div>

<div class="table-responsive">
    <table id="departmentsTable" class="table border table-bordered text-nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $dept)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dept->title }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm editPositionBtn" data-id="{{ $dept->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm deletePositionBtn" data-id="{{ $dept->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#departmentsTable').DataTable();
    });
</script>
