@extends('layouts.admin')

@section('title')
    Faculty & Batch
@endsection

@section('styles')
    <style>
        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1em;
        }

        .content {
            padding: 30px;
        }

        .add-building-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        .building {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .building-header {
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            color: white;
            padding: 20px;
            position: relative;
        }

        .building-title {
            font-size: 1.5em;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .building-actions {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .building-content {
            padding: 25px;
        }

        .room {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .room-header {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .room-header:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
        }

        .room-title {
            font-size: 1.2em;
            font-weight: 500;
        }

        .room-stats {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .room-content {
            padding: 20px;
            display: none;
        }

        .room-content.active {
            display: block;
        }

        .bench-type-selector {
            margin-bottom: 25px;
            text-align: center;
        }

        .bench-type-selector label {
            display: inline-block;
            margin: 0 15px;
            padding: 10px 20px;
            background: #e9ecef;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bench-type-selector input[type="radio"] {
            display: none;
        }

        /* .bench-type-selector input[type="radio"]:checked + span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        } */
        .bench-type-selector label:has(input[type="radio"]:checked) {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .row-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .row-title {
            font-weight: 600;
            color: #495057;
        }

        .bench-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .bench-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .status-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .status-message.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .status-message.error {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }

        .status-message.show {
            transform: translateX(0);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .empty-state p {
            font-size: 1.1em;
            opacity: 0.8;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .building, .room {
            animation: fadeIn 0.5s ease;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .collapsed .collapse-icon {
            transform: rotate(-90deg);
        }
        .room-title-input{
            max-width: 50%;
        }
        .room-title-input:focus{
            background: white!important;
            color: black!important;
        }
        .room-title-input{
            max-width: 50%;
        }
        .building-title-input:focus{
            background: white!important;
            color: black!important;
        }
        .add-row-btn{
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Building & Rooms!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Building & Rooms</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                    <!-- Add Faculty Button centered -->
                    <button class="btn btn-primary mx-auto faculty-add-btn" onclick="addFaculty()">
                        <i class="fas fa-plus me-2"></i>
                        Add Faculty
                    </button>

                    <!-- Search bar aligned to the right -->
                    <div class="d-flex align-items-center search-div">
                        <input type="text" id="facultySearch" class="form-control" placeholder="Search Faculty" onkeyup="searchFaculty()" style="width: 200px;">
                        <i class="fas fa-search ms-2"></i>
                    </div>
                </div> --}}

                {{-- <div class="header">
                    <h1>Building Seat Planner</h1>
                    <p>Manage buildings, rooms, benches, and seats with ease</p>
                </div> --}}
                
                <div class="content">
                    <div class="add-building-section">
                        <button class="btn" id="addBuildingBtn">+ Add Building</button>
                    </div>
                    
                    <div id="buildingsContainer">
                        <div class="empty-state">
                            <h3>No Buildings Added Yet</h3>
                            <p>Click "Add Building" to get started</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="statusMessage" class="status-message"></div>
@endsection

@section('scripts')
<script>
    let buildingCounter = 0;
        // let roomCounter = 0;
        // let rowCounter = 0;
        // let benchCounter = 0;

        // Simulate AJAX requests (replace with actual endpoints)
        function sendAjaxRequest(data, callback) {
            console.log('Sending AJAX request to /buildings/add-element:', data);
            // Simulate server response
            setTimeout(() => {
                const success = Math.random() > 0.1; // 90% success rate for demo
                callback(success, success ? { id: Date.now() + Math.random() } : null);
            }, 500);
        }

        function sendAjaxAddRequest(data, callback) {
            console.log('Sending AJAX request to /buildings/add-element:', data);
            
            $.ajax({
                url: '/buildings/add-element',
                method: 'POST',
                data: JSON.stringify({
                    data: data
                }),
                headers: {  
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                contentType: 'application/json',
                success: function(response) {
                    console.log('success')
                    console.log(response)

                    const success = 1; // 90% success rate for demo
                    callback(
                        response.status === 'success' ? true : false, 
                        success ? { id: response.id } : null
                    );

                    showStatus(response.message,'success');
                },
                error: function(xhr, status, error) {
                    console.log('error')

                    callback(
                        false, 
                        null
                    );

                    showStatus(errorMessage,'error');
                }
            });
        }

        function showStatus(message, type) {
            if(type == 'error'){
                toastr.error(message);
            }
            else{
                toastr.success(message);
            }
        }

        function updateRoomStats(roomEl) {
            const benchType = roomEl.find('input[name^="bench_type_"]:checked').val();
            let totalBenches = 0;
            let totalSeats = 0;

            if (benchType === 'total') {
                const benchesInput = roomEl.find('.total-benches-input');
                const seatsInput = roomEl.find('.seats-per-bench-input');
                totalBenches = parseInt(benchesInput.val()) || 0;
                const seatsPerBench = parseInt(seatsInput.val()) || 0;
                totalSeats = totalBenches * seatsPerBench;
            } else if (benchType === 'individual') {
                roomEl.find('.row-section').each(function() {
                    const rowBenches = $(this).find('.bench-item').length;
                    totalBenches += rowBenches;
                    $(this).find('.seats-input').each(function() {
                        totalSeats += parseInt($(this).val()) || 0;
                    });
                });
            }

            roomEl.find('.room-stats').text(`${totalBenches} benches, ${totalSeats} seats`);
        }

        $('#addBuildingBtn').click(function() {
            buildingCounter++;
            const buildingId = `building_${buildingCounter}`;

            // Send AJAX request for building
            const buildingData = {
                title: `Building ${buildingCounter}`,
                type: 'building'
            };

            sendAjaxAddRequest(buildingData, (success, response) => {
                if (success) {
            
                    const buildingHtml = `
                        <div class="building" data-id="${buildingId}">
                            <div class="building-header">
                                <div class="building-title">
                                    <input type="text" placeholder="Enter building title" class="building-title-input" style="background: transparent; border: none; color: white; font-weight: 500; width: 50%; outline: none;" value="Building ${buildingCounter}">
                                </div>
                                <div class="building-actions">
                                    <button class="btn btn-secondary add-room-btn">+ Add Room</button>
                                    <button class="btn btn-danger delete-building-btn" style="margin-left: 10px;">Delete</button>
                                </div>
                            </div>
                            <div class="building-content">
                                <div class="rooms-container"></div>
                            </div>
                        </div>
                    `;

                    $('#buildingsContainer .empty-state').remove();
                    $('#buildingsContainer').append(buildingHtml);

                    $(`.building[data-id="${buildingId}"]`).attr('data-server-id', response.id);
                    // showStatus('Building added successfully!', 'success');
                } else {
                    showStatus('Error adding building', 'error');
                }
            });
        });

        $(document).on('focus', '.building-title-input', function() {
            const initialTitle = $(this).val();
            $(this).data('initial-title', initialTitle); // Store the initial value
        });
        // Building title edit
        $(document).on('blur', '.building-title-input', function() {
            const buildingEl = $(this).closest('.building');
            const title = $(this).val();
            const initialTitle = $(this).data('initial-title'); // Retrieve the initial value
            
            // Only send request if the title has changed
            if (title !== initialTitle) {
                const buildingData = {
                    title: title,
                    type: 'building',
                    id: buildingEl.attr('data-server-id')
                };

                sendAjaxRequest(buildingData, (success) => {
                    if (success) {
                        showStatus('Building title updated!', 'success');
                    } else {
                        showStatus('Error updating building title', 'error');
                    }
                });
            }
            // After blur, reset the initialTitle to prevent further comparisons
            $(this).removeData('initial-title');
        });

        // Blur on Enter key press
        $(document).on('keypress', '.building-title-input', function(event) {
            if (event.which === 13) { // Check if Enter key (key code 13) is pressed
                $(this).blur(); // Trigger blur event
            }
        });

        // Delete building
        $(document).on('click', '.delete-building-btn', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete this building and all its data! This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const buildingEl = $(this).closest('.building');
                    const buildingId = buildingEl.attr('data-server-id');
                    const type = 'building';  // Element type: building

                    // Make AJAX DELETE request
                    $.ajax({
                        url: '/buildings/delete-element',  // Your delete route
                        type: 'DELETE',
                        data: {
                            data: {
                                type: type,            // Type of element to delete (building)
                                building_id: buildingId // ID of the building to delete
                            }
                        },
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // If successful, remove the building from the UI
                            buildingEl.remove();
                            
                            // Check if there are no buildings left and display empty state
                            if ($('.building').length === 0) {
                                $('#buildingsContainer').html(`
                                    <div class="empty-state">
                                        <h3>No Buildings Added Yet</h3>
                                        <p>Click "Add Building" to get started</p>
                                    </div>
                                `);
                            } else {
                                // Re-index remaining buildings
                                $('.building').each(function(index) {
                                    // Update the data-server-id based on the new index
                                    $(this).attr('data-server-id', index + 1); // Assuming the new server ID should be 1-based index
                                });
                            }

                            // Optionally, show a success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The building has been deleted.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error if any
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error deleting the building. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                }
            });
        });


        // Add room
        $(document).on('click', '.add-room-btn', function() {
            // roomCounter++;
            const buildingEl = $(this).closest('.building');
            const buildingId = buildingEl.attr('data-server-id');
            
            // Count the number of existing room elements inside this building
            const roomCounter = buildingEl.find('.room').length + 1;  // Increment the count to create the next room ID

            const roomId = `room_${roomCounter}`;
            
            // Send AJAX request for room
            const roomData = {
                title: `Room ${roomCounter}`,
                type: 'room',
                building_id: buildingId
            };

            sendAjaxAddRequest(roomData, (success, response) => {
                if (success) {
                    // showStatus('Room added successfully!', 'success');

                    const roomHtml = `
                        <div class="room" data-id="${roomId}">
                            <div class="room-header">
                                <div class="room-title">
                                    <input type="text" placeholder="Enter room title" class="room-title-input" style="background: transparent; border: none; color: white; font-size: 1.2em; font-weight: 500; outline: none;" value="Room ${roomCounter}">
                                </div>
                                <div>
                                    <span class="room-stats">0 benches, 0 seats</span>
                                    <span class="collapse-icon" style="margin-left: 15px; font-size: 1.2em;">▼</span>
                                </div>
                            </div>
                            <div class="room-content active">
                                <div class="bench-type-selector">
                                    <label>
                                        <input type="radio" name="bench_type_${roomCounter}" value="total" checked>
                                        <span>Total Bench Data</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="bench_type_${roomCounter}" value="individual">
                                        <span>Individual Bench Data</span>
                                    </label>
                                </div>
                                
                                <div class="total-bench-section">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Total Benches</label>
                                            <input type="number" class="total-benches-input" min="0" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Seats per Bench</label>
                                            <input type="number" class="seats-per-bench-input" min="0" value="0">
                                        </div>
                                    </div>
                                    <button class="btn submit-total-bench-btn">Submit Bench Data</button>
                                </div>
                                
                                <div class="individual-bench-section" style="display: none;">
                                    <button class="btn btn-secondary add-row-btn">+ Add Row</button>
                                    <div class="rows-container"></div>
                                </div>
                                
                                <div style="text-align: right; margin-top: 20px;">
                                    <button class="btn btn-danger delete-room-btn">Delete Room</button>
                                </div>
                            </div>
                        </div>
                    `;

                    buildingEl.find('.rooms-container').append(roomHtml);
                    $(`.room[data-id="${roomId}"]`).attr('data-server-id', response.id);
                } else {
                    showStatus('Error adding room', 'error');
                }
            });


        });

        // Room collapse/expand
        $(document).on('click', '.room-header', function(event) {
            const roomEl = $(this).closest('.room');
            const content = roomEl.find('.room-content');
            const icon = $(this).find('.collapse-icon');
            
            // Prevent collapse when editing the title
            if ($(event.target).hasClass('room-title-input')) {
                return;
            }

            if (content.hasClass('active')) {
                content.removeClass('active').slideUp();
                icon.text('▶');
                roomEl.addClass('collapsed');
            } else {
                content.addClass('active').slideDown();
                icon.text('▼');
                roomEl.removeClass('collapsed');
            }
        });

        // Room title edit
        $(document).on('blur', '.room-title-input', function() {
            const roomEl = $(this).closest('.room');
            const title = $(this).val();
            
            const roomData = {
                title: title,
                type: 'room',
                id: roomEl.attr('data-server-id')
            };

            sendAjaxRequest(roomData, (success) => {
                if (success) {
                    showStatus('Room title updated!', 'success');
                } else {
                    showStatus('Error updating room title', 'error');
                }
            });
        });

        // Blur on Enter key press
        $(document).on('keypress', '.room-title-input', function(event) {
            if (event.which === 13) { // Check if Enter key (key code 13) is pressed
                $(this).blur(); // Trigger blur event
            }
        });

        // Delete room
        $(document).on('click', '.delete-room-btn', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete this building and all its data! This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const buildingEl = $(this).closest('.building');
                    const buildingId = buildingEl.attr('data-server-id');

                    const roomEl = $(this).closest('.room');
                    const roomId = roomEl.attr('data-server-id');
                    const type = 'room';  // Element type: building

                    // Make AJAX DELETE request
                    $.ajax({
                        url: '/buildings/delete-element',  // Your delete route
                        type: 'DELETE',
                        data: {
                            data: {
                                type: type,            // Type of element to delete (building)
                                building_id: buildingId, // ID of the building to delete
                                room_id: roomId
                            }
                        },
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // If successful, remove the building from the UI
                            roomEl.remove();

                            // Re-index remaining buildings
                            $('.room').each(function(index) {
                                // Update the data-server-id based on the new index
                                $(this).attr('data-server-id', index + 1); // Assuming the new server ID should be 1-based index
                            });

                            // Optionally, show a success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The room has been deleted.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error if any
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error deleting the room. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                }
            });
            // if (confirm('Are you sure you want to delete this room?')) {
            //     $(this).closest('.room').remove();
            // }
        });

        // Delete room
        $(document).on('click', '.delete-bench-btn', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete this bench and all its data! This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const buildingEl = $(this).closest('.building');
                    const buildingId = buildingEl.attr('data-server-id');

                    const roomEl = $(this).closest('.room');
                    const roomId = roomEl.attr('data-server-id');

                    const rowEl = $(this).closest('.row-section');
                    const rowId = rowEl.attr('data-server-id');

                    const benchEl = $(this).closest('.bench-item');
                    const benchId = benchEl.attr('data-server-id');

                    const type = 'bench';  // Element type: building

                    // Make AJAX DELETE request
                    $.ajax({
                        url: '/buildings/delete-element',  // Your delete route
                        type: 'DELETE',
                        data: {
                            data: {
                                type: type,            // Type of element to delete (building)
                                building_id: buildingId, // ID of the building to delete
                                room_index: roomId,
                                row_index: rowId,
                                bench_index: benchId,
                            }
                        },
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // If successful, remove the building from the UI
                            benchEl.remove();

                            // Re-index remaining buildings
                            $('.bench-item').each(function(index) {
                                // Update the data-server-id based on the new index
                                $(this).attr('data-server-id', index); // Assuming the new server ID should be 1-based index
                            });

                            // Optionally, show a success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The room has been deleted.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error if any
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error deleting the room. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                }
            });
            // if (confirm('Are you sure you want to delete this room?')) {
            //     $(this).closest('.room').remove();
            // }
        });
        // Bench type toggle
        $(document).on('change', 'input[name^="bench_type_"]', function() {
            const roomEl = $(this).closest('.room');
            const value = $(this).val();
            
            if (value === 'total') {
                roomEl.find('.total-bench-section').show();
                roomEl.find('.individual-bench-section').hide();
            } else {
                roomEl.find('.total-bench-section').hide();
                roomEl.find('.individual-bench-section').show();
            }
            
            updateRoomStats(roomEl);
        });

        // Submit total bench data
        $(document).on('click', '.submit-total-bench-btn', function() {
            const buildingEl = $(this).closest('.building');
            const buildingId = buildingEl.attr('data-server-id');
            const buildingName = buildingEl.find('.building-title-input').val();

            const roomEl = $(this).closest('.room');
            const roomId = roomEl.attr('data-server-id');
            const roomName = roomEl.find('.room-title-input').val();

            const totalBenches = roomEl.find('.total-benches-input').val();
            const seatsPerBench = roomEl.find('.seats-per-bench-input').val();

            
            const benchData = {
                title: `Total Bench Data`,
                type: 'bench',
                room_id: roomId,
                room_name: roomName,
                selected_type: 'total',
                building_id: buildingId,
                total_benches: parseInt(totalBenches),
                total_seats: parseInt(seatsPerBench)
            };

            sendAjaxAddRequest(benchData, (success) => {
                if (success) {
                    showStatus('Bench data submitted successfully!', 'success');
                    updateRoomStats(roomEl);
                } else {
                    showStatus('Error submitting bench data', 'error');
                }
            });
        });

        // Add row for individual bench data
        $(document).on('click', '.add-row-btn', function() {
            // rowCounter++;
            const roomEl = $(this).closest('.room');
            const roomId = roomEl.attr('data-server-id');
            const rowCounter = roomEl.find('.row-section').length + 1;
            const rowId = `row_${rowCounter}`;
            const buildingEl = $(this).closest('.building');
            const buildingId = buildingEl.attr('data-server-id');
            
            // console.log(rowCounter)
            // Send AJAX request for row
            const rowData = {
                title: `Row ${rowCounter}`,
                type: 'row',
                room_id: roomId,
                building_id: buildingId
            };

            sendAjaxAddRequest(rowData, (success, response) => {
                if (success) {
                    console.log(success)
                    console.log(response)
                    showStatus('Row added successfully!', 'success');

                    const rowHtml = `
                        <div class="row-section" data-id="${rowId}">
                            <div class="row-header">
                                <div class="row-title">Row ${rowCounter}</div>
                                <button class="btn btn-secondary add-bench-btn">+ Add Bench</button>
                            </div>
                            <div class="benches-container"></div>
                        </div>
                    `;

                    roomEl.find('.rows-container').append(rowHtml);
                    console.log($(`.row-section[data-id="${rowId}"]`))
                    // console.log(response)
                    // console.log(response.row_index)
            
                    $(`.row-section[data-id="${rowId}"]`).attr('data-server-id', response.id);
                } else {
                    showStatus('Error adding row', 'error');
                }
            });
        });

        // Add bench to row
        $(document).on('click', '.add-bench-btn', function() {
            // benchCounter++;

            const buildingEl = $(this).closest('.building');
            const buildingId = buildingEl.attr('data-server-id');
            const buildingName = buildingEl.find('.building-title-input').val();
            // console.log(buildingName)

            const roomEl = $(this).closest('.room');
            const roomId = roomEl.attr('data-server-id');
            const roomName = buildingEl.find('.room-title-input').val();

            const rowEl = $(this).closest('.row-section');
            const rowId = rowEl.attr('data-server-id');
            const rowName = buildingEl.find('.row-title').text();

            const benchCounter = rowEl.find('.bench-item').length + 1;

            const benchId = `bench_${benchCounter}`;
            
            const benchHtml = `
                <div class="bench-item" data-id="${benchId}">
                    <div class="bench-header">
                        <strong>Bench ${benchCounter}</strong>
                        <button class="btn btn-danger delete-bench-btn" style="padding: 5px 10px; font-size: 12px;" >Delete</button>
                    </div>
                    <div class="form-group">
                        <label>Number of Seats</label>
                        <input type="number" class="seats-input" min="0" value="0">
                    </div>
                    <button class="btn submit-bench-btn">Submit Bench</button>
                </div>
            `;

            rowEl.find('.benches-container').append(benchHtml);

            // Send AJAX request for bench
            const benchData = {
                title: `Bench ${benchCounter}`,
                type: 'bench',
                row_id: rowId,
                row_name: rowName,
                room_id: roomId,
                room_name: roomName,
                building_id:buildingId,
                building_name: buildingName,
                selected_type: 'individual',
            };
            console.log(benchData)

            sendAjaxAddRequest(benchData, (success, response) => {
                if (success) {
                    showStatus('Bench added successfully!', 'success');
                    $(`.bench-item[data-id="${benchId}"]`).attr('data-server-id', response.id);
                } else {
                    showStatus('Error adding bench', 'error');
                }
            });
        });

        // Submit individual bench
        $(document).on('click', '.submit-bench-btn', function() {
            const buildingEl = $(this).closest('.building');
            const buildingId = buildingEl.attr('data-server-id');
            const buildingName = buildingEl.find('.building-title-input').val();
            // console.log(buildingName)

            const rowEl = $(this).closest('.row-section');
            const rowId = rowEl.attr('data-server-id');
            const rowName = buildingEl.find('.row-title').text();

            const roomEl = $(this).closest('.room');
            const roomId = roomEl.attr('data-server-id');
            const roomName = roomEl.find('.room-title-input').val();

            const benchEl = $(this).closest('.bench-item');
            const benchId = benchEl.attr('data-server-id');
            const benchName = benchEl.find('.bench-header strong').text(); 
            
            const seatsValue = benchEl.find('.seats-input').val();
            
            // const seatData = {
            //     bench_id: benchId,
            //     seats_value: parseInt(seatsValue),
            //     room_name: roomName,
            //     row_id: rowId
            // };
            const benchData = {
                title: benchName,
                type: 'bench-edit',
                row_id: rowId,
                row_name: rowName,
                room_id: roomId,
                room_name: roomName,
                bench_id: benchId,
                building_id:buildingId,
                building_name: buildingName,
                selected_type: 'individual',
                seats_value: parseInt(seatsValue),
            };

            sendAjaxAddRequest(benchData, (success) => {
                if (success) {
                    showStatus('Bench seats submitted successfully!', 'success');
                    updateRoomStats(roomEl);
                } else {
                    showStatus('Error submitting bench seats', 'error');
                }
            });
        });

        // Update room stats when inputs change
        $(document).on('input', '.total-benches-input, .seats-per-bench-input, .seats-input', function() {
            updateRoomStats($(this).closest('.room'));
        });
</script>  
@endsection