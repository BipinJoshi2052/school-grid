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
        .stats{
            height: 90px!important;
        }
        .grid .card {
            min-height: 200px; /* Set a reasonable minimum height for consistency */
            max-height: 300px; /* Prevent cards from growing too tall */
            overflow: hidden; /* Hide overflowing content */
            border: 1px solid #ddd; /* Optional: for visibility */
            border-radius: 8px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
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
        /* @page {
            size: A4;
            margin: 0;
        } */
        /* @page { size: auto;  margin: 0mm; } */
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

        /* .seating-layout {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 75px;
            flex-wrap: wrap;
        } */

        /* .layout-content .row-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            position: relative;
        } */

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

        /* .layout-content .row {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        } */

        /* .layout-content .row-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 15px;
            text-align: center;
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 2px solid #e9ecef;
        } */

        /* .bench {
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
        } */

        /* .bench::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 2px;
        } */

        /* .seats {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 5px;
        } */

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
        @media print {.hide-when-printing {display: none;}}

        /* .student-info {
            background: #f9fbfd;
            color: black;
            padding: 2px;
            width: 110px;
        } */
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
        $groupedByBuildingRoomClass = json_encode($data['groupedByBuildingRoomClass'], JSON_HEX_TAG);
        $groupedByBuildingRoomClassSection = json_encode($data['groupedByBuildingRoomClassSection'], JSON_HEX_TAG);
        $studentDataForAttendance = json_encode($data['studentDataForAttendance'], JSON_HEX_TAG);
        $configs = json_encode($data['configs'], JSON_HEX_TAG);
        // $configs = 
        // dd($buildingsDataJson);
    ?>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Parse the entire data first
            const buildingsData = JSON.parse(`<?php echo addslashes($buildingsDataJson); ?>`);
            const configs = JSON.parse(`<?php echo addslashes($configs); ?>`);
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

                // console.log(`${JSON.stringify(groupedByBuildingRoomClass)}`)
                // console.log(groupedByBuildingRoomClass)
                // console.log(groupedByBuildingRoomClassSection)
                let html = `
                    <button class="back-btn hide-when-printing" onclick="showRooms(${currentBuilding.id})">← Back to Rooms</button>
                    <h2 class="hide-when-printing" style="margin-bottom: 20px; color: #2c3e50;">🪑 ${currentRoom.name} - Seating Layout</h2>
                    <button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('layout', ${currentBuilding.id}, '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Seating Layout</button>
                    <button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('roll', ${currentBuilding.id},  '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Symbol</button>
                    <button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('class', ${currentBuilding.id},  '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Class</button>
                    <button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('class-section', ${currentBuilding.id},  '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Class & Section</button>
                    <button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('attendance', ${currentBuilding.id},  '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Attendance</button>
                `;

                if(configs['custom-seatplan-attendance-print'] === 1){
                    html += `<button class="btn btn-primary hide-when-printing" onclick="printSeatingLayout('attendance-custom', ${currentBuilding.id},  '${currentBuilding.name}',${roomIndex},'${currentRoom.name}')"><i class="fa fa-print"></i>Attendance Custom</button>`;
                }

                    // <button class="btn btn-primary" onclick="printSeatingLayout('class', ${currentBuilding.id}, ${roomIndex}, '${JSON.stringify(groupedByBuildingRoomClass)}')"><i class="fa fa-print"></i>Print by Class</button>
                    // <button class="btn btn-primary" onclick="printSeatingLayout('class-section', ${currentBuilding.id}, ${roomIndex}, '${JSON.stringify(groupedByBuildingRoomClassSection)}')"><i class="fa fa-print"></i>Print by Class & Section</button>
                if (currentRoom.selected_type === 'total') {
                    html += generateTotalLayout(currentRoom, roomIndex);
                } else {
                    html += generateIndividualLayout(currentRoom, roomIndex);
                }

                $('#main-content').html(html);
            }

            function generateTotalLayout(room, roomIndex) {
                // console.log(room);
                const totalBenches = room.total.benches;
                const seatsPerBench = room.total.seats; // Assuming this is seats per bench
                const currentBuildingId = currentBuilding.id; // Get building id from currentBuilding
                const currentRoomId = roomIndex; // Get room id from currentRoom

                // Fetch students for the current room (structured by bench)
                const structuredStudents = getStudentsForRoom(currentBuildingId, currentRoomId); // Returns { "Bench 1": [{seat:1, ...}, ...], ... }
                console.log(structuredStudents);

                if (totalBenches === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No seating arrangement</h3><p>This room has no benches or seats configured.</p></div>';
                }

                let html = '<style>@media print {.seating-layout {gap:15px!important;}}</style><div class="seating-layout" style="margin-top: 30px;display: flex!important;justify-content: center;gap: 75px;flex-wrap: wrap;border: 1px solid black;padding: 15px;">';

                // Determine number of rows (2 or 3 based on total benches) - currently fixed at 2
                let numRows = 2;
                const benchesInFirstRow = Math.floor(totalBenches / numRows);
                const benchesPerRow = Math.ceil(totalBenches / numRows);
                const benchesInSecondRow = totalBenches - benchesInFirstRow;
                console.log(`Row 1: ${benchesInFirstRow} benches, Row 2: ${benchesInSecondRow} benches`);
                console.log(benchesPerRow)

                for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
                    const benchesInRow = rowIndex === 0 ? benchesInFirstRow : benchesInSecondRow;
                    const startBench = rowIndex === 0 ? 0 : benchesInFirstRow;
                    const endBench = startBench + benchesInRow;

                    // const startBench = rowIndex * benchesPerRow;
                    // const endBench = Math.min(startBench + benchesPerRow, totalBenches);

                    if (startBench >= totalBenches) break;

                    html += '<div class="row-container" style="display: flex;flex-direction: column;align-items: center;min-width: 300px;position: relative;">';
                    html += `<div class="row-label" style="font-weight: bold;color: #495057;margin-bottom: 15px;text-align: center;padding: 8px 16px;font-size: 14px;border: 2px solid #000000;">Row ${rowIndex + 1}</div>`;
                    html += '<div class="row" style="display: flex;flex-direction: column;gap: 15px;align-items: center;">';

                    if(rowIndex === 0 && (totalBenches%2 != 0)){
                        html += '<div class="bench" style=" position: relative;max-width: 315px;padding: 10px;min-width: 315px;min-height: 50px;"></div>'
                    }

                    for (let i = startBench; i < endBench; i++) {
                        const benchKey = `Bench ${i + 1}`;
                        const benchStudents = structuredStudents[benchKey] || []; // Get students for this bench

                        // Sort benchStudents by seat number to ensure correct order
                        benchStudents.sort((a, b) => a.seat - b.seat);

                        html += `
                            <div class="bench" style="color: #000000; text-align: center; font-size: 12px; position: relative; border: 1px solid black;max-width: 315px;padding: 10px;min-width: 315px;">
                                ${benchKey}
                                <div class="seats" style="display: flex;flex-wrap: wrap;gap: 30px;margin-top: 5px;margin-left: 15px;">
                        `;
                        // justify-content: center;
                        // We assume up to seatsPerBench seats, fill with assigned students or empty
                        for (let j = 0; j < seatsPerBench; j++) {
                            // Find the student assigned to this seat (seat numbers are 1-indexed)
                            const student = benchStudents.find(s => s.seat === (j + 1));
                            // console.log(student)
                            if (student) {
                                html += `
                                    <div class="seat-student" style="border: 1px solid black;background: none;">
                                        <div class="student-info" style="color: black;padding: 2px;width: 110px;">
                                            <div class="student-name">${student.name}</div>
                                            <div class="student-rol">${student.roll_no}</div>
                                        </div>
                                    </div>
                                `;
                            } else {
                                html += '<div class="seat"></div>'; // Empty seat
                            }
                        }
                        // <div class="student-class">${student.class} / ${student.section}</div>

                        html += '</div>'; // Close .seats
                        html += '</div>'; // Close .bench
                    }

                    html += '</div>'; // Close .row

                    // Add aisle label except for last row
                    if (rowIndex < numRows - 1 && endBench < totalBenches) {
                        html += '<div class="aisle-label hide-when-printing">Aisle</div>';
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
                // console.log(seatingData)

                // Fetch the data for the specific building and room
                if (seatingData[buildingId] && seatingData[buildingId][roomId]) {
                    const room = seatingData[buildingId][roomId]; // Object with benches as keys
                    const structuredData = {}; // Will hold { benchName: [{ student with seat }] }

                    // Iterate through the benches in the room
                    for (const bench in room) {
                        const seats = room[bench];
                        const seatsArray = Object.entries(seats).map(([seatNumber, student]) => ({
                            seat: parseInt(seatNumber), // Preserve seat number
                            id: student.id,
                            name: student.name,
                            class: student.class,
                            section: student.section,
                            gender: student.gender,
                            handicapped: student.handicapped,
                            roll_no: student.roll_no
                        }));

                        structuredData[bench] = seatsArray; // Group by bench
                    }

                    return structuredData; // e.g., { "Bench 1": [students with seats], "Bench 2": [...] }
                }

                return {}; // Return empty object if not found
            }
            function getStudentsForRoom1(buildingId, roomId) {
                // This is your mock data based on the provided structure
                const seatingData = JSON.parse(`<?php echo json_encode($data['arrangedData']); ?>`);
                // console.log(seatingData[buildingId][roomId])

                // Fetch the data for the specific building and room
                if (seatingData[buildingId]) {
                    // We assume roomId corresponds to the bench number (adjust logic if necessary)
                    const room = seatingData[buildingId][roomId]; // Room data, which corresponds to an array of benches
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

            function generateIndividualLayout(room, roomIndex) {
                const currentBuildingId = currentBuilding.id;
                if (!room.individual || room.individual.length === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No individual layout</h3><p>This room has no individual seating arrangement configured.</p></div>';
                }

                let html = '<style>@media print {.seating-layout {gap:0!important;}}</style><div class="seating-layout" style="margin-top: 30px;display: flex!important;justify-content: center;gap: 75px;flex-wrap: wrap;border: 1px solid black;padding: 15px;">';

                // Fetch students for the current building and room using the getStudentsForRoom function
                const students = getStudentsForRoom(currentBuildingId, roomIndex);
                let studentIndex = 0; // To keep track of the student being assigned to each seat

                // Iterate over rows in the individual layout
                room.individual.forEach((row, rowIndex) => {
                    html += '<div class="row-container" style="display: flex;flex-direction: column;align-items: center;min-width: 300px;position: relative;">';
                    html += `<div class="row-label" style="font-weight: bold;color: #495057;margin-bottom: 15px;text-align: center;padding: 8px 16px;font-size: 14px;border: 2px solid #000000;">${row.name}</div>`;
                    html += '<div class="row" style="display: flex;flex-direction: column;gap: 15px;align-items: center;">';

                    // Iterate over benches in each row
                    row.bench.forEach(bench => {
                        html += `
                        <div class="bench" style="color: #000000; text-align: center; font-size: 15px; position: relative; border: 1px solid black;max-width: 285px;min-width: 285px;padding: 10px;">
                            ${bench.name}
                            <div class="seats" style="display: flex;justify-content: center;flex-wrap: wrap;gap: 30px;margin-top: 5px;">
                    `;
                        // console.log(bench.seats)
                        // For each seat in the bench, check if there's a student
                        for (let seatIndex = 0; seatIndex < bench.seats; seatIndex++) {
                            if (studentIndex < students.length) {
                                const student = students[
                                studentIndex]; // Fetch the student for the seat
                                html += `
                                <div class="seat-student" style="border: 1px solid black;background: none;">
                                    <div class="student-info" style="color: black;padding: 2px;width: 110px;">
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
                        html += '<div class="aisle-label" hide-when-printing>Aisle</div>';
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
        function printSeatingLayout(type,buildingId, currentBuildingName,roomIndex, currentRoomName) {
            var groupedByBuildingRoomClass = JSON.parse(`<?php echo addslashes($groupedByBuildingRoomClass); ?>`);
            var groupedByBuildingRoomClassSection = JSON.parse(`<?php echo addslashes($groupedByBuildingRoomClassSection); ?>`);
            var studentDataForAttendance = JSON.parse(`<?php echo addslashes($studentDataForAttendance); ?>`);

            // console.log(groupedByBuildingRoomClass)
            // console.log(groupedByBuildingRoomClassSection)
            if (type === 'layout') {
                // Get the HTML content of the seating layout
                var printContents = document.querySelector('.main-content').innerHTML;
                var originalContents = document.body.innerHTML;

                // Temporarily replace the body content with the layout content for printing
                document.body.innerHTML = printContents;
                // Open a new window to handle printing (similar to other sections)
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>seatplanpro.com</title>
                            <style>
                                body { 
                                    font-family: Arial, sans-serif; 
                                    font-size: 12px; 
                                    margin: 0;
                                    margin-top:25px;
                                }
                                h2, h3 { 
                                    text-align: center; 
                                    margin: 0;
                                }
                                .class-section { 
                                    margin-bottom: 15px; 
                                }
                                @page { 
                                    size: A4; 
                                    margin: 0mm; 
                                }
                                .seating-layout {
                                    display: flex !important;
                                    gap: 75px;
                                    flex-wrap: wrap;
                                }
                                .hide-when-printing {
                                    display: none;
                                }
                                footer {
                                    position: fixed; /* Maintain fixed position in print */
                                    // bottom: 10px;
                                    right: 10px;
                                    text-align: right;
                                    font-size: 10px;
                                    color: #666;
                                }
                            </style>
                        </head>
                        <body>
                            <!-- Building and Room Name -->
                            <h2>Building Name: ${currentBuildingName}</h2>
                            <h3>Room Name: ${currentRoomName}</h3>

                            <!-- Insert the layout content -->
                            ${printContents}
                            <footer>seatplanpro.com</footer>
                        </body>
                    </html>
                `);                
                // printWindow.document.write(printContents); // Insert the layout content
                //printWindow.document.write('</body></html>');
                printWindow.document.close();

                // Add an event listener to close the window after printing or canceling
                printWindow.onafterprint = function() {
                    printWindow.close();  // Close the print window after printing or canceling
                };
                printWindow.print(); // Trigger print dialog

                // Restore the original content after printing
                document.body.innerHTML = originalContents;

            }else {
                const roomId = roomIndex;

                // Fetch the corresponding data from the grouped arrays
                let dataToPrint = '';
                if (type === 'class') {
                    // Get the roll numbers grouped by class for the building and room
                    if (groupedByBuildingRoomClass[buildingId] && groupedByBuildingRoomClass[buildingId][roomId]) {
                        const roomData = groupedByBuildingRoomClass[buildingId][roomId];
                        console.log(groupedByBuildingRoomClass)
                        dataToPrint = generatePrintHTMLByClass(currentBuildingName,roomData, currentRoomName);
                    }
                }else if (type === 'class-section') {
                    // Get the roll numbers grouped by class & section for the building and room
                    if (groupedByBuildingRoomClassSection[buildingId] && groupedByBuildingRoomClassSection[buildingId][roomId]) {
                        const roomData = groupedByBuildingRoomClassSection[buildingId][roomId];
                        dataToPrint = generatePrintHTMLByClassSection(currentBuildingName,roomData, currentRoomName);
                    }
                }else if (type === 'roll') {
                    // Get the roll numbers grouped by class for the building and room
                    if (groupedByBuildingRoomClass[buildingId] && groupedByBuildingRoomClass[buildingId][roomId]) {
                        const roomData = groupedByBuildingRoomClass[buildingId][roomId];
                        console.log(groupedByBuildingRoomClass)
                        dataToPrint = generatePrintHTMLByRoll(currentBuildingName,roomData, currentRoomName);
                    }
                }else if (type === 'attendance') {
                    // Get the roll numbers grouped by class for the building and room
                    if (studentDataForAttendance[buildingId] && studentDataForAttendance[buildingId][roomId]) {
                        const roomData = studentDataForAttendance[buildingId][roomId];
                        console.log(groupedByBuildingRoomClass)
                        dataToPrint = generatePrintHTMLByAttendance(currentBuildingName,roomData, currentRoomName);
                    }
                }else if (type === 'attendance-custom') {
                    // Get the roll numbers grouped by class for the building and room
                    if (studentDataForAttendance[buildingId] && studentDataForAttendance[buildingId][roomId]) {
                        const roomData = studentDataForAttendance[buildingId][roomId];
                        console.log(groupedByBuildingRoomClass)
                        dataToPrint = generatePrintHTMLByAttendanceCustom(currentBuildingName,roomData, currentRoomName);
                    }
                }

                // Open a new window and print the content
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                <html>
                <head>
                    <title>seatplanpro.com</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            font-size: 12px;
                            margin-top:25px; 
                        } 
                        h2, h3 { 
                            text-align: center; 
                        } 
                        .class-section { 
                            margin-bottom: 15px; 
                        }
                        @page { 
                            size: A4;  
                            margin: 5mm; 
                        }
                        .hide-when-printing {
                            display: none;
                        }
                        footer {
                            position: fixed;
                            right: 10px;
                            text-align: left;
                            font-size: 10px;
                            color: #666;
                        }
                    </style>
                </head>
                <body>`);
                printWindow.document.write(dataToPrint);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
// <footer>seatplanpro.com</footer>
                // Add an event listener to handle after printing is done or canceled
                printWindow.onafterprint = function() {
                    printWindow.close();  // Close the print window after printing or canceling
                };
                printWindow.print();
            }
        }
            
        function generatePrintHTMLByAttendance(currentBuildingName, roomData, roomName) {
            // Get current date (formatted as YYYY-MM-DD)
            const today = new Date().toLocaleDateString('en-CA'); // e.g., 2025-08-13

            // Start HTML with building and room name
            let html = `
                <div class="attendance-sheet">
                    <h2>Building Name: ${currentBuildingName}</h2>
                    <h3>Room Name: ${roomName}</h3>
                    <p style="display: flex; justify-content: space-between;">
                        <span>Date: ${today}</span>
                        <span>Subject: ____________________</span>
                    </p>
                    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 8px; text-align: left;">S.N.</th>
                                <th style="padding: 8px; text-align: left;">Symbol</th>
                                <th style="padding: 8px; text-align: left;">Name</th>
                                <th style="padding: 8px; text-align: left;">Signature</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            // Iterate over roomData array to populate table rows
            roomData.forEach((student, index) => {
                html += `
                    <tr>
                        <td style="padding: 8px;">${index + 1}</td>
                        <td style="padding: 8px;">${student.roll_no}</td>
                        <td style="padding: 8px;">${student.name}</td>
                        <td style="padding: 8px;"></td>
                    </tr>
                `;
            });

            // Close table and div
            html += `
                        </tbody>
                    </table>
                </div>
            `;

            return html;
        }

        function generatePrintHTMLByRoll(currentBuildingName, roomData, roomName) {
            let html = `<h2>Building Name: ${currentBuildingName}</h2>`;
            html += `<h3>Room Name: ${roomName}</h3>`;
            
            html += `<p>`;
            for (let className in roomData) {
                roomData[className].forEach(rollNo => {
                    html += `${rollNo}, `;
                });
            }
            html += `</p>`;
                
            // html += `</ul>`;

            return html;
        }

        function generatePrintHTMLByClass(currentBuildingName, roomData, roomName) {
            let html = `<h2>Building Name: ${currentBuildingName}</h2>`;
            html += `<h3>Room Name: ${roomName}</h3>`;

            for (let className in roomData) {
                html += `<h4>Class - ${className}</h4>`;
                // html += `<ul style="list-style-type: none;">`;
                
                // let counter = 1;

                // roomData[className].forEach(rollNo => {
                //     html += `<li style="margin-bottom:5px;">${counter}. Roll No: ${rollNo}</li>`;
                //     counter++; // Increment the counter
                // });

                html += `<p>Symbol Numbers = `;
                roomData[className].forEach(rollNo => {
                    html += `${rollNo}, `;
                });
                html += `</p>`;
                
                html += `</ul>`;
            }

            return html;
        }

        function generatePrintHTMLByClassSection(currentBuildingName, roomData, roomName) {
            let html = `<h2>Building Name: ${currentBuildingName}</h2>`;
            html += `<h3>Room Name: ${roomName}</h3>`;

            for (let classSection in roomData) {
                html += `<h4>${classSection}</h4>`;
                html += `<p>Symbol Numbers = `;
                roomData[classSection].forEach(rollNo => {
                    html += `${rollNo}, `;
                });
                html += `</p>`;
            }

            return html;
        }
        function generatePrintHTMLByAttendanceCustom(currentBuildingName, roomData, roomName) {
         // Get current date in DD/MM/YYYY format for Nepal
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const year = today.getFullYear();
    const formattedDate = `${day}/${month}/${year}`; // e.g., 13/08/2025

    // Function to generate a page's HTML
    function generatePage(studentData) {
        let html = `
            <div class="attendance-sheet" style="page-break-after: always;">
                <div style="text-align: center; margin-bottom: 10px;">
                    <h2>प.फा.नं.&nbsp;&nbsp;&nbsp;&nbsp;परीक्षार्थीहरुको हाजिरी फाराम</h2>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <span>विषयः ____________________</span>
                        <span>पत्रः ____________________</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <span>मितिः ${formattedDate}</span>
                        <span>कोठा नं. : ${roomName}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                    </div>
                </div>
                <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 1px; text-align: center;">S.N.</th>
                            <th style="padding: 1px; text-align: left;">Symbol No.</th>
                            <th style="padding: 1px; text-align: left;">Name of Students</th>
                            <th style="padding: 1px; text-align: left;">Answer Sheet No.</th>
                            <th style="padding: 1px; text-align: left;">Subject of Tomorrow</th>
                            <th style="padding: 1px; text-align: left;">Signature</th>
                            <th style="padding: 1px; text-align: left;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Populate up to 30 rows
        const maxRows = 30;
        for (let i = 0; i < maxRows; i++) {
            const student = studentData[i] || {};
            html += `
                <tr>
                    <td style="padding: 2px; text-align: center;">${i + 1}</td>
                    <td style="padding: 2px;width:100px;">${student.roll_no || ''}</td>
                    <td style="padding: 2px;">${student.name || ''}</td>
                    <td style="padding: 2px;">${student.answer_sheet_no || ''}</td>
                    <td style="padding: 2px;">${student.subject_of_tomorrow || ''}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;">${student.remarks || ''}</td>
                </tr>
            `;
        }

        // Close table and add footer
        html += `
                    </tbody>
                </table>
                <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                    <p>निरीक्षकको हस्ताक्षर : ____________________</p>
                    <p>केन्द्राध्यक्षको हस्ताक्षर : ____________________</p>
                </div>
                <div style="float: right;">
                    <p>केन्द्राध्यक्षको नाम : ____________________</p>
                    <p>परीक्षा केन्द्र : नेपाल नमूना मा.वि.</p>
                </div>
            </div>
        `;
        return html;
    }

    // Generate pages
    let html = '';
    const chunkSize = 30;
    for (let i = 0; i < roomData.length; i += chunkSize) {
        const chunk = roomData.slice(i, i + chunkSize);
        html += generatePage(chunk);
    }

    // Ensure at least one page if no data
    if (roomData.length === 0) {
        html += generatePage([]);
    }

    return html;
}

// Print function with enhanced print styles
function printAttendance() {
    const printContent = document.getElementById('attendancePreview').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
                <style>
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        .attendance-sheet {
                            margin-top: 10mm; /* Top margin for all pages */
                            page-break-before: auto;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            page-break-inside: auto;
                        }
                        th, td {
                            border: 1px solid black;
                            padding: 2px;
                            text-align: left;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        tr {
                            page-break-inside: avoid; /* Prevent row splitting */
                            page-break-after: auto;
                        }
                        @page {
                            size: A4;
                            margin: 0mm; /* Remove all margins to hide header and footer */
                            @bottom-center {
                                content: normal; /* Remove footer content */
                            }
                            @top-center {
                                content: normal; /* Remove header content */
                            }
                        }
                        thead {
                            display: table-header-group; /* Repeat table headers on each page */
                        }
                        tfoot {
                            display: table-footer-group;
                        }
                    }
                    /* Screen styles for preview */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 2px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .attendance-sheet div {
                        display: flex;
                        justify-content: space-between;
                    }
                </style>
                ${printContent}
    `);
            return html;
    // printWindow.document.close();
    // printWindow.print();
}

    </script>
@endsection
