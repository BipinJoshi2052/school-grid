@extends('layouts.admin')

@section('title')
    Seat Plan
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/ui-lightness/jquery-ui.min.css">
    <style>

        .main-container {
            max-width: 1600px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            position: relative;
            transition: all 0.3s ease;
        }

        .main-container.unassigned-visible {
            grid-template-columns: 1fr 150px;
        }

        .room-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .room-section.shrunk {
            /* margin-right: 370px; */
        }

        .unassigned-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: fixed;
            top: 87px;
            right: -370px;
            width: 350px;
            height: calc(94vh - 40px);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .unassigned-section.visible {
            right: 20px;
        }

        .unassigned-toggle-btn {
            position: fixed;
            top: 100px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            z-index: 1001;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unassigned-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .unassigned-toggle-btn.active {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            right: 390px;
        }

        .unassigned-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f39c12;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header h2 {
            font-size: 1.5em;
            opacity: 0.9;
        }

        .unassigned-header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .unassigned-header h3 {
            font-size: 1.3em;
        }

        .room-layout {
            padding: 30px;
            background: #f8f9fa;
        }

        .room-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .benches-container {
            display: grid;
            grid-template-columns: 1fr 100px 1fr;
            gap: 20px;
            position: relative;
            min-width: 800px;
        }

        .bench-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .aisle {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #7f8c8d;
            font-weight: bold;
            writing-mode: vertical-lr;
            text-orientation: mixed;
            background: linear-gradient(180deg, #ecf0f1 0%, #bdc3c7 100%);
            border-radius: 10px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .bench {
            background: linear-gradient(135deg, #ffffff 0%, #f1f2f6 100%);
            border: 2px solid #e1e8ed;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
        }

        .bench:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .bench-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            font-weight: bold;
            color: #2c3e50;
        }

        .students-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            min-height: 80px;
            padding: 10px;
            border: 2px dashed transparent;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .students-row.drag-over {
            border-color: #3498db;
            background: rgba(52, 152, 219, 0.1);
        }

        .student-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            cursor: move;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            gap: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            user-select: none;
            width: calc(33.333% - 10px);
            min-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border-color: #3498db;
        }

        .student-card.handicapped {
            border-color: #e74c3c;
            background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
        }

        .student-card.handicapped::after {
            content: '♿';
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 16px;
            color: #e74c3c;
        }

        .student-card.dragging {
            opacity: 0.5;
            transform: rotate(5deg) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3) !important;
            z-index: 1000;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .student-info {
            flex: 1;
            min-width: 0;
        }

        .student-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .student-details {
            font-size: 12px;
            color: #7f8c8d;
            line-height: 1.3;
        }

        .student-actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding-left: 5px;
        }

        .action-btn {
            width: 25px;
            height: 25px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .drag-handle {
            background: #95a5a6;
            color: white;
            cursor: grab;
        }

        .drag-handle:hover {
            background: #7f8c8d;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .sms-btn {
            background: #2ecc71;
            color: white;
        }

        .sms-btn:hover {
            background: #27ae60;
        }

        .notification-btn {
            background: #f39c12;
            color: white;
        }

        .notification-btn:hover {
            background: #e67e22;
        }

        .unassigned-students {
            padding: 20px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .unassigned-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .unassigned-card {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            border: 2px solid #e74c3c;
            border-radius: 12px;
            padding: 12px;
            cursor: move;
            transition: all 0.3s ease;
            display: flex;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            user-select: none;
        }

        .unassigned-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .unassigned-card.dragging {
            opacity: 0.5;
            transform: rotate(3deg) scale(1.05);
            z-index: 1000;
        }

        .unassigned-card .student-avatar {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        .unassigned-card .student-name {
            font-size: 13px;
        }

        .unassigned-card .student-details {
            font-size: 11px;
        }

        .controls {
            padding: 30px;
            background: #2c3e50;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .control-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .control-btn.sms-btn {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }

        .control-btn.notification-btn {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #2c3e50;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #ddd;
        }

        .legend-color.handicapped {
            background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
            border-color: #e74c3c;
        }

        .legend-color.normal {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-color: #e9ecef;
        }

        .legend-color.unassigned {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            border-color: #e74c3c;
        }

        @media (max-width: 1200px) {
            .unassigned-section {
                right: -100%;
                width: 90%;
                max-width: 400px;
            }
            
            .unassigned-section.visible {
                right: 5%;
            }
            
            .unassigned-toggle-btn.active {
                right: calc(95% - 60px);
            }
            
            .room-section.shrunk {
                margin-right: 0;
                transform: scale(0.95);
            }
            
            .unassigned-students {
                max-height: calc(100vh - 200px);
            }
            
            .unassigned-list {
                flex-direction: column;
            }
            
            .unassigned-card {
                flex: none;
                min-width: auto;
            }
        }

        @media (max-width: 768px) {
            .benches-container {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .aisle {
                writing-mode: horizontal-tb;
                text-orientation: mixed;
                height: 40px;
            }
            
            .controls {
                flex-direction: column;
                align-items: center;
            }
            
            .legend {
                flex-direction: column;
                gap: 15px;
            }
            
            .student-card {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Seat Plan!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Seat Plan</li>
                            <li class="breadcrumb-item text-muted" aria-current="page">Engineering Building</li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Room 101 - Computer Science</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
 <div class="main-container" id="main-container">
        <!-- Floating Toggle Button -->
        <button class="unassigned-toggle-btn" id="unassigned-toggle" onclick="toggleUnassignedPanel()">
            <i class="fas fa-users"></i>
            <span class="unassigned-count" id="unassigned-count">10</span>
        </button>

        <!-- Main Room Section -->
        <div class="room-section" id="room-section">
            <div class="header">
                <h1>Engineering Building</h1>
                <h2>Room 101 - Computer Science</h2>
            </div>

            <div class="room-layout">
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color normal"></div>
                        <span>Regular Student</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color handicapped"></div>
                        <span>♿ Special Case Student</span>
                    </div>
                    {{-- <div class="legend-item">
                        <div class="legend-color unassigned"></div>
                        <span>Unassigned Student</span>
                    </div> --}}
                    <div class="legend-item">
                        <i class="fas fa-arrows-alt" style="color: #95a5a6;"></i>
                        <span>Drag to move</span>
                    </div>
                </div>

                <div class="room-container">
                    <div class="benches-container">
                        <div class="bench-column" id="left-column">
                            <!-- Left side benches will be generated here -->
                        </div>
                        <div class="aisle">
                            <span>AISLE</span>
                        </div>
                        <div class="bench-column" id="right-column">
                            <!-- Right side benches will be generated here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="controls">
                <button class="control-btn sms-btn" onclick="sendBulkSMS()">
                    <i class="fas fa-sms"></i>
                    Send SMS to All
                </button>
                <button class="control-btn notification-btn" onclick="sendBulkNotification()">
                    <i class="fas fa-bell"></i>
                    Send Notification to All
                </button>
            </div>
        </div>

        <!-- Unassigned Students Section -->
        <div class="unassigned-section" id="unassigned-section">
            <div class="unassigned-header">
                <h3><i class="fas fa-users"></i> Unassigned Students</h3>
                <p>Drag students to assign seats</p>
            </div>
            <div class="unassigned-students">
                <div class="unassigned-list" id="unassigned-list">
                    <!-- Unassigned students will be generated here -->
                </div>
            </div>
        </div>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script>
        // All student data - some assigned, some unassigned
        const allStudents = [
            { id: 1, name: 'Ram Kumar', class: 'CSE-A', roll: 'CS001', handicapped: false, assigned: true },
            { id: 2, name: 'Sita Sharma', class: 'CSE-A', roll: 'CS002', handicapped: true, assigned: true },
            { id: 3, name: 'Hari Bahadur', class: 'CSE-A', roll: 'CS003', handicapped: false, assigned: true },
            { id: 4, name: 'Gita Patel', class: 'CSE-B', roll: 'CS004', handicapped: false, assigned: true },
            { id: 5, name: 'Shyam Thapa', class: 'CSE-B', roll: 'CS005', handicapped: false, assigned: true },
            { id: 6, name: 'Radha Gurung', class: 'CSE-B', roll: 'CS006', handicapped: true, assigned: true },
            { id: 7, name: 'Krishna Lama', class: 'CSE-C', roll: 'CS007', handicapped: false, assigned: true },
            { id: 8, name: 'Sarita Magar', class: 'CSE-C', roll: 'CS008', handicapped: false, assigned: true },
            { id: 9, name: 'Ravi Tamang', class: 'CSE-C', roll: 'CS009', handicapped: false, assigned: true },
            { id: 10, name: 'Mina Rai', class: 'CSE-D', roll: 'CS010', handicapped: false, assigned: true },
            { id: 11, name: 'Dipak Limbu', class: 'CSE-D', roll: 'CS011', handicapped: false, assigned: true },
            { id: 12, name: 'Kamala Subedi', class: 'CSE-D', roll: 'CS012', handicapped: true, assigned: true },
            { id: 13, name: 'Bikash Adhikari', class: 'CSE-E', roll: 'CS013', handicapped: false, assigned: true },
            { id: 14, name: 'Sunita Karki', class: 'CSE-E', roll: 'CS014', handicapped: false, assigned: true },
            { id: 15, name: 'Mohan Bastola', class: 'CSE-E', roll: 'CS015', handicapped: false, assigned: true },
            // Unassigned students
            { id: 16, name: 'Laxmi Thapa', class: 'CSE-F', roll: 'CS016', handicapped: false, assigned: false },
            { id: 17, name: 'Kiran Shrestha', class: 'CSE-F', roll: 'CS017', handicapped: false, assigned: false },
            { id: 18, name: 'Prema Ghimire', class: 'CSE-F', roll: 'CS018', handicapped: false, assigned: false },
            { id: 19, name: 'Anil Pokharel', class: 'CSE-G', roll: 'CS019', handicapped: false, assigned: false },
            { id: 20, name: 'Rita Bhattarai', class: 'CSE-G', roll: 'CS020', handicapped: true, assigned: false },
            { id: 21, name: 'Sunil Dhakal', class: 'CSE-G', roll: 'CS021', handicapped: false, assigned: false },
            { id: 22, name: 'Maya Joshi', class: 'CSE-H', roll: 'CS022', handicapped: false, assigned: false },
            { id: 23, name: 'Gopal Neupane', class: 'CSE-H', roll: 'CS023', handicapped: false, assigned: false },
            { id: 24, name: 'Sushma Gautam', class: 'CSE-H', roll: 'CS024', handicapped: false, assigned: false },
            { id: 25, name: 'Ramesh Khadka', class: 'CSE-I', roll: 'CS025', handicapped: false, assigned: false }
        ];

        let draggedElement = null;
        let draggedData = null;

        // Configure toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };

        function getStudentInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase();
        }

        function createStudentCard(student, isUnassigned = false) {
            // console.log('isUnassigned - '+isUnassigned )
            const handicappedClass = student.handicapped ? 'handicapped' : '';
            const cardClass = isUnassigned ? 'unassigned-card' : `student-card ${handicappedClass}`;
            
            return `
                <div class="${cardClass}" draggable="true" data-student-id="${student.id}">
                    <div class="student-avatar">${getStudentInitials(student.name)}</div>
                    <div class="student-info">
                        <div class="student-name">${student.name}</div>
                        <div class="student-details">
                            Class: ${student.class}<br>
                            Roll: ${student.roll}
                        </div>
                    </div>
                    ${!isUnassigned ? `
                    <div class="student-actions">
                        <button class="action-btn drag-handle" title="Drag to move">
                            <i class="fas fa-arrows-alt"></i>
                        </button>
                        <button class="action-btn sms-btn" title="Send SMS" onclick="sendIndividualSMS(${student.id})">
                            <i class="fas fa-envelope"></i>
                        </button>
                        <button class="action-btn notification-btn" title="Send Notification" onclick="sendIndividualNotification(${student.id})">
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        function createBench(benchNumber, studentsInBench) {
            const studentsHTML = studentsInBench.map(s => createStudentCard(s, false)).join('');
            return `
                <div class="bench" data-bench="${benchNumber}">
                    <div class="bench-header">Bench ${benchNumber}</div>
                    <div class="students-row" data-bench="${benchNumber}">
                        ${studentsHTML}
                    </div>
                </div>
            `;
        }

        function initializeRoom() {
            const leftColumn = $('#left-column');
            const rightColumn = $('#right-column');
            const assignedStudents = allStudents.filter(s => s.assigned);
            
            // Create benches for left side (benches 1-5)
            for (let i = 1; i <= 5; i++) {
                const startIndex = (i - 1) * 3;
                const benchStudents = assignedStudents.slice(startIndex, Math.min(startIndex + 3, assignedStudents.length));
                leftColumn.append(createBench(i, benchStudents));
            }
            
            // Create benches for right side (benches 6-10)
            for (let i = 6; i <= 10; i++) {
                const startIndex = (i - 1) * 3;
                const benchStudents = assignedStudents.slice(startIndex, Math.min(startIndex + 3, assignedStudents.length));
                rightColumn.append(createBench(i, benchStudents));
            }

            // Create unassigned students list
            const unassignedList = $('#unassigned-list');
            const unassignedStudents = allStudents.filter(s => !s.assigned);
            unassignedStudents.forEach(student => {
                unassignedList.append(createStudentCard(student, true));
            });
        }

        function initializeDragAndDrop() {
            // console.log('Initializing drag and drop...');

            // Handle drag start
            $(document).on('dragstart', '.student-card, .unassigned-card', function(e) {
                draggedElement = this;
                draggedData = {
                    id: $(this).data('student-id'),
                    isUnassigned: $(this).hasClass('unassigned-card')
                };
                
                $(this).addClass('dragging');
                console.log('Drag started for student:', draggedData.id);
                
                e.originalEvent.dataTransfer.effectAllowed = 'move';
                e.originalEvent.dataTransfer.setData('text/plain', draggedData.id);
            });

            // Handle drag end
            $(document).on('dragend', '.student-card, .unassigned-card', function(e) {
                $(this).removeClass('dragging');
                $('.drag-over').removeClass('drag-over');
                console.log('Drag ended');
            });

            // Handle drag over
            $(document).on('dragover', '.students-row, .student-card, .unassigned-list', function(e) {
                e.preventDefault();
                e.originalEvent.dataTransfer.dropEffect = 'move';
                $(this).addClass('drag-over');
            });

            // Handle drag leave
            $(document).on('dragleave', '.students-row, .student-card, .unassigned-list', function(e) {
                // console.log('object')
                // console.log($(this))
                $(this).removeClass('drag-over');
            });

            // Handle drop
            $(document).on('drop', '.students-row, .student-card, .unassigned-list', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $dropTarget = $(this);
                const $closestStudentsRow = $(e.target).closest('.students-row');
                // console.log($dropTarget)
                $dropTarget.removeClass('drag-over');
                $closestStudentsRow.removeClass('drag-over');
                
                if (!draggedElement || !draggedData) return;
                
                const draggedStudent = allStudents.find(s => s.id === draggedData.id);
                if (!draggedStudent) return;

                if ($dropTarget.hasClass('students-row')) {
                    // Dropped on a bench row
                    // console.log('1');
                    // console.log($dropTarget)
                    // console.log(draggedStudent)
                    handleDropOnBench($dropTarget, draggedStudent);
                } else if ($dropTarget.hasClass('student-card')) {
                    // Dropped on another student - swap positions
                    // console.log('here');
                    // console.log($dropTarget)
                    // console.log(draggedStudent)
                    handleSwapStudents($dropTarget, draggedStudent);
                } else if ($dropTarget.hasClass('unassigned-list')) {
                    // Dropped back to unassigned list
                    // console.log('unassigned dropped');
                    handleDropToUnassigned(draggedStudent);
                }
                    // $dropTarget.removeClass('drag-over');

                // Clean up
                draggedElement = null;
                draggedData = null;
            });
        }

        function handleDropOnBench($benchRow, draggedStudent) {
            console.log('Dropping on bench:', $benchRow.data('bench'));
            
            // Create new student card for the bench
            const newCard = createStudentCard(draggedStudent, false);
            $benchRow.append(newCard);
            
            // Remove from original location
            $(draggedElement).remove();
            
            // Update student status
            draggedStudent.assigned = true;
            
            toastr.success(`${draggedStudent.name} assigned to Bench ${$benchRow.data('bench')}`);
        }

       
        function handleSwapStudents($targetCard, draggedStudent) {
            const targetStudentId = $targetCard.data('student-id');
            const targetStudent = allStudents.find(s => s.id === targetStudentId);
            
            if (!targetStudent || draggedStudent.id === targetStudent.id) return;
            
            console.log(`Swapping ${draggedStudent.name} with ${targetStudent.name}`);
            
            // Get parent containers
            const $draggedParent = $(draggedElement).parent();
            const $targetParent = $targetCard.parent();
            
            // Create replacement cards
            const draggedReplacement = draggedData.isUnassigned ? 
                createStudentCard(draggedStudent, false) : 
                createStudentCard(draggedStudent, false);
                // console.log(draggedReplacement)
            const targetReplacement = createStudentCard(targetStudent, true);
            
                // console.log(targetReplacement)
            // Replace elements
            $(draggedElement).replaceWith(targetReplacement);
            $targetCard.replaceWith(draggedReplacement);
            // console.log(draggedData)
            // Update assignment status
            if (draggedData.isUnassigned) {
                draggedStudent.assigned = true;
                targetStudent.assigned = false;
                // console.log('123')
                
                // Add target student to unassigned list
                const unassignedCard = createStudentCard(targetStudent, true);
                $('#unassigned-list').append(unassignedCard);
            }
            // const unassignedCard = createStudentCard(draggedStudent, true);
            // $('#unassigned-list').append(unassignedCard);
            
            // Update count
            updateUnassignedCount();
            
            toastr.success(`${targetStudent.name} is replaced by ${draggedStudent.name}`);
        }

        function updateUnassignedCount() {
            const unassignedStudents = allStudents.filter(s => !s.assigned);
            $('#unassigned-count').text(unassignedStudents.length);
            
            // Hide count if no unassigned students
            if (unassignedStudents.length === 0) {
                $('#unassigned-count').hide();
            } else {
                $('#unassigned-count').show();
            }
        }


        function toggleUnassignedPanel() {
            const panel = $('#unassigned-section');
            const button = $('#unassigned-toggle');
            const roomSection = $('#room-section');
            const mainContainer = $('#main-container');
            
            if (panel.hasClass('visible')) {
                // Hide panel
                panel.removeClass('visible');
                button.removeClass('active');
                roomSection.removeClass('shrunk');
                mainContainer.removeClass('unassigned-visible');
                console.log('Unassigned panel hidden');
            } else {
                // Show panel
                panel.addClass('visible');
                button.addClass('active');
                roomSection.addClass('shrunk');
                mainContainer.addClass('unassigned-visible');
                // console.log('Unassigned panel shown');
            }
        }
        function handleDropToUnassigned(draggedStudent) {
            console.log('Dropping to unassigned:', draggedStudent.name);
            
            // Create unassigned card
            const unassignedCard = createStudentCard(draggedStudent, true);
            $('#unassigned-list').append(unassignedCard);
            
            // Remove from original location
            $(draggedElement).remove();
            
            // Update student status
            draggedStudent.assigned = false;
            
            toastr.info(`${draggedStudent.name} moved to unassigned list`);
            
        }

        function sendIndividualSMS(studentId) {
            const student = allStudents.find(s => s.id === studentId);
            if (student) {
                swal.fire({
                    title: "Send SMS",
                    text: `Send SMS to ${student.name}?`,
                    icon: "info",
                    buttons: ["Cancel", "Send"],
                    dangerMode: false,
                }).then((willSend) => {
                    if (willSend) {
                        setTimeout(() => {
                            if (Math.random() > 0.2) {
                                toastr.success(`SMS sent successfully to ${student.name}`);
                            } else {
                                toastr.error(`Failed to send SMS to ${student.name}`);
                            }
                        }, 1000);
                    }
                });
            }
        }

        function sendIndividualNotification(studentId) {
            const student = allStudents.find(s => s.id === studentId);
            if (student) {
                swal.fire({
                    title: "Send Notification",
                    text: `Send notification to ${student.name}?`,
                    icon: "info",
                    buttons: ["Cancel", "Send"],
                    dangerMode: false,
                }).then((willSend) => {
                    if (willSend) {
                        setTimeout(() => {
                            if (Math.random() > 0.2) {
                                toastr.success(`Notification sent successfully to ${student.name}`);
                            } else {
                                toastr.error(`Failed to send notification to ${student.name}`);
                            }
                        }, 1000);
                    }
                });
            }
        }

        function sendBulkSMS() {
            const assignedStudents = allStudents.filter(s => s.assigned);
            swal.fire({
                title: "Send SMS to All Assigned Students",
                text: `This will send SMS to ${assignedStudents.length} assigned students. Continue?`,
                icon: "warning",
                buttons: ["Cancel", "Send All"],
                dangerMode: false,
            }).then((willSend) => {
                if (willSend) {
                    setTimeout(() => {
                        const successCount = Math.floor(Math.random() * 3) + assignedStudents.length - 2;
                        const failCount = assignedStudents.length - successCount;
                        
                        if (failCount === 0) {
                            toastr.success(`SMS sent successfully to all ${successCount} assigned students`);
                        } else {
                            toastr.warning(`SMS sent to ${successCount} students, ${failCount} failed`);
                        }
                    }, 2000);
                }
            });
        }

        function sendBulkNotification() {
            const assignedStudents = allStudents.filter(s => s.assigned);
            swal.fire({
                title: "Send Notification to All Assigned Students",
                text: `This will send notifications to ${assignedStudents.length} assigned students. Continue?`,
                icon: "warning",
                buttons: ["Cancel", "Send All"],
                dangerMode: false,
            }).then((willSend) => {
                if (willSend) {
                    setTimeout(() => {
                        const successCount = Math.floor(Math.random() * 2) + assignedStudents.length - 1;
                        const failCount = assignedStudents.length - successCount;
                        
                        if (failCount === 0) {
                            toastr.success(`Notifications sent successfully to all ${successCount} assigned students`);
                        } else {
                            toastr.warning(`Notifications sent to ${successCount} students, ${failCount} failed`);
                        }
                    }, 2000);
                }
            });
        }

        // Initialize everything when document is ready
        $(document).ready(function() {
            // console.log('Document ready');
            // console.log('jQuery version:', $.fn.jquery);
            
            initializeRoom();
            
            setTimeout(function() {
                // console.log('Initializing drag and drop...');
                initializeDragAndDrop();
                // console.log('Drag and drop initialized');
            }, 300);
        });
    </script>
@endsection