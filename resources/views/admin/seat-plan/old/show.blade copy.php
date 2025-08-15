@extends('layouts.admin')

@section('title')
    {{ $data['seat_plan']['title'] }}
@endsection

@section('styles')
    <style>
        .header {
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .layout-content .breadcrumb {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }

        .layout-content .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            cursor: pointer;
        }

        .layout-content .breadcrumb a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 30px;
            min-height: 400px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .main-content .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            max-height: 50%;
        }

        .main-content .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .main-content .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .main-content .card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .main-content .card-info {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .stat {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            flex: 1;
            margin: 0 5px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
        }

        .seating-layout {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 75px;
            flex-wrap: wrap;
        }

        .layout-content .row-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            position: relative;
        }

        .layout-content .row-container::after {
            content: '';
            position: absolute;
            right: -45px;
            top: 50px;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent 0%, #dee2e6 20%, #dee2e6 80%, transparent 100%);
        }

        .layout-content .row-container:last-child::after {
            display: none;
        }

        .aisle-label {
            position: absolute;
            right: -60px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            font-size: 20px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 3px;
            white-space: nowrap;
        }

        .layout-content .row {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .layout-content .row-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 15px;
            text-align: center;
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 2px solid #e9ecef;
        }

        .bench {
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            border-radius: 8px;
            padding: 12px 8px;
            margin: 10px 5px;
            color: white;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            min-width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .bench::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 2px;
        }

        .seats {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 5px;
        }

        .seat {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .layout-type {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 20px;
            }
        }

        .student-info {
            background: #f9fbfd;
            color: black;
            padding: 2px;
            width: 110px;
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">{{ $data['seat_plan']['title'] }}!
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            {{-- <li class="breadcrumb-item text-muted active" aria-current="page">
                                {{$data['seat_plan']['title']}}
                            </li> --}}
                            <li class="breadcrumb-item text-muted active" aria-current="page">
                                <div class="breadcrumb">
                                    <span id="breadcrumb-content">🏠 {{ $data['seat_plan']['title'] }}</span>
                                </div>
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid layout-content">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="header">
                            <h1>🏢 Building Management System</h1>
                            <p>Interactive Room & Seating Visualization</p>
                        </div> --}}
                        <div class="content">
                            <div id="main-content" class="main-content">
                                <!-- Content will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <?php
    $buildingsDataJson = json_encode($data['buildings'], JSON_HEX_TAG);
    // dd($buildingsDataJson);
    ?>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Parse the entire data first
            const buildingsData = JSON.parse(`<?php echo addslashes($buildingsDataJson); ?>`);
            // console.log(buildingsData);

            // Decode the 'rooms' field in JavaScript (since it's a JSON string)
            buildingsData.forEach(function(building) {
                // Check if rooms is a string that can be parsed as JSON
                if (typeof building.rooms === 'string') {
                    try {
                        building.rooms = JSON.parse(building.rooms); // Parse it if it's a string
                    } catch (e) {
                        console.error('Error parsing rooms for building', building.id, e);
                    }
                }
            });
            // console.log(buildingsData)

            let currentView = 'buildings';
            let currentBuilding = null;
            let currentRoom = null;

            function showBuildings() {
                currentView = 'buildings';
                currentBuilding = null;
                currentRoom = null;
                updateBreadcrumb();

                let html = '<h2 style="margin-bottom: 20px; color: #2c3e50;">🏢 Buildings Overview</h2>';
                html += '<div class="grid">';

                if (buildingsData.length <= 0) {
                    html += 'No Buildings Found</div>';
                } else {
                    buildingsData.forEach(building => {
                        // const rooms = JSON.parse(building.rooms);
                        const rooms = building.rooms;
                        const totalRooms = rooms.length;
                        const totalSeats = rooms.reduce((sum, room) => {
                            if (room.selected_type === 'total') {
                                // console.log(room.total.bench)
                                // console.log(room.total.seats)
                                return sum + (room.total.benches * room.total.seats);
                            } else {
                                return sum + room.individual.reduce((rowSum, row) => {
                                    // console.log(row)
                                    return rowSum + row.bench.reduce((benchSum, bench) => {
                                        return benchSum + bench
                                        .seats; // Summing the number of seats for each bench
                                    }, 0);
                                }, 0);
                            }
                        }, 0);

                        html += `
                            <div class="card" onclick="showRooms(${building.id})">
                                <h3>${building.name}</h3>
                                <div class="card-info">Building ID: ${building.id}</div>
                                <div class="stats">
                                    <div class="stat">
                                        <div class="stat-number">${totalRooms}</div>
                                        <div class="stat-label">Rooms</div>
                                    </div>
                                    <div class="stat">
                                        <div class="stat-number">${totalSeats}</div>
                                        <div class="stat-label">Total Seats</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';
                }
                $('#main-content').html(html);
            }

            function showRooms(buildingId) {
                currentView = 'rooms';
                currentBuilding = buildingsData.find(b => b.id === buildingId);
                currentRoom = null;
                updateBreadcrumb();

                // const rooms = JSON.parse(currentBuilding.rooms);
                const rooms = currentBuilding.rooms;

                let html = `
                    <button class="back-btn" onclick="showBuildings()">← Back to Buildings</button>
                    <h2 style="margin-bottom: 20px; color: #2c3e50;">🚪 ${currentBuilding.name} - Rooms</h2>
                `;
                html += '<div class="grid">';

                rooms.forEach((room, index) => {
                    let totalSeats = 0;
                    let totalBenches = 0;

                    if (room.selected_type === 'total') {
                        totalSeats = room.total.seats * room.total.benches;
                        totalBenches = room.total.benches;
                    } else {
                        // Calculate the total number of benches in the individual rows
                        totalBenches = room.individual.reduce((sum, row) => sum + row.bench.length, 0);

                        // Calculate the total number of seats in the individual rows by summing the seats in each bench
                        totalSeats = room.individual.reduce((sum, row) => {
                            return sum + row.bench.reduce((benchSum, bench) => benchSum + bench
                                .seats, 0);
                        }, 0);
                        // You can log the results if you want to verify the calculations
                        // console.log(`Total Benches: ${totalBenches}`);
                        // console.log(`Total Seats: ${totalSeats}`);

                        // Update the room's total data with the calculated values
                        // room.total.benches = totalBenches;
                        // room.total.seats = totalSeats;
                    }

                    // <div class="card-info">Layout Type: ${room.selected_type}</div>
                    html += `
                        <div class="card" onclick="showRoomLayout(${index})">
                            <h3>${room.name} 
                                <span class="layout-type">${room.selected_type}</span>
                            </h3>
                            <div class="stats">
                                <div class="stat">
                                    <div class="stat-number">${totalBenches}</div>
                                    <div class="stat-label">Benches</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-number">${totalSeats}</div>
                                    <div class="stat-label">Seats</div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += '</div>';
                $('#main-content').html(html);
            }

            function showRoomLayout(roomIndex) {
                currentView = 'layout';
                // const rooms = JSON.parse(currentBuilding.rooms);
                const rooms = currentBuilding.rooms;
                currentRoom = rooms[roomIndex];
                updateBreadcrumb();

                let html = `
                    <button class="back-btn" onclick="showRooms(${currentBuilding.id})">← Back to Rooms</button>
                    <h2 style="margin-bottom: 20px; color: #2c3e50;">🪑 ${currentRoom.name} - Seating Layout</h2>
                    <button class="btn btn-primary" onclick="printSeatingLayout()"><i class="fa fa-print"></i>Print Seating Layout</button>
                `;

                if (currentRoom.selected_type === 'total') {
                    html += generateTotalLayout(currentRoom, roomIndex);
                } else {
                    html += generateIndividualLayout(currentRoom, roomIndex);
                }

                $('#main-content').html(html);
            }

            function generateTotalLayout1(room) {
                // console.log(room)
                const totalBenches = room.total.benches;
                const totalSeats = room.total.seats;

                if (totalBenches === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No seating arrangement</h3><p>This room has no benches or seats configured.</p></div>';
                }

                let html = '<div class="seating-layout">';

                // Determine number of rows (2 or 3 based on total benches)
                let numRows = 2;

                const benchesPerRow = Math.ceil(totalBenches / numRows);
                const seatsPerBench = Math.floor(totalSeats / totalBenches);
                const extraSeats = totalSeats % totalBenches;

                for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
                    const startBench = rowIndex * benchesPerRow;
                    const endBench = Math.min(startBench + benchesPerRow, totalBenches);

                    if (startBench >= totalBenches) break;

                    html += '<div class="row-container">';
                    html += `<div class="row-label">Row ${rowIndex + 1}</div>`;
                    html += '<div class="row">';

                    for (let i = startBench; i < endBench; i++) {
                        const benchSeats = totalSeats;
                        html += `
                            <div class="bench">
                                Bench ${i + 1}
                                <div class="seats">
                                    ${'<div class="seat"></div>'.repeat(benchSeats)}
                                </div>
                            </div>
                        `;
                    }

                    html += '</div>';

                    // Add aisle label except for last row
                    if (rowIndex < numRows - 1 && endBench < totalBenches) {
                        html += '<div class="aisle-label">Aisle</div>';
                    }

                    html += '</div>';
                }

                html += '</div>';
                return html;
            }

            function generateTotalLayout(room, roomIndex) {
                // console.log(room);
                const totalBenches = room.total.benches;
                const totalSeats = room.total.seats;
                const currentBuildingId = currentBuilding.id; // Get building id from currentBuilding
                const currentRoomId = roomIndex; // Get room id from currentRoom

                // Fetch students for the current room (you can implement an AJAX call to your backend or use preloaded data)
                // For now, we assume this function or data gives us the students in the room
                const students = getStudentsForRoom(currentBuildingId,
                currentRoomId); // This should return an array of students
                // console.log([
                //     currentBuildingId, currentRoomId
                // ])
                if (totalBenches === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No seating arrangement</h3><p>This room has no benches or seats configured.</p></div>';
                }

                let html = '<div class="seating-layout">';

                // Determine number of rows (2 or 3 based on total benches)
                let numRows = 2;
                const benchesPerRow = Math.ceil(totalBenches / numRows);
                // const seatsPerBench = Math.floor(totalSeats / totalBenches);
                const seatsPerBench = totalSeats;
                const extraSeats = 0;

                let studentIndex = 0; // To iterate through students and assign them to seats

                for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
                    const startBench = rowIndex * benchesPerRow;
                    const endBench = Math.min(startBench + benchesPerRow, totalBenches);

                    if (startBench >= totalBenches) break;

                    html += '<div class="row-container">';
                    html += `<div class="row-label">Row ${rowIndex + 1}</div>`;
                    html += '<div class="row">';

                    for (let i = startBench; i < endBench; i++) {
                        let benchSeats = seatsPerBench;
                        if (extraSeats > 0) {
                            benchSeats++;
                            extraSeats--;
                        }

                        html += `
                            <div class="bench">
                                Bench ${i + 1}
                                <div class="seats">
                        `;

                        for (let j = 0; j < benchSeats; j++) {
                            if (studentIndex < students.length) {
                                const student = students[studentIndex];
                                html += `
                                    <div class="seat-student">
                                        <div class="student-info">
                                            <div class="student-name">${student.name}</div>
                                            <div class="student-class">${student.class}</div>
                                            <div class="student-section">${student.section}</div>
                                        </div>
                                    </div>
                                `;
                                studentIndex++; // Move to the next student for the next seat
                            } else {
                                html += '<div class="seat"></div>'; // Empty seat if no student is available
                            }
                        }

                        html += '</div>'; // Close .seats
                        html += '</div>'; // Close .bench
                    }

                    html += '</div>'; // Close .row

                    // Add aisle label except for last row
                    if (rowIndex < numRows - 1 && endBench < totalBenches) {
                        html += '<div class="aisle-label">Aisle</div>';
                    }

                    html += '</div>'; // Close .row-container
                }

                html += '</div>'; // Close .seating-layout
                return html;
            }

            // Example: Function to get students for a room, you should replace this with your actual data fetching logic
            function getStudentsForRoom(buildingId, roomId) {
                // This is your mock data based on the provided structure
                const seatingData = JSON.parse(`<?php echo json_encode($data['arrangedData']); ?>`);
                // console.log(seatingData[buildingId][roomId])

                // Fetch the data for the specific building and room
                if (seatingData[buildingId]) {
                    // We assume roomId corresponds to the bench number (adjust logic if necessary)
                    const room = seatingData[buildingId][
                    roomId]; // Room data, which corresponds to an array of benches
                    let students = [];

                    // Iterate through the benches in the room
                    for (const bench in room) {
                        const seats = room[bench];
                        // console.log(seats)

                        // For each seat in the bench, add the student data
                        const seatsArray = Object.values(seats); // Convert object to an array of values
                        seatsArray.forEach(student => {
                            students.push({
                                id: student.id,
                                name: student.name,
                                class: student.class,
                                section: student.section,
                                gender: student.gender,
                                handicapped: student.handicapped,
                                roll_no: student.roll_no
                            });
                        });
                    }

                    return students;
                }

                return []; // Return an empty array if buildingId or roomId is not found
            }


            function generateIndividualLayout1(room, roomIndex) {
                if (!room.individual || room.individual.length === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No individual layout</h3><p>This room has no individual seating arrangement configured.</p></div>';
                }

                let html = '<div class="seating-layout">';

                room.individual.forEach((row, rowIndex) => {
                    html += '<div class="row-container">';
                    html += `<div class="row-label">${row.name}</div>`;
                    html += '<div class="row">';

                    row.bench.forEach(bench => {
                        html += `
                            <div class="bench">
                                ${bench.name}
                                <div class="seats">
                                    ${'<div class="seat"></div>'.repeat(bench.seats)}
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';

                    // Add aisle label except for last row
                    if (rowIndex < room.individual.length - 1) {
                        html += '<div class="aisle-label">Aisle</div>';
                    }

                    html += '</div>';
                });

                html += '</div>';
                return html;
            }

            function generateIndividualLayout(room, roomIndex) {
                const currentBuildingId = currentBuilding.id;
                if (!room.individual || room.individual.length === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No individual layout</h3><p>This room has no individual seating arrangement configured.</p></div>';
                }

                let html = '<div class="seating-layout">';

                // Fetch students for the current building and room using the getStudentsForRoom function
                const students = getStudentsForRoom(currentBuildingId, roomIndex);
                let studentIndex = 0; // To keep track of the student being assigned to each seat

                // Iterate over rows in the individual layout
                room.individual.forEach((row, rowIndex) => {
                    html += '<div class="row-container">';
                    html += `<div class="row-label">${row.name}</div>`;
                    html += '<div class="row">';

                    // Iterate over benches in each row
                    row.bench.forEach(bench => {
                        html += `
                        <div class="bench">
                            ${bench.name}
                            <div class="seats">
                    `;
                        // console.log(bench.seats)
                        // For each seat in the bench, check if there's a student
                        for (let seatIndex = 0; seatIndex < bench.seats; seatIndex++) {
                            if (studentIndex < students.length) {
                                const student = students[
                                studentIndex]; // Fetch the student for the seat
                                html += `
                                <div class="seat-student">
                                    <div class="student-info">
                                        <div class="student-name">${student.name}</div>
                                        <div class="student-class">${student.class}</div>
                                        <div class="student-section">${student.section}</div>
                                    </div>
                                </div>
                            `;
                                studentIndex++; // Move to the next student for the next seat
                            } else {
                                html +=
                                '<div class="seat"></div>'; // Empty seat if no student is assigned
                            }
                        }

                        html += '</div>'; // Close .seats
                        html += '</div>'; // Close .bench
                    });

                    html += '</div>'; // Close .row

                    // Add aisle label except for last row
                    if (rowIndex < room.individual.length - 1) {
                        html += '<div class="aisle-label">Aisle</div>';
                    }

                    html += '</div>'; // Close .row-container
                });

                html += '</div>'; // Close .seating-layout
                return html;
            }



            function updateBreadcrumb() {
                let breadcrumb = '<a onclick="showBuildings()">🏠 Home</a>';

                if (currentBuilding) {
                    breadcrumb += ` > <a onclick="showRooms(${currentBuilding.id})">${currentBuilding.name}</a>`;
                }

                if (currentRoom) {
                    breadcrumb += ` > ${currentRoom.name}`;
                }

                $('#breadcrumb-content').html(breadcrumb);
            }

            // Make functions global
            window.showBuildings = showBuildings;
            window.showRooms = showRooms;
            window.showRoomLayout = showRoomLayout;

            // Initialize with buildings view
            showBuildings();


        });
            function printSeatingLayout() {
                var printContents = document.querySelector('.seating-layout').innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                // Restore original content after printing
                document.body.innerHTML = originalContents;
            }
    </script>
@endsection
