@extends('layouts.admin')

@section('title')
    {{ $data['seat_plan']['title'] }}
@endsection

@section('styles')
    <style>
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .header h1 {
            color: #2c3e50;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* .breadcrumb {
            background: linear-gradient(45deg, #3498db, #2980b9);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            font-size: clamp(0.9rem, 2vw, 1rem);
        }

        .breadcrumb a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .breadcrumb a:hover {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            transform: translateY(-1px);
        } */

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stats{
            height: 90px!important;
            display: flex;
            justify-content: space-between;
            /* margin-top: 15px; */
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
        /* .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        } */

        /* .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        } */

        /* .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #3498db, #e74c3c, #f39c12, #2ecc71);
            background-size: 300% 300%;
            animation: gradient 3s ease infinite;
        } */

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* .building-card {
            cursor: pointer;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

        .building-card::before {
            background: linear-gradient(45deg, #fff, #f1c40f);
        }

        .building-card .card-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        } */
        .building-card{
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .building-card::before {
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
        .building-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .building-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .building-card .card-info {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
            min-height: 400px;
        }

        /* .building-card .card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        } */

        .card-header {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            font-weight: 600;
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* .card-body {
            padding: 20px;
            min-height: 120px;
        } */

        .room-count {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .staff-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 15px;
            margin: 10px 0;
            cursor: move;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            user-select: none;
        }

        .staff-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-color: #3498db;
        }

        .staff-card.ui-draggable-dragging {
            transform: rotate(3deg) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            z-index: 10000 !important;
            border-color: #3498db;
        }

        .ui-draggable-helper {
            z-index: 10000 !important;
            opacity: 0.9 !important;
            pointer-events: none !important;
            transform: rotate(3deg) scale(1.05);
        }

        .staff-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .staff-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3498db, #e74c3c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .staff-details {
            flex: 1;
            min-width: 0;
        }

        .staff-details h4 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            word-wrap: break-word;
        }

        .staff-details p {
            color: #7f8c8d;
            font-size: clamp(0.8rem, 1.8vw, 0.9rem);
            word-wrap: break-word;
        }

        .replace-btn {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            position: absolute;
            top: 8px;
            right: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(231, 76, 60, 0.3);
            z-index: 10;
        }

        .replace-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        .empty-room {
            text-align: center;
            padding: 30px 20px;
            color: #95a5a6;
            border: 2px dashed #bdc3c7;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            transition: all 0.3s ease;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .empty-room i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .ui-droppable-hover {
            border-color: #27ae60 !important;
            background: linear-gradient(135deg, #d5f4e6, #a8e6cf) !important;
            transform: scale(1.02);
        }

        .staff-swap-hover {
            border-color: #f39c12 !important;
            background: linear-gradient(135deg, #fef9e7, #fcf3cf) !important;
            transform: scale(1.02);
        }

        /* .modal {
            display: none;
            position: fixed;
            z-index: 15000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        } */

        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .unassigned-staff {
            cursor: pointer;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
        }

        .unassigned-staff:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-color: #3498db;
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


        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 5px;
                border-radius: 15px;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .staff-info {
                gap: 10px;
            }

            .staff-avatar {
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }

            .replace-btn {
                position: static;
                margin-top: 10px;
                width: 100%;
            }

            .breadcrumb {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
            .content {
                padding: 0px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
                margin: 2px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            /* .card-body {
                padding: 15px;
                min-height: 100px;
            } */

            .staff-card {
                padding: 12px;
            }

            .staff-details h4 {
                font-size: 0.95rem;
            }

            .staff-details p {
                font-size: 0.8rem;
            }
            .content {
                padding: 0px;
            }
        }

        @media print {.hide-when-printing {display: none;}}
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
                            <li class="breadcrumb-item text-muted active" aria-current="page">
                                <a href="{{ route('seat-plan') }}">
                                    Invigilator Plans
                                </a>
                            </li>
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
    
    <!-- Replace Staff Modal -->
    <div id="replace-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-exchange-alt"></i> Replace Staff</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Unassigned staff will be loaded here -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="entityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="entityModalTitle">Update Invigilator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                {{-- <div class="spinner-div spinner-div-edit-staff">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div> --}}
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <?php
        $buildingsDataJson = json_encode($data['buildings'], JSON_HEX_TAG);
        $grouped_staff = json_encode($data['grouped_staff'], JSON_HEX_TAG);
        // $unassigned_staffs = json_encode($data['unassigned_staffs'], JSON_HEX_TAG);
        $configs = json_encode($data['configs'], JSON_HEX_TAG);
        // $configs = 
        // dd($grouped_staff);
    ?>
@endsection

@section('scripts')
    <script>
        // $(document).ready(function() {

            $(document).ready(function() {
                showBuildings();
                setupEventHandlers();
            });
            // Parse the entire data first
            const buildingsData = JSON.parse(`<?php echo addslashes($buildingsDataJson); ?>`);
            const grouped_staff = JSON.parse(`<?php echo addslashes($grouped_staff); ?>`);
            const configs = JSON.parse(`<?php echo addslashes($configs); ?>`);
            const seatPlanId = `<?php echo $data['seat_plan_id'] ?>`;

            let currentView = 'buildings';
            let currentBuilding = null;
            let currentRoom = null;
            let currentStaffCard = null;

            // $(document).ready(function() {
                // showRooms();
            // });

            function setupEventHandlers() {
                // Modal close handlers
                $('.close').click(function() {
                    $('#replace-modal').hide();
                });

                $(window).click(function(event) {
                    if (event.target.id === 'replace-modal') {
                        $('#replace-modal').hide();
                    }
                });
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

            function showBuildings() {
                currentView = 'buildings';
                currentBuilding = null;
                currentRoom = null;
                updateBreadcrumb();
                // $('#back-btn').hide();

                let html = '<button class="back-btn" onclick="showSeatPlans()">← Back</button><h2 style="margin-bottom: 20px; color: #2c3e50;">🏢 Buildings Overview</h2>';

                html += '<div class="grid">';

                if (buildingsData.length <= 0) {
                    html += 'No Buildings Found</div>';
                } else {
                    buildingsData.forEach(building => {
                        // const rooms = JSON.parse(building.rooms);
                        const rooms = building.rooms;
                        const parsedRoom = JSON.parse(rooms);
                        // console.log(JSON.parse(rooms))
                        const totalRooms = parsedRoom.length;

                        html += `
                            <div class="card building-card" onclick="showRooms(${building.id})">
                                <h3>${building.name}</h3>
                                <div class="card-info">Building ID: ${building.id}</div>
                                <div class="stats">
                                    <div class="stat">
                                        <div class="stat-number">${totalRooms}</div>
                                        <div class="stat-label">Rooms</div>
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
                // console.log('object')
                // if (!buildingsData.find(building => building.id === buildingId)) {
                //     console.error('Building not found:', buildingId);
                //     return;
                // }

                currentView = 'rooms';
                currentBuilding = buildingsData.find(building => building.id === buildingId);
                // currentRoom = null;
                // currentRoom = rooms[roomIndex];
                updateBreadcrumb();
                $('#back-btn').show();

                // Get the rooms for the selected building
                const rooms = JSON.parse(currentBuilding.rooms);
                const buildingStaff = grouped_staff[buildingId] || [];

                let html = `
                    <button class="back-btn hide-when-printing" onclick="showBuildings()">← Back</button>
                    <h2 class="hide-when-printing" style="margin-bottom: 20px; color: #2c3e50;">🚪 Rooms in ${currentBuilding.name}</h2>
                    <button class="btn btn-primary hide-when-printing" type="button" id="printList" onclick="printInvigilatorList('${currentBuilding.id}', '${currentBuilding.name}')">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <table class="table table-bordered " style="width: 100%; border-collapse: collapse;margin-top: 30px;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 10px;">SN</th>
                                <th style="padding: 10px;">Room</th>
                                <th style="padding: 10px;">Staff</th>
                                <th style="padding: 10px;">Department</th>
                                <th style="padding: 10px;">Position</th>
                                <th style="padding: 10px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                // Iterate through rooms
                rooms.forEach((room, index) => {
                    // Find staff assigned to this room (if any)
                    const roomStaff = buildingStaff[index] ? Object.values(buildingStaff[index])[0] : null;

                    html += `
                        <tr id="row-${index}">
                            <td style="padding: 10px;">${index+1}</td>
                            <td style="padding: 10px;">${room.name}</td>
                            <td style="padding: 10px;" class="staff-name">${(roomStaff && roomStaff.name) ? roomStaff.name : '-'}</td>
                            <td style="padding: 10px;" class="staff-department">${(roomStaff && roomStaff.department) ? roomStaff.department : '-'}</td>
                            <td style="padding: 10px;" class="staff-position">${(roomStaff && roomStaff.position) ? roomStaff.position : '-'}</td>
                            <td style="padding: 10px;" class="action-buttons">
                                <button class="btn btn-primary btn-sm replaceInvigilator" data-id="${(roomStaff && roomStaff.id) ? roomStaff.id : ''}" data-seat-plan-id="${seatPlanId}" data-room-index="${index}" data-building-id="${buildingId}" data-row="${index}">
                                    ${(roomStaff && roomStaff.name) ? `Replace` : `Add`}
                                </button>
                                ${(roomStaff && roomStaff.name) ? `<button class="btn btn-danger btn-sm removeInvigilator" data-row="${index}" data-id="${roomStaff.id}" data-room-index="${index}" data-building-id="${buildingId}">
                                    Remove
                                </button>` : ''}
                            </td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                `;

                $('#main-content').html(html);
            }

        // });
        function printInvigilatorList(buildingId,buildingName){
            var printContents = document.querySelector('.main-content').innerHTML;
            var originalContents = document.body.innerHTML;

            // Temporarily replace the body content with the layout content for printing
            // document.body.innerHTML = printContents;
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
                                margin-top: 25px;
                                margin-bottom: 30px; /* Reserve space for footer */
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
                                margin: 5mm;
                                @bottom-right {
                                    content: "seatplanpro.com";
                                    font-size: 10px;
                                    color: #666;
                                }
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
                                display: block;
                                text-align: right;
                                font-size: 10px;
                                color: #666;
                                margin-top: 20px; /* Space above footer */
                            }
                            @media screen {
                                footer {
                                    display: none; /* Hide footer on screen, show only in print */
                                }
                            }
                            th{
                                border: 1px solid #000; 
                                text-align: left;
                            }
                            td{
                                border: 1px solid #000;
                            }
                        </style>
                    </head>
                    <body>
                        <!-- Building and Room Name -->
                        <h2>Building Name: ${buildingName}</h2>

                        <!-- Insert the layout content -->
                        ${printContents}
                    </body>
                </html>
            `);                
            printWindow.document.close();

            // Add an event listener to close the window after printing or canceling
            printWindow.onafterprint = function() {
                printWindow.close();  // Close the print window after printing or canceling
            };
            printWindow.print(); // Trigger print dialog

            // Restore the original content after printing
            document.body.innerHTML = originalContents;
        }

        function showSeatPlans(){
            window.location.href = `/seat-plan/`;
        }

        $(document).on('click', '.replaceInvigilator', function () {
            const seatPlanDetailId = $(this).data('id');
            const roomIndex = $(this).data('room-index');
            const row = $(this).data('row');
            const buildingId = $(this).data('building-id');
            const seatPlanId = $(this).data('seat-plan-id');

            $.ajax({
                url: '/invig-plan/load-invigilator',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { 
                    id: seatPlanDetailId, 
                    roomIndex : roomIndex,
                    row : row,
                    buildingId : buildingId,
                    seatPlanId : seatPlanId
                },
                success: function(response) {
                    // Assuming the response contains the rendered view HTML
                    $('#entityModal .modal-body').html(response); // Inject the partial view into a modal
                    $('#entityModal').modal('show'); // Show the modal with the staff list
                },
                error: function(xhr) {
                    console.error('Error loading invigilators:', xhr.responseText);
                }
            });
        });

        $(document).on('click', '.selectStaff', function () {
            const seatPlanDetailId = $(this).data('id');
            const seatPlanId = $(this).data('seat-plan-id');
            const staffId = $(this).data('staff-id');
            const buildingId = $(this).data('building-id');
            const roomIndex = $(this).data('room-index');
            const row = $(this).data('row');

            $.ajax({
                url: '/invig-plan/replace-invigilator',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: seatPlanDetailId,
                    staff_id: staffId,
                    building_id: buildingId,
                    room_index: roomIndex,
                    seat_plan_id: seatPlanId
                },
                success: function(response) {
                    if (response.success) {
                        // Update the table row with new staff details
                        $(`#row-${row} .staff-name`).text(response.staff.staff_name);
                        $(`#row-${row} .staff-department`).text(response.staff.staff_department);
                        $(`#row-${row} .staff-position`).text(response.staff.staff_position);
                        $(`#row-${row} .action-buttons`).html(`
                            <button class="btn btn-primary btn-sm replaceInvigilator" data-id="${response.staff.seat_plan_detail_id}" data-seat-plan-id="${seatPlanId}" data-room-index="${roomIndex}" data-building-id="${buildingId}" data-row="${row}">Replace</button>
                            <button class="btn btn-danger btn-sm removeInvigilator" data-id="${response.staff.seat_plan_detail_id}" data-row="${row}">Remove</button>
                        `);
                        
                        // 2️⃣ Update grouped_staff to keep id but reset other fields
                        if (grouped_staff[buildingId] && grouped_staff[buildingId][roomIndex]) {
                            const oldStaffObj = Object.values(grouped_staff[buildingId][roomIndex])[0];
                            const staffIdKey = Object.keys(grouped_staff[buildingId][roomIndex])[0];

                            grouped_staff[buildingId][roomIndex][staffIdKey] = {
                                id: oldStaffObj.id,
                                staff_id: response.staff.staff_id,
                                name: response.staff.staff_name,
                                department: response.staff.department,
                                position: response.staff.staff_position
                            };
                        }
                        $('#entityModal').modal('hide');
                    } else {
                        console.error('Failed to replace invigilator:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error replacing invigilator:', xhr.responseText);
                }
            });
        });

        $(document).on('click', '.removeInvigilator', function () {
            const staffId = $(this).data('id');
            const rowIndex = $(this).data('row');
            const buildingId = $(this).data('building-id');
            const roomIndex = $(this).data('room-index');
            // console.log(rowIndex)
            // return;

            $.ajax({
                url: `/invig-plan/remove/${staffId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update the table row to reflect the removal
                        $(`#row-${rowIndex} .staff-name`).text('-');
                        $(`#row-${rowIndex} .staff-department`).text('-');
                        $(`#row-${rowIndex} .staff-position`).text('-');
                        $(`#row-${rowIndex} .replaceInvigilator`).text('Add');
                        $(`#row-${rowIndex} .action-buttons .removeInvigilator`).remove();

                        // 2️⃣ Update grouped_staff to keep id but reset other fields
                        if (grouped_staff[buildingId] && grouped_staff[buildingId][roomIndex]) {
                            const oldStaffObj = Object.values(grouped_staff[buildingId][roomIndex])[0];
                            const staffIdKey = Object.keys(grouped_staff[buildingId][roomIndex])[0];

                            grouped_staff[buildingId][roomIndex][staffIdKey] = {
                                id: oldStaffObj.id,
                                staff_id: '-',
                                name: '-',
                                department: '-',
                                position: '-'
                            };
                        }
                    } else {
                        console.error('Failed to remove invigilator:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error removing invigilator:', xhr.responseText);
                }
            });
        });

</script>
@endsection
