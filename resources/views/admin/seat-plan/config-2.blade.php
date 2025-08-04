@extends('layouts.admin')

@section('title')
Seat Plan Config
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
            font-weight: 300;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .progress-bar {
            height: 4px;
            background: rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #3498db;
            width: 20%;
            transition: all 0.5s ease;
        }

        .pattern-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .pattern-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pattern-card:hover {
            border-color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .pattern-card.selected {
            border-color: #3498db;
            background: #f0f8ff;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.2);
        }

        .pattern-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .pattern-radio {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
        }

        .pattern-title {
            font-weight: 600;
            font-size: 1.3rem;
            color: #2c3e50;
            cursor: pointer;
        }

        .pattern-description {
            color: #666;
            margin-bottom: 20px;
            font-size: 1rem;
            line-height: 1.5;
        }

        .pattern-visual {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }

        .seat-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .seat-row:last-child {
            margin-bottom: 0;
        }

        .seat {
            width: 50px;
            height: 35px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            background: #95a5a6;
            position: relative;
        }

        .seat.cs {
            background: #3498db;
        }

        .seat.math {
            background: #e74c3c;
        }

        .seat.physics {
            background: #27ae60;
        }

        .seat.special {
            background: #f39c12;
            border: 2px solid #e67e22;
        }

        .pattern-config {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-top: 15px;
        }

        .config-group {
            margin-bottom: 15px;
        }

        .config-group:last-child {
            margin-bottom: 0;
        }

        .config-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .config-select, .config-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .config-select:focus, .config-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .special-needs-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }

        .special-needs-card {
            background: #fff8dc;
            border: 2px solid #f39c12;
            border-radius: 12px;
            padding: 25px;
        }

        .special-needs-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .special-needs-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #e67e22;
            cursor: pointer;
            margin-left: 10px;
        }

        .special-needs-description {
            color: #d68910;
            margin-bottom: 20px;
            font-size: 1rem;
            line-height: 1.5;
        }

        .special-needs-visual {
            background: #fef9e7;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #f4d03f;
        }

        .content {
            padding: 40px;
        }

        .step {
            margin-bottom: 40px;
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 5px solid #3498db;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }

        .step-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .collapsed {
            opacity: 0.5;
            pointer-events: none;
            max-height: 80px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .active {
            opacity: 1;
            pointer-events: auto;
            max-height: none;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .title-input {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        .title-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            /* padding: 20px; */
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            border-color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card.selected {
            border-color: #3498db;
            background: #f0f8ff;
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .checkbox {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #2c3e50;
        }

        .card-content {
            margin-left: 32px;
        }

        .room-item, .section-item, .staff-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .room-item:last-child, .section-item:last-child, .staff-item:last-child {
            border-bottom: none;
        }

        .room-info, .staff-info {
            margin-left: 8px;
            font-size: 0.9rem;
            color: #666;
        }

        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 15px;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-generate {
            background: linear-gradient(135deg, #27ae60 0%, #219a52 100%);
            font-size: 1.2rem;
            padding: 18px 40px;
        }

        .btn-generate:hover:not(:disabled) {
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            margin-right: auto;
            margin-left: auto;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-message {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .loading-progress {
            width: 100%;
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 20px;
        }

        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2980b9);
            width: 0%;
            transition: width 1s ease;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: 1.1rem;
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .content {
                padding: 20px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .pattern-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .step-header {
                padding: 15px;
            }

            .btn {
                padding: 12px 24px;
                font-size: 1rem;
            }

            .seat {
                width: 40px;
                height: 30px;
                font-size: 0.7rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Seat Plan Config!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Seat Plan Config</li>
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
                        <div class="header">
                            <h1>Seat Plan Generator</h1>
                            <p>Create comprehensive examination seat plans with ease</p>
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressBar"></div>
                            </div>
                        </div>
                        <div class="content">
                            <!-- Step 1: Title and Building Selection -->
                            <div class="step active" id="step1">
                                <div class="step-header">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Title & Building Selection</div>
                                </div>
                                
                                <input type="text" class="title-input" id="seatPlanTitle" placeholder="Enter seat plan title..." required>
                                
                                <div class="grid" id="buildingsGrid">
                                    <!-- Sample buildings data -->
                                    <div class="card" data-building="building1">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox building-checkbox" id="building1">
                                            <label for="building1" class="card-title">Science Block</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room1_1" data-building="building1">
                                                <label for="room1_1">Room S101</label>
                                                <span class="room-info">(15 benches, 30 seats)</span>
                                            </div>
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room1_2" data-building="building1">
                                                <label for="room1_2">Room S102</label>
                                                <span class="room-info">(20 benches, 40 seats)</span>
                                            </div>
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room1_3" data-building="building1">
                                                <label for="room1_3">Room S103</label>
                                                <span class="room-info">(12 benches, 24 seats)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" data-building="building2">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox building-checkbox" id="building2">
                                            <label for="building2" class="card-title">Arts Block</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room2_1" data-building="building2">
                                                <label for="room2_1">Room A201</label>
                                                <span class="room-info">(18 benches, 36 seats)</span>
                                            </div>
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room2_2" data-building="building2">
                                                <label for="room2_2">Room A202</label>
                                                <span class="room-info">(25 benches, 50 seats)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" data-building="building3">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox building-checkbox" id="building3">
                                            <label for="building3" class="card-title">Main Hall</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room3_1" data-building="building3">
                                                <label for="room3_1">Auditorium</label>
                                                <span class="room-info">(50 benches, 100 seats)</span>
                                            </div>
                                            <div class="room-item">
                                                <input type="checkbox" class="checkbox room-checkbox" id="room3_2" data-building="building3">
                                                <label for="room3_2">Conference Hall</label>
                                                <span class="room-info">(30 benches, 60 seats)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="btn" id="nextStep1" disabled>Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Class and Section Selection -->
                            <div class="step collapsed" id="step2">
                                <div class="step-header">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Class & Section Selection</div>
                                </div>
                                
                                <div class="grid" id="classesGrid">
                                    <div class="card" data-class="class1">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox class-checkbox" id="class1">
                                            <label for="class1" class="card-title">Computer Science</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section1_1" data-class="class1">
                                                <label for="section1_1">Section A (35 students)</label>
                                            </div>
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section1_2" data-class="class1">
                                                <label for="section1_2">Section B (32 students)</label>
                                            </div>
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section1_3" data-class="class1">
                                                <label for="section1_3">Section C (28 students)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" data-class="class2">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox class-checkbox" id="class2">
                                            <label for="class2" class="card-title">Mathematics</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section2_1" data-class="class2">
                                                <label for="section2_1">Section A (40 students)</label>
                                            </div>
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section2_2" data-class="class2">
                                                <label for="section2_2">Section B (38 students)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" data-class="class3">
                                        <div class="card-header">
                                            <input type="checkbox" class="checkbox class-checkbox" id="class3">
                                            <label for="class3" class="card-title">Physics</label>
                                        </div>
                                        <div class="card-content">
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section3_1" data-class="class3">
                                                <label for="section3_1">Section A (30 students)</label>
                                            </div>
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section3_2" data-class="class3">
                                                <label for="section3_2">Section B (25 students)</label>
                                            </div>
                                            <div class="section-item">
                                                <input type="checkbox" class="checkbox section-checkbox" id="section3_3" data-class="class3">
                                                <label for="section3_3">Section C (33 students)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="btn" id="backStep2">Back</button>
                                    <button class="btn" id="nextStep2" disabled>Next</button>
                                </div>
                            </div>

                            <!-- Step 3: Staff Selection -->
                            <div class="step collapsed" id="step3">
                                <div class="step-header">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Invigilator Selection</div>
                                </div>
                                
                                <div class="grid" id="staffGrid">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff1" checked>
                                                <label for="staff1">Dr. John Smith</label>
                                                <span class="staff-info">(Computer Science)</span>
                                            </div>
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff2" checked>
                                                <label for="staff2">Prof. Sarah Johnson</label>
                                                <span class="staff-info">(Mathematics)</span>
                                            </div>
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff3" checked>
                                                <label for="staff3">Dr. Michael Brown</label>
                                                <span class="staff-info">(Physics)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-content">
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff4" checked>
                                                <label for="staff4">Ms. Emily Davis</label>
                                                <span class="staff-info">(Chemistry)</span>
                                            </div>
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff5" checked>
                                                <label for="staff5">Dr. Robert Wilson</label>
                                                <span class="staff-info">(Biology)</span>
                                            </div>
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff6" checked>
                                                <label for="staff6">Prof. Lisa Anderson</label>
                                                <span class="staff-info">(English)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-content">
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff7" checked>
                                                <label for="staff7">Dr. David Miller</label>
                                                <span class="staff-info">(History)</span>
                                            </div>
                                            <div class="staff-item">
                                                <input type="checkbox" class="checkbox staff-checkbox" id="staff8" checked>
                                                <label for="staff8">Ms. Jennifer Garcia</label>
                                                <span class="staff-info">(Geography)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="btn" id="backStep3">Back</button>
                                    <button class="btn" id="nextStep3" disabled>Next</button>
                                </div>
                            </div>

                            <!-- Step 4: Seating Pattern Selection -->
                            <div class="step collapsed" id="step4">
                                <div class="step-header">
                                    <div class="step-number">4</div>
                                    <div class="step-title">Seating Pattern Selection</div>
                                </div>
                                
                                <div class="pattern-grid">
                                    <!-- Sequential Class Assignment -->
                                    <div class="pattern-card" data-pattern="sequential">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="sequential" class="pattern-radio">
                                            <label for="sequential" class="pattern-title">Sequential Class Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign students from the same class in consecutive order
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="seat-row">
                                                <div class="seat cs">CS1</div>
                                                <div class="seat cs">CS2</div>
                                                <div class="seat cs">CS3</div>
                                                <div class="seat cs">CS4</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat math">M1</div>
                                                <div class="seat math">M2</div>
                                                <div class="seat math">M3</div>
                                                <div class="seat math">M4</div>
                                            </div>
                                        </div>
                                        <div class="pattern-config" id="sequentialConfig" style="display: none;">
                                            <div class="config-group">
                                                <label>Starting Class:</label>
                                                <select class="config-select" id="sequentialClass">
                                                    <option value="">Select Class</option>
                                                    <option value="cs">Computer Science</option>
                                                    <option value="math">Mathematics</option>
                                                    <option value="physics">Physics</option>
                                                </select>
                                            </div>
                                            <div class="config-group">
                                                <label>Starting Roll Number:</label>
                                                <input type="number" class="config-input" id="sequentialRoll" placeholder="e.g., 1" min="1">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alternate Class Assignment -->
                                    <div class="pattern-card" data-pattern="alternate">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="alternate" class="pattern-radio">
                                            <label for="alternate" class="pattern-title">Alternate Class Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign students from different classes alternately
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="seat-row">
                                                <div class="seat cs">CS1</div>
                                                <div class="seat math">M1</div>
                                                <div class="seat physics">P1</div>
                                                <div class="seat cs">CS2</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat math">M2</div>
                                                <div class="seat physics">P2</div>
                                                <div class="seat cs">CS3</div>
                                                <div class="seat math">M3</div>
                                            </div>
                                        </div>
                                        <div class="pattern-config" id="alternateConfig" style="display: none;">
                                            <div class="config-group">
                                                <label>Starting Class:</label>
                                                <select class="config-select" id="alternateClass">
                                                    <option value="">Select Class</option>
                                                    <option value="cs">Computer Science</option>
                                                    <option value="math">Mathematics</option>
                                                    <option value="physics">Physics</option>
                                                </select>
                                            </div>
                                            <div class="config-group">
                                                <label>Starting Roll Number:</label>
                                                <input type="number" class="config-input" id="alternateRoll" placeholder="e.g., 1" min="1">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Class Assignment in Stages -->
                                    <div class="pattern-card" data-pattern="stages">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="stages" class="pattern-radio">
                                            <label for="stages" class="pattern-title">Class Assignment in Stages</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign students in stages with custom arrangement
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="seat-row">
                                                <div class="seat cs">CS1</div>
                                                <div class="seat cs">CS2</div>
                                                <div class="seat math">M1</div>
                                                <div class="seat math">M2</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat physics">P1</div>
                                                <div class="seat physics">P2</div>
                                                <div class="seat cs">CS3</div>
                                                <div class="seat cs">CS4</div>
                                            </div>
                                        </div>
                                        <div class="pattern-config" id="stagesConfig" style="display: none;">
                                            <div class="config-group">
                                                <label>Stage Assignment Type:</label>
                                                <select class="config-select" id="stageType">
                                                    <option value="">Select Type</option>
                                                    <option value="sequential">Sequential in Stages</option>
                                                    <option value="alternate">Alternate in Stages</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Random Student Assignment -->
                                    <div class="pattern-card" data-pattern="random">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="random" class="pattern-radio">
                                            <label for="random" class="pattern-title">Random Student Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Seats are filled randomly with students from all classes
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="seat-row">
                                                <div class="seat physics">P3</div>
                                                <div class="seat cs">CS7</div>
                                                <div class="seat math">M5</div>
                                                <div class="seat physics">P1</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat math">M2</div>
                                                <div class="seat cs">CS3</div>
                                                <div class="seat physics">P8</div>
                                                <div class="seat math">M9</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Class-Based Row Assignment -->
                                    <div class="pattern-card" data-pattern="rowbased">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="rowbased" class="pattern-radio">
                                            <label for="rowbased" class="pattern-title">Class-Based Row Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign entire rows to specific classes
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="seat-row">
                                                <div class="seat cs">CS1</div>
                                                <div class="seat cs">CS2</div>
                                                <div class="seat cs">CS3</div>
                                                <div class="seat cs">CS4</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat math">M1</div>
                                                <div class="seat math">M2</div>
                                                <div class="seat math">M3</div>
                                                <div class="seat math">M4</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat physics">P1</div>
                                                <div class="seat physics">P2</div>
                                                <div class="seat physics">P3</div>
                                                <div class="seat physics">P4</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Special Needs Seating -->
                                <div class="special-needs-section">
                                    <div class="special-needs-card">
                                        <div class="special-needs-header">
                                            <input type="checkbox" id="specialNeeds" class="checkbox">
                                            <label for="specialNeeds" class="special-needs-title">Special Needs Seating</label>
                                        </div>
                                        <div class="special-needs-description">
                                            Handicapped students will be seated at bench edges (rightmost in Row 1, leftmost in Row 2, alternating pattern)
                                        </div>
                                        <div class="special-needs-visual">
                                            <div class="seat-row">
                                                <div class="seat">S1</div>
                                                <div class="seat">S2</div>
                                                <div class="seat">S3</div>
                                                <div class="seat special">H1</div>
                                            </div>
                                            <div class="seat-row">
                                                <div class="seat special">H2</div>
                                                <div class="seat">S4</div>
                                                <div class="seat">S5</div>
                                                <div class="seat">S6</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="btn" id="backStep4">Back</button>
                                    <button class="btn btn-generate" id="generateSeatPlan" disabled>Generate Seat Plan</button>
                                </div>
                            </div>

                            <div class="success-message" id="successMessage">
                                🎉 Seat plan generated successfully! Redirecting to view...
                            </div>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
    </div>
    

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <div class="loading-message" id="loadingMessage">Preparing seat plan generation...</div>
            <div class="loading-progress">
                <div class="loading-progress-bar" id="loadingProgressBar"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            
            // Building checkbox logic
            $('.building-checkbox').on('change', function() {
                const buildingId = $(this).attr('id');
                const isChecked = $(this).is(':checked');
                const card = $(this).closest('.card');
                
                // Check/uncheck all rooms in this building
                $(`.room-checkbox[data-building="${buildingId}"]`).prop('checked', isChecked);
                
                // Update card appearance
                if (isChecked) {
                    card.addClass('selected');
                } else {
                    card.removeClass('selected');
                }
                
                validateStep1();
            });

            // Room checkbox logic
            $('.room-checkbox').on('change', function() {
                const buildingId = $(this).data('building');
                const buildingCheckbox = $(`#${buildingId}`);
                const allRooms = $(`.room-checkbox[data-building="${buildingId}"]`);
                const checkedRooms = $(`.room-checkbox[data-building="${buildingId}"]:checked`);
                
                // Update building checkbox based on room selections
                if (checkedRooms.length === 0) {
                    buildingCheckbox.prop('checked', false);
                    buildingCheckbox.closest('.card').removeClass('selected');
                } else if (checkedRooms.length === allRooms.length) {
                    buildingCheckbox.prop('checked', true);
                    buildingCheckbox.closest('.card').addClass('selected');
                }
                
                validateStep1();
            });

            // Class checkbox logic
            $('.class-checkbox').on('change', function() {
                const classId = $(this).attr('id');
                const isChecked = $(this).is(':checked');
                const card = $(this).closest('.card');
                
                // Check/uncheck all sections in this class
                $(`.section-checkbox[data-class="${classId}"]`).prop('checked', isChecked);
                
                // Update card appearance
                if (isChecked) {
                    card.addClass('selected');
                } else {
                    card.removeClass('selected');
                }
                
                validateStep2();
            });

            // Section checkbox logic
            $('.section-checkbox').on('change', function() {
                const classId = $(this).data('class');
                const classCheckbox = $(`#${classId}`);
                const allSections = $(`.section-checkbox[data-class="${classId}"]`);
                const checkedSections = $(`.section-checkbox[data-class="${classId}"]:checked`);
                
                // Update class checkbox based on section selections
                if (checkedSections.length === 0) {
                    classCheckbox.prop('checked', false);
                    classCheckbox.closest('.card').removeClass('selected');
                } else if (checkedSections.length === allSections.length) {
                    classCheckbox.prop('checked', true);
                    classCheckbox.closest('.card').addClass('selected');
                }
                
                validateStep2();
            });

            // Staff checkbox logic
            $('.staff-checkbox').on('change', function() {
                validateStep3();
            });

            // Title input validation
            $('#seatPlanTitle').on('input', function() {
                validateStep1();
            });

            // Step navigation
            $('#nextStep1').on('click', function() {
                if (validateStep1()) {
                    moveToStep(2);
                }
            });

            $('#backStep2').on('click', function() {
                moveToStep(1);
            });

            $('#nextStep2').on('click', function() {
                if (validateStep2()) {
                    moveToStep(3);
                }
            });
            $('#nextStep3').on('click', function() {
                if (validateStep3()) {
                    moveToStep(4);
                }
            });

            $('#backStep3').on('click', function() {
                moveToStep(2);
            });

            $('.pattern-radio').on('change', function() {
                const selectedPattern = $(this).attr('id');
                
                // Update card appearances
                $('.pattern-card').removeClass('selected');
                $(this).closest('.pattern-card').addClass('selected');
                
                // Hide all config sections
                $('.pattern-config').hide();
                
                // Show relevant config section
                if (selectedPattern === 'sequential') {
                    $('#sequentialConfig').show();
                } else if (selectedPattern === 'alternate') {
                    $('#alternateConfig').show();
                } else if (selectedPattern === 'stages') {
                    $('#stagesConfig').show();
                }
                
                validateStep4();
            });


            $('#generateSeatPlan').on('click', function() {
                if (validateStep4()) {
                    generateSeatPlan();
                }
            });

            function validateStep1() {
                const title = $('#seatPlanTitle').val().trim();
                const selectedRooms = $('.room-checkbox:checked').length;
                const isValid = title.length > 0 && selectedRooms > 0;
                $('#nextStep1').prop('disabled', !isValid);
                return isValid;
            }

            function validateStep2() {
                const selectedSections = $('.section-checkbox:checked').length;
                const isValid = selectedSections > 0;
                $('#nextStep2').prop('disabled', !isValid);
                return isValid;
            }

            function validateStep3() {
                const selectedStaff = $('.staff-checkbox:checked').length;
                const isValid = selectedStaff > 0;
                console.log(isValid)
                $('#nextStep3').prop('disabled', !isValid);
                return isValid;
            }
            function validateStep4() {
                const selectedPattern = $('.pattern-radio:checked').length;
                const isValid = selectedPattern > 0;
                $('#generateSeatPlan').prop('disabled', !isValid);
                return isValid;
            }

            function moveToStep(step) {
                // Update current step
                currentStep = step;
                
                // Update progress bar
                const progress = (step / 4) * 100;
                $('#progressBar').css('width', progress + '%');
                
                // Show/hide steps
                $('.step').removeClass('active').addClass('collapsed');
                $(`#step${step}`).removeClass('collapsed').addClass('active');
                
                // Scroll to top of step
                $(`#step${step}`)[0].scrollIntoView({ behavior: 'smooth' });
            }

            function generateSeatPlan() {
                // Collect form data
                const formData = {
                    title: $('#seatPlanTitle').val(),
                    buildings: [],
                    rooms: [],
                    classes: [],
                    sections: [],
                    staff: [],
                    seatingPattern: {
                        type: $('input[name="seatingPattern"]:checked').val(),
                        config: {},
                        specialNeeds: $('#specialNeeds').is(':checked')
                    }
                };

                // Collect selected buildings
                $('.building-checkbox:checked').each(function() {
                    formData.buildings.push($(this).attr('id'));
                });

                // Collect selected rooms
                $('.room-checkbox:checked').each(function() {
                    formData.rooms.push($(this).attr('id'));
                });

                // Collect selected classes
                $('.class-checkbox:checked').each(function() {
                    formData.classes.push($(this).attr('id'));
                });

                // Collect selected sections
                $('.section-checkbox:checked').each(function() {
                    formData.sections.push($(this).attr('id'));
                });

                // Collect selected staff
                $('.staff-checkbox:checked').each(function() {
                    formData.staff.push($(this).attr('id'));
                });

                // Collect pattern-specific configuration
                const patternType = formData.seatingPattern.type;
                if (patternType === 'sequential') {
                    formData.seatingPattern.config = {
                        startingClass: $('#sequentialClass').val(),
                        startingRoll: $('#sequentialRoll').val()
                    };
                } else if (patternType === 'alternate') {
                    formData.seatingPattern.config = {
                        startingClass: $('#alternateClass').val(),
                        startingRoll: $('#alternateRoll').val()
                    };
                } else if (patternType === 'stages') {
                    formData.seatingPattern.config = {
                        stageType: $('#stageType').val()
                    };
                }

                // Show loading overlay
                $('#loadingOverlay').show();
                
                // Simulate loading process
                const loadingMessages = [
                    "Collecting building data...",
                    "Collecting class and student data...",
                    "Collecting invigilator data...",
                    "Processing seating pattern preferences...",
                    "Generating optimal seat arrangement...",
                    "Finalizing seat plan..."
                ];

                let messageIndex = 0;
                let progress = 0;

                const loadingInterval = setInterval(function() {
                    if (messageIndex < loadingMessages.length) {
                        $('#loadingMessage').text(loadingMessages[messageIndex]);
                        progress += 20;
                        $('#loadingProgressBar').css('width', progress + '%');
                        messageIndex++;
                    } else {
                        // return;
                        clearInterval(loadingInterval);
                        setTimeout(function() {
                            console.log('here')
                            loadSeatPlan('response');
                        }, 2000);
                        
                        // Simulate AJAX request (replace with actual endpoint)
                        // setTimeout(function() {
                        //     $.ajax({
                        //         url: '/generate-seat-plan', // Replace with your actual endpoint
                        //         method: 'POST',
                        //         data: JSON.stringify(formData),
                        //         contentType: 'application/json',
                        //         success: function(response) {
                        //             $('#loadingOverlay').hide();
                        //             $('#successMessage').show();
                                    
                        //             // Simulate redirect to loadSeatPlan function
                        //             setTimeout(function() {
                        //                 loadSeatPlan(response);
                        //             }, 2000);
                        //         },
                        //         error: function(xhr, status, error) {
                        //             $('#loadingOverlay').hide();
                        //             alert('Error generating seat plan: ' + error);
                        //         }
                        //     });
                        // }, 1000);
                    }
                }, 1000);
            }

            function loadSeatPlan(data) {
                // This function would handle the seat plan display
                // For demo purposes, just show an alert
                alert('Seat plan loaded successfully! This would redirect to the seat plan view.');
                console.log('Seat plan data:', data);
            }

            // Initialize validation
            validateStep1();
        });
    </script>
@endsection