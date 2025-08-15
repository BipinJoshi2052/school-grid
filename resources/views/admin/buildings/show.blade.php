@extends('layouts.admin')

@section('title')
    Visualize Buildings Layout
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
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Visualize Buildings Layout!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Visualize Buildings Layout</li>
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
                        </div>
                        <div class="breadcrumb">
                            <span id="breadcrumb-content">🏠 Hoasdme</span>
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
        $buildingsDataJson = json_encode($data, JSON_HEX_TAG);  
        // dd($data);
    ?>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Sample data - replace with your actual data
            // const buildingsData = [
            //     {
            //         "id": 90,
            //         "name": "Building 1",
            //         "rooms": "[{\"name\": \"Room 1\", \"total\": {\"seats\": 24, \"benches\": 6}, \"individual\": [], \"selected_type\": \"total\"}]",
            //         "user_id": 132,
            //         "added_by": 132,
            //         "created_at": "2025-07-30T18:33:42.000000Z",
            //         "updated_at": "2025-07-30T18:33:45.000000Z",
            //         "deleted_at": null
            //     },
            //     {
            //         "id": 91,
            //         "name": "Building 2",
            //         "rooms": "[{\"name\": \"Conference Room\", \"total\": {\"seats\": 36, \"benches\": 12}, \"individual\": [], \"selected_type\": \"total\"}, {\"name\": \"Auditorium\", \"total\": {\"seats\": 0, \"benches\": 0}, \"individual\": [{\"name\": \"Row 1\", \"bench\": [{\"name\": \"Bench A\", \"seats\": 4}, {\"name\": \"Bench B\", \"seats\": 6}, {\"name\": \"Bench C\", \"seats\": 4}]}, {\"name\": \"Row 2\", \"bench\": [{\"name\": \"Bench D\", \"seats\": 5}, {\"name\": \"Bench E\", \"seats\": 3}]}, {\"name\": \"Row 3\", \"bench\": [{\"name\": \"Bench F\", \"seats\": 6}]}], \"selected_type\": \"individual\"}, {\"name\": \"Meeting Room\", \"total\": {\"seats\": 18, \"benches\": 6}, \"individual\": [], \"selected_type\": \"total\"}]",
            //         "user_id": 132,
            //         "added_by": 132,
            //         "created_at": "2025-07-30T18:36:47.000000Z",
            //         "updated_at": "2025-07-30T18:37:27.000000Z",
            //         "deleted_at": null
            //     }
            // ];

            // Parse the entire data first
            const buildingsData = JSON.parse('<?php echo addslashes($buildingsDataJson); ?>');
            console.log(buildingsData);

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
            console.log(buildingsData)

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
                
                if(buildingsData.length <= 0){
                    html += 'No Buildings Found</div>';
                }
                else{
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
                                    console.log(row)
                                    return rowSum + row.bench.reduce((benchSum, bench) => {
                                        return benchSum + bench.seats; // Summing the number of seats for each bench
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
                            return sum + row.bench.reduce((benchSum, bench) => benchSum + bench.seats, 0);
                        }, 0);
// You can log the results if you want to verify the calculations
console.log(`Total Benches: ${totalBenches}`);
console.log(`Total Seats: ${totalSeats}`);

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
                `;
                
                if (currentRoom.selected_type === 'total') {
                    html += generateTotalLayout(currentRoom);
                } else {
                    html += generateIndividualLayout(currentRoom);
                }
                
                $('#main-content').html(html);
            }

            function generateTotalLayout(room) {
                const totalBenches = room.total.benches;
                const totalSeats = room.total.seats;
                
                if (totalBenches === 0) {
                    return '<div class="empty-state"><div style="font-size: 4rem; margin-bottom: 20px;">🪑</div><h3>No seating arrangement</h3><p>This room has no benches or seats configured.</p></div>';
                }
                
                let html = '<div class="seating-layout">';
                
                // Determine number of rows (2 or 3 based on total benches)
                let numRows = 2;
                // if (totalBenches >= 12) {
                //     numRows = 3;
                // } else if (totalBenches >= 6) {
                //     numRows = 2;
                // } else {
                //     numRows = 1;
                // }
                
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

            function generateIndividualLayout(room) {
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
    </script>
@endsection