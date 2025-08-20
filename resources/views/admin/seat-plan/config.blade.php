@extends('layouts.admin')

@section('title')
Seat Plan Config
@endsection

@section('styles')
    <link href="{{ asset('admin/dist/css/seat-plan-config.css?v=2') }}" rel="stylesheet">
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
                                    {{-- @php
                                        dd($data['buildings']);
                                    @endphp --}}
                                    @if (isset($data['buildings']) && !empty($data['buildings']))
                                        @foreach ($data['buildings'] as $building)
                                            <div class="card" data-building="{{$building['id']}}">
                                                <div class="card-header">
                                                    <input type="checkbox" class="checkbox building-checkbox" id="building{{$building['id']}}" data-server-id="{{$building['id']}}">
                                                    <label for="building{{$building['id']}}" class="card-title">{{$building['name']}}</label>
                                                </div>
                                                <div class="card-content">
                                                    <?php 
                                                        $rooms = json_decode($building['rooms'],true);
                                                        // dd($rooms);
                                                    ?>
                                                    @foreach ($rooms as $index => $room)
                                                        <div class="room-item">
                                                            <input type="checkbox" class="checkbox room-checkbox" id="{{str_replace(' ', '_', strtolower($room['name'])).'_'.$index}}" data-room-index="{{$index}}" data-building="{{$building['id']}}">
                                                            <label for="room1_1">{{$room['name']}}</label>
                                                            <span class="room-info">
                                                                @if ($room['selected_type'] == 'total')
                                                                    ({{$room['total']['benches']}} benches, {{($room['total']['benches'] * $room['total']['seats'])}} seats)
                                                                @else
                                                                    ({{$room['room_total']['total_bench']}} benches, {{($room['room_total']['total_seats'])}} seats)                                                                    
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>You have not added any buildings. Please add building and rooms data first.</p>
                                    @endif
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
                                        <div class="select-all-container">
                                            <input type="checkbox" id="selectAllSections" class="checkbox">
                                            <label for="selectAllSections">Select all</label>
                                        </div>
                                
                                <div class="grid" id="classesGrid">
                                    @if (isset($data['classes']) && !empty($data['classes']))
                                        @foreach ($data['classes'] as $class)
                                            <div class="card" data-class="{{$class['id']}}">
                                                <div class="card-header">
                                                    <input type="checkbox" class="checkbox class-checkbox" id="class{{$class['id']}}" data-server-id="{{$class['id']}}">
                                                    <label for="class{{$class['id']}}" class="card-title">{{$class['name']}}</label>
                                                </div>
                                                <div class="card-content">
                                                    @foreach ($class['sections'] as $section)
                                                        <div class="section-item">
                                                            <input type="checkbox" class="checkbox section-checkbox" id="section{{$section['id']}}_{{$class['id']}}" data-class="{{$class['id']}}" data-server-id="{{$section['id']}}">
                                                            <label for="section{{$section['id']}}_{{$class['id']}}">{{$section['title']}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>You have not added any classes. Please add class data first.</p>
                                    @endif
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
                                @if (isset($data['staffs']) && !empty($data['staffs']))                              
                                    @php                            
                                        // Split the staff array into three parts
                                        $staffChunks = array_chunk($data['staffs'], ceil(count($data['staffs']) / 3)); // Splits into 3 parts    
                                    @endphp    
                                    <div class="grid" id="staffGrid">
                                        {{-- @foreach ($staffChunks as $index => $staffGroup)
                                            <div class="card">
                                                <div class="card-content">
                                                    @foreach ($staffGroup as $staffItem)
                                                        <div class="staff-item">
                                                            <input type="checkbox" class="checkbox staff-checkbox" id="staff{{$staffItem['id']}}" data-server-id="{{$staffItem['id']}}" checked>
                                                            <label for="staff1">{{$staffItem['name']}}</label>
                                                            <span class="staff-info">{{isset($staffItem['staff']['department']['title']) ? '('.$staffItem['staff']['department']['title'].')' : ''}}</span>
                                                        </div>                                         
                                                    @endforeach
                                                </div>
                                            </div>                                            
                                        @endforeach --}}

                                        @foreach ($staffChunks as $index => $staffGroup)
                                            <div class="card">
                                                <div class="card-content">
                                                    @foreach ($staffGroup as $staffItem)
                                                        <div class="staff-item">
                                                            <input type="checkbox" class="checkbox staff-checkbox" id="staff{{$staffItem['staff']['id']}}" data-server-id="{{$staffItem['staff']['id']}}" checked>
                                                            <label for="staff1">{{$staffItem['name']}}</label>
                                                            <span class="staff-info">{{isset($staffItem['staff']['department']['title']) ? '('.$staffItem['staff']['department']['title'].')' : ''}}</span>
                                                        </div>                                         
                                                    @endforeach
                                                </div>
                                            </div>                                            
                                        @endforeach
                                    </div>
                                    <div class="button-group">
                                        <button class="btn" id="backStep3">Back</button>
                                        <button class="btn" id="nextStep3">Next</button>
                                        <button class="btn" id="skipStep3">Skip</button>
                                    </div>
                                @else
                                    <p>You have not added any staffs. Please add staff data.</p>      
                                    <div class="button-group">
                                        <button class="btn" id="skipStep3">Skip</button>
                                    </div>                              
                                @endif
                            </div>


                            <!-- Step 4: Seating Pattern Selection -->
                            <div class="step collapsed" id="step4">
                                <div class="step-header">
                                    <div class="step-number">4</div>
                                    <div class="step-title">Seating Pattern Selection</div>
                                </div>
                                
                                <div class="pattern-grid">
                                    <!-- Sequential Class Assignment -->

                                    <!-- Class-Based Row Assignment -->
                                    <div class="pattern-card" data-pattern="rowbased">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="rowbased" class="pattern-radio" value="rowbased">
                                            <label for="rowbased" class="pattern-title">Class-Based Row Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign entire rows to specific classes
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="bench-row">
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
                                            {{-- <div class="seat-row">
                                                <div class="seat physics">P1</div>
                                                <div class="seat physics">P2</div>
                                                <div class="seat physics">P3</div>
                                                <div class="seat physics">P4</div>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="pattern-card" data-pattern="sequential">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="sequential" class="pattern-radio" value="sequential">
                                            <label for="sequential" class="pattern-title">Sequential Class Assignment</label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign students from the same class in consecutive order
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="bench-row">
                                                <div class="seat-row">
                                                    <div class="seat cs">CS1</div>
                                                    <div class="seat cs">CS3</div>
                                                    <div class="seat math">M1</div>
                                                    <div class="seat math">M3</div>
                                                </div>
                                                <div class="seat-row">
                                                    <div class="seat cs">CS2</div>
                                                    <div class="seat cs">CS4</div>
                                                    <div class="seat math">M2</div>
                                                    <div class="seat math">M4</div>
                                                </div>
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
                                    {{-- <div class="pattern-card" data-pattern="alternate">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="alternate" class="pattern-radio" value="alternate" disabled>
                                            <label for="alternate" class="pattern-title">Alternate Class Assignment <span style="color: red;">(Not Available)</span> </label>
                                        </div>
                                        <div class="pattern-description">
                                            Assign students from different classes alternately
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="bench-row">
                                                <div class="seat-row">
                                                    <div class="seat cs">CS1</div>
                                                    <div class="seat math">M1</div>
                                                    <div class="seat cs">CS2</div>
                                                    <div class="seat math">M2</div>
                                                </div>
                                                <div class="seat-row">
                                                    <div class="seat math">M3</div>
                                                    <div class="seat cs">CS3</div>
                                                    <div class="seat math">M4</div>
                                                    <div class="seat cs">CS4</div>
                                                </div>
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
                                    </div> --}}

                                    <!-- Class Assignment in Stages -->
                                    {{-- <div class="pattern-card" data-pattern="stages">
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
                                    </div> --}}

                                    <!-- Random Student Assignment -->
                                    {{-- <div class="pattern-card" data-pattern="random">
                                        <div class="pattern-header">
                                            <input type="radio" name="seatingPattern" id="random" class="pattern-radio" value="random" disabled>
                                            <label for="random" class="pattern-title">Random Student Assignment <span style="color: red;">(Not Available)</span></label>
                                        </div>
                                        <div class="pattern-description">
                                            Seats are filled randomly with students from all classes
                                        </div>
                                        <div class="pattern-visual">
                                            <div class="bench-row">
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
                                    </div> --}}

                                </div>

                                <!-- Special Needs Seating -->
                                {{-- <div class="special-needs-section">
                                    <div class="special-needs-card">
                                        <div class="special-needs-header">
                                            <input type="checkbox" id="specialNeeds" class="checkbox">
                                            <label for="specialNeeds" class="special-needs-title">Special Needs Seating</label>
                                        </div>
                                        <div class="special-needs-description">
                                            Differently abled students will be seated at bench edges (rightmost in Row 1, leftmost in Row 2, alternating pattern)
                                        </div>
                                        <div class="special-needs-visual">
                                            <div class="seat-row d-flex">
                                                <div class="seat">S1</div>
                                                <div class="seat special">H1</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="button-group">
                                    <button class="btn" id="backStep4">Back</button>
                                    <button class="btn btn-generate" id="generateSeatPlan" disabled>Generate Seat Plan</button>
                                </div>
                            </div>

                            {{-- <div class="success-message" id="successMessage">
                                🎉 Seat plan generated successfully! Redirecting to view...
                            </div> --}}
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

            function isValidSelection() {
                const checkedClasses = $('.class-checkbox:checked').length;
                const checkedSections = $('.section-checkbox:checked').length;
                return (checkedClasses + checkedSections) > 1;
            }

            $('#selectAllSections').on('change', function() {
                $('.section-checkbox').prop('checked', $(this).prop('checked'));
                
                // Update class checkboxes based on section selections
                $('.class-checkbox').each(function() {
                    const classId = $(this).data('server-id');
                    const allSectionsChecked = $(`.section-checkbox[data-class="${classId}"]`).length > 0 &&
                        $(`.section-checkbox[data-class="${classId}"]`).filter(':checked').length === 
                        $(`.section-checkbox[data-class="${classId}"]`).length;
                    $(this).prop('checked', allSectionsChecked);

                    // Update card appearance only if valid selection
                    const card = $(this).closest('.card');
                    if (isValidSelection()) {
                        if (allSectionsChecked) {
                            card.addClass('selected');
                        } else {
                            card.removeClass('selected');
                        }
                    } else {
                        card.removeClass('selected');
                    }
                });
                // Validate step 2 only if valid selection
                if (isValidSelection()) {
                    validateStep2();
                }else{
                    $('#nextStep2').attr('disabled','disabled');
                }
            });

            // Update select all checkbox state when individual section checkboxes change
            $('.section-checkbox').on('change', function() {
                const allChecked = $('.section-checkbox').length > 0 && 
                    $('.section-checkbox').filter(':checked').length === $('.section-checkbox').length;
                $('#selectAllSections').prop('checked', allChecked);
            });
            
            // Building checkbox logic
            $('.building-checkbox').on('change', function() {
                const buildingId = $(this).attr('data-server-id');
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
                const classId = $(this).attr('data-server-id');
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
                
                // Validate step 2 only if valid selection
                if (isValidSelection()) {
                    validateStep2();
                }
                // validateStep2();
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
            $('#skipStep3').on('click', function() {
                moveToStep(4);
            });
            $('#nextStep3').on('click', function() {
                if (validateStep3()) {
                    moveToStep(4);
                }
            });

            $('#backStep4').on('click', function() {
                moveToStep(3);
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
                // if (selectedPattern === 'sequential') {
                //     $('#sequentialConfig').show();
                // } else if (selectedPattern === 'alternate') {
                //     $('#alternateConfig').show();
                // } else if (selectedPattern === 'stages') {
                //     $('#stagesConfig').show();
                // }
                
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
                // console.log(isValid)
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
                    rooms: {},
                    classes: [],
                    sections: {},
                    staff: [],
                    seatingPattern: {
                        type: $('input[name="seatingPattern"]:checked').val(),
                        config: {},
                        specialNeeds: $('#specialNeeds').is(':checked')
                    }
                };
                // console.log(formData)

                // Collect selected buildings
                $('.building-checkbox:checked').each(function() {
                    // formData.buildings.push($(this).attr('id'));
                    formData.buildings.push($(this).attr('data-server-id'));
                });

                // Collect selected rooms
                // $('.room-checkbox:checked').each(function() {
                //     formData.rooms.push($(this).attr('id'));
                // });
                // var rooms = {};  
                // Object to hold buildings and their associated room indices

                $('.room-checkbox:checked').each(function() {
                    var building = $(this).data('building');  // Get the building from data-building attribute
                    var roomIndex = $(this).data('room-index');  // Get the room index from data-room-index attribute
                    
                    // console.log(building)
                    // console.log(roomIndex)
                   
                    // If the building doesn't exist in the rooms object, initialize it as an empty array
                    if (!formData.rooms[building]) {
                        formData.rooms[building] = [];
                    }

                    // Push the room index into the array for that building
                    formData.rooms[building].push(roomIndex);
                    // console.log(formData)
                });

                // Collect selected classes
                $('.class-checkbox:checked').each(function() {
                    // formData.classes.push($(this).attr('id'));
                    formData.classes.push($(this).attr('data-server-id'));
                });

                $('.section-checkbox:checked').each(function() {
                    var class_id = $(this).data('class');  // Get the building from data-building attribute
                    var section_id = $(this).data('server-id');  // Get the room index from data-room-index attribute

                    // console.log(class_id)
                    // console.log(section_id)
                    // console.log('here')

                    if (!formData.sections[class_id]) {
                        formData.sections[class_id] = [];
                    }

                    formData.sections[class_id].push(section_id);
                    // console.log(formData)
                });
                // Collect selected sections
                // $('.section-checkbox:checked').each(function() {
                //     // formData.sections.push($(this).attr('id'));
                //     formData.sections.push($(this).attr('data-server-id'));
                // });

                // Collect selected staff
                $('.staff-checkbox:checked').each(function() {
                    formData.staff.push($(this).attr('data-server-id'));
                });

                // Collect pattern-specific configuration
                // const patternType = formData.seatingPattern.type;
                // if (patternType === 'sequential') {
                //     formData.seatingPattern.config = {
                //         startingClass: $('#sequentialClass').val(),
                //         startingRoll: $('#sequentialRoll').val()
                //     };
                // } else if (patternType === 'alternate') {
                //     formData.seatingPattern.config = {
                //         startingClass: $('#alternateClass').val(),
                //         startingRoll: $('#alternateRoll').val()
                //     };
                // } else if (patternType === 'stages') {
                //     formData.seatingPattern.config = {
                //         stageType: $('#stageType').val()
                //     };
                // }

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
                        clearInterval(loadingInterval);
                        // Proceed to handle AJAX request after all messages have been shown
                        handleRequest();
                    }
                }, 100);

                // Function to handle the AJAX request
                function handleRequest() {
                    // Start the AJAX request
                    $.ajax({
                        url: '/generate-seat-plan', // Replace with your actual endpoint
                        method: 'POST',
                        data: JSON.stringify(formData),
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        contentType: 'application/json',
                        success: function(response) {
                            handleResponse('success', response);
                        },
                        error: function(xhr, status, error) {
                            // console.log('Error response:', xhr.responseText);
                            handleResponse('error', xhr.responseText);
                        }
                    });
                }

                // Handle the response after the loading messages
                function handleResponse(status, response) {
                    $('#loadingOverlay').hide(); // Hide loading overlay
                    if (status === 'success') {
                        // Show final success message
                        $('#loadingMessage').text('Seat plan generated. Please choose an action.');

                        // Show SweetAlert asking if the user wants to go to the seat plan layout
                        Swal.fire({
                            icon: 'success',
                            title: 'Seat plan generated',
                            text: 'Would you like to be redirected to the seat plan layout?',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, take me there',
                            cancelButtonText: 'No, take me to seat plan list'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the user clicks 'Yes', redirect to the seat plan layout
                                window.location.href = '/seat-plan/' + response.seatPlanId;
                            } else {
                                // If the user clicks 'No', redirect to the seat plan list
                                window.location.href = '/seat-plan';
                            }
                        });

                    } else {
                        // Show SweetAlert with error message
                        try {
                            var errorResponse = JSON.parse(response);
                        } catch (e) {
                            var errorResponse = { message: 'An unknown error occurred while generating the seat plan.' };
                        }

                        const errorMessage = errorResponse.message || 'An unknown error occurred while generating the seat plan.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error generating seat plan: ' + errorMessage,
                            showCancelButton: true,
                            confirmButtonText: 'Retry',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Retry the request
                                retryRequest();
                            }
                        });
                    }
                }


                // Retry the AJAX request
                function retryRequest() {
                    messageIndex = 0; // Reset the message index
                    progress = 0; // Reset progress bar

                    $('#loadingOverlay').show(); // Show the loading overlay again
                    $('#loadingMessage').text(loadingMessages[messageIndex]); // Show the first message
                    $('#loadingProgressBar').css('width', progress + '%'); // Reset progress bar

                    // Start the loading messages again
                    const retryInterval = setInterval(function() {
                        if (messageIndex < loadingMessages.length) {
                            $('#loadingMessage').text(loadingMessages[messageIndex]);
                            progress += 20;
                            $('#loadingProgressBar').css('width', progress + '%');
                            messageIndex++;
                        } else {
                            clearInterval(retryInterval);
                        }
                    }, 1000);

                    // Make the AJAX request again
                    $.ajax({
                        url: '/generate-seat-plan',
                        method: 'POST',
                        data: JSON.stringify(formData),
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        contentType: 'application/json',
                        success: function(response) {
                            handleResponse('success', response);
                        },
                        error: function(xhr, status, error) {
                            handleResponse('error', error);
                        }
                    });
                }

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