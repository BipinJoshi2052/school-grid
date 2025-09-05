@extends('layouts.admin')

@section('title')
    Seat Plan
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/ui-lightness/jquery-ui.min.css">
    <style>
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,60 0,100"/></svg>');
            background-size: cover;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .header h2 {
            font-size: 1.5em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .room-layout {
            padding: 40px;
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

        .room-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(52, 152, 219, 0.03) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .benches-container {
            display: grid;
            grid-template-columns: 1fr 100px 1fr;
            gap: 20px;
            position: relative;
            z-index: 1;
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
            justify-content: space-between;
        }

        .student-card {
            flex: 1;
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

        .student-card.handicapped::before {
            content: '♿';
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 16px;
            color: #e74c3c;
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
        }

        .drag-handle:hover {
            background: #7f8c8d;
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

        .ui-sortable-helper {
            transform: rotate(5deg);
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }

        .dragging {
            transform: rotate(5deg);
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }

        .ui-sortable-placeholder {
            background: #e8f4f8;
            border: 2px dashed #3498db;
            border-radius: 12px;
            margin: 5px;
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
        #right-column{
            padding-right: 30px;
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
            
            .students-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .student-card {
                min-height: 80px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 2em;
            }
            
            .room-layout {
                padding: 20px;
            }
            
            .room-container {
                padding: 20px;
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
                        {{-- <div class="container"> --}}
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
                                        <span>♿ Handicapped Student</span>
                                    </div>
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
                                    <i class="fas fa-envelope"></i>
                                    Send SMS to All
                                </button>
                                <button class="control-btn notification-btn" onclick="sendBulkNotification()">
                                    <i class="fas fa-bell"></i>
                                    Send Notification to All
                                </button>
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
  <div class="row">
    <div class="column">
      <ul class="connected-sortable droppable-area1">
        <li class="draggable-item">Item 1</li>
        <li class="draggable-item">Item 2</li>
        <li class="draggable-item">Item 3</li>
        <li class="draggable-item">Item 4</li>
        <li class="draggable-item">Item 5</li>
        <li class="draggable-item">Item 6</li>
        <li class="draggable-item">Item 7</li>
      </ul>
    </div>
    
    <div class="column">
      <ul class="connected-sortable droppable-area2">
        <li class="draggable-item">Item 8</li>
        <li class="draggable-item">Item 9</li>
        <li class="draggable-item">Item 10</li>
        <li class="draggable-item">Item 11</li>
        <li class="draggable-item">Item 12</li>
        <li class="draggable-item">Item 13</li>
        <li class="draggable-item">Item 14</li>
      </ul>
    </div>
  </div>
</div>
@endsection

@section('scripts')  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $( init );

function init() {
  $( ".droppable-area1, .droppable-area2" ).sortable({
      connectWith: ".connected-sortable",
      stack: '.connected-sortable ul'
    }).disableSelection();
}
        // Sample student data
        const students = [
            { id: 1, name: 'Ram Kumar', class: 'CSE-A', roll: 'CS001', handicapped: false },
            { id: 2, name: 'Sita Sharma', class: 'CSE-A', roll: 'CS002', handicapped: true },
            { id: 3, name: 'Hari Bahadur', class: 'CSE-A', roll: 'CS003', handicapped: false },
            { id: 4, name: 'Gita Patel', class: 'CSE-B', roll: 'CS004', handicapped: false },
            { id: 5, name: 'Shyam Thapa', class: 'CSE-B', roll: 'CS005', handicapped: false },
            { id: 6, name: 'Radha Gurung', class: 'CSE-B', roll: 'CS006', handicapped: true },
            { id: 7, name: 'Krishna Lama', class: 'CSE-C', roll: 'CS007', handicapped: false },
            { id: 8, name: 'Sarita Magar', class: 'CSE-C', roll: 'CS008', handicapped: false },
            { id: 9, name: 'Ravi Tamang', class: 'CSE-C', roll: 'CS009', handicapped: false },
            { id: 10, name: 'Mina Rai', class: 'CSE-D', roll: 'CS010', handicapped: false },
            { id: 11, name: 'Dipak Limbu', class: 'CSE-D', roll: 'CS011', handicapped: false },
            { id: 12, name: 'Kamala Subedi', class: 'CSE-D', roll: 'CS012', handicapped: true },
            { id: 13, name: 'Bikash Adhikari', class: 'CSE-E', roll: 'CS013', handicapped: false },
            { id: 14, name: 'Sunita Karki', class: 'CSE-E', roll: 'CS014', handicapped: false },
            { id: 15, name: 'Mohan Bastola', class: 'CSE-E', roll: 'CS015', handicapped: false },
            { id: 16, name: 'Laxmi Thapa', class: 'CSE-F', roll: 'CS016', handicapped: false },
            { id: 17, name: 'Kiran Shrestha', class: 'CSE-F', roll: 'CS017', handicapped: false },
            { id: 18, name: 'Prema Ghimire', class: 'CSE-F', roll: 'CS018', handicapped: false },
            { id: 19, name: 'Anil Pokharel', class: 'CSE-G', roll: 'CS019', handicapped: false },
            { id: 20, name: 'Rita Bhattarai', class: 'CSE-G', roll: 'CS020', handicapped: false },
            { id: 21, name: 'Sunil Dhakal', class: 'CSE-G', roll: 'CS021', handicapped: false },
            { id: 22, name: 'Maya Joshi', class: 'CSE-H', roll: 'CS022', handicapped: false },
            { id: 23, name: 'Gopal Neupane', class: 'CSE-H', roll: 'CS023', handicapped: false },
            { id: 24, name: 'Sushma Gautam', class: 'CSE-H', roll: 'CS024', handicapped: false },
            { id: 25, name: 'Ramesh Khadka', class: 'CSE-I', roll: 'CS025', handicapped: false },
            { id: 26, name: 'Bindu Parajuli', class: 'CSE-I', roll: 'CS026', handicapped: false },
            { id: 27, name: 'Nabin Maharjan', class: 'CSE-I', roll: 'CS027', handicapped: false },
            { id: 28, name: 'Sabina Manandhar', class: 'CSE-J', roll: 'CS028', handicapped: false },
            { id: 29, name: 'Prakash Silwal', class: 'CSE-J', roll: 'CS029', handicapped: false },
            { id: 30, name: 'Anita Pandey', class: 'CSE-J', roll: 'CS030', handicapped: false }
        ];

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

        function createStudentCard(student) {
            const handicappedClass = student.handicapped ? 'handicapped' : '';
            return `
                <div class="student-card ${handicappedClass}" data-student-id="${student.id}">
                    <div class="student-avatar">${getStudentInitials(student.name)}</div>
                    <div class="student-info">
                        <div class="student-name">${student.name}</div>
                        <div class="student-details">
                            Class: ${student.class}<br>
                            Roll: ${student.roll}
                        </div>
                    </div>
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
                </div>
            `;
        }

        function createBench(benchNumber, studentsInBench) {
            const studentsHTML = studentsInBench.map(createStudentCard).join('');
            return `
                <div class="bench" data-bench="${benchNumber}">
                    <div class="bench-header">Bench ${benchNumber}</div>
                    <div class="students-row">
                        ${studentsHTML}
                    </div>
                </div>
            `;
        }

        function initializeRoom() {
            const leftColumn = $('#left-column');
            const rightColumn = $('#right-column');
            
            // Create benches for left side (benches 1-5)
            for (let i = 1; i <= 5; i++) {
                const startIndex = (i - 1) * 3;
                const benchStudents = students.slice(startIndex, startIndex + 3);
                leftColumn.append(createBench(i, benchStudents));
            }
            
            // Create benches for right side (benches 6-10)
            for (let i = 6; i <= 10; i++) {
                const startIndex = (i - 1) * 3;
                const benchStudents = students.slice(startIndex, startIndex + 3);
                rightColumn.append(createBench(i, benchStudents));
            }
        }
        
        function initializeDragAndDrop() {
  $( ".droppable-area1, .droppable-area2" ).sortable({
      connectWith: ".connected-sortable",
      stack: '.connected-sortable ul'
    }).disableSelection();

            // console.log('object')
            // console.log($.fn.jquery);  // Should print the jQuery version number
            // console.log($.ui.version);  // Should print the jQuery UI version num
            $('.students-row').sortable({
                items: '.student-card',
                handle: '.drag-handle',
                connectWith: '.students-row',
                placeholder: 'ui-sortable-placeholder',
                tolerance: 'pointer',
                cursor: 'move',
                helper: 'clone',
                opacity: 0.8,
                scroll: true,
                scrollSensitivity: 100,
                scrollSpeed: 20,
                stack: '.ui-sortable div',
                start: function(event, ui) {
                    // console.log('22')
                    ui.placeholder.height(ui.item.height());
                    ui.helper.addClass('dragging');
                },
                stop: function(event, ui) {
                    // console.log('4')
                    ui.item.removeClass('dragging');
                },
                update: function(event, ui) {
                    // console.log('2')
                    const movedStudent = ui.item;
                    const studentId = movedStudent.data('student-id');
                    const student = students.find(s => s.id === studentId);
                    
                    if (student && ui.sender) {
                        // Get the student that was replaced (if any)
                        const targetRow = $(this);
                        const allStudentsInRow = targetRow.find('.student-card');
                        
                        if (allStudentsInRow.length > 1) {
                            // Find the student that was displaced
                            const otherStudent = allStudentsInRow.not(movedStudent).first();
                            if (otherStudent.length) {
                                const otherStudentId = otherStudent.data('student-id');
                                const otherStudentData = students.find(s => s.id === otherStudentId);
                                
                                if (otherStudentData) {
                                    toastr.success(`${otherStudentData.name} is replaced by ${student.name}`);
                                }
                            }
                        } else {
                            toastr.info(`${student.name} moved to new position`);
                        }
                    }
                }
            }).disableSelection();
            
            // Make individual student cards draggable within their row
            $('.student-card').draggable({
                handle: '.drag-handle',
                cursor: 'move',
                opacity: 0.8,
                helper: 'clone',
                connectToSortable: '.students-row',
                scroll: true,
                scrollSensitivity: 100,
                scrollSpeed: 20,
                start: function(event, ui) {
                    ui.helper.addClass('dragging');
                },
                stop: function(event, ui) {
                    // This will be handled by sortable
                }
            }).disableSelection();
        }

        function sendIndividualSMS(studentId) {
            const student = students.find(s => s.id === studentId);
            if (student) {
                Swal.fire({
                    title: "Send SMS",
                    text: `Send SMS to ${student.name}?`,
                    icon: "info",
                    buttons: ["Cancel", "Send"],
                    dangerMode: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Simulate SMS sending
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
            const student = students.find(s => s.id === studentId);
            if (student) {
                Swal.fire({
                    title: "Send Notification",
                    text: `Send notification to ${student.name}?`,
                    icon: "info",
                    buttons: ["Cancel", "Send"],
                    dangerMode: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Simulate notification sending
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
            Swal.fire({
                title: "Send SMS to All Students",
                text: "This will send SMS to all 30 students in the room. Continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Send All",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulate bulk SMS sending
                    setTimeout(() => {
                        const successCount = Math.floor(Math.random() * 5) + 26; // 26-30 success
                        const failCount = 30 - successCount;

                        if (failCount === 0) {
                            toastr.success(`SMS sent successfully to all ${successCount} students`);
                        } else {
                            toastr.warning(`SMS sent to ${successCount} students, ${failCount} failed`);
                        }
                    }, 2000);
                }
            });
        }

        function sendBulkNotification() {
            Swal.fire({
                title: "Send Notification to All Students",
                text: "This will send notifications to all 30 students in the room. Continue?",
                icon: "warning",
                buttons: ["Cancel", "Send All"],
                dangerMode: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulate bulk notification sending
                    setTimeout(() => {
                        const successCount = Math.floor(Math.random() * 3) + 28; // 28-30 success
                        const failCount = 30 - successCount;
                        
                        if (failCount === 0) {
                            toastr.success(`Notifications sent successfully to all ${successCount} students`);
                        } else {
                            toastr.warning(`Notifications sent to ${successCount} students, ${failCount} failed`);
                        }
                    }, 2000);
                }
            });
        }

        // Initialize everything when document is ready
        $(document).ready(function() {
            initializeRoom();
            // Add a small delay to ensure DOM is fully ready
            setTimeout(function() {
                initializeDragAndDrop();
            }, 1000);
        });
    </script>
@endsection