<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\HelperFile;
use App\Models\Building;
use App\Models\ClassModel;
use App\Models\Faculty;
use App\Models\InvigilatorPlanDetail;
use App\Models\SeatPlan;
use App\Models\SeatPlanDetail;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

class SeatPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [];
        return view('admin.seat-plan.index', compact('data'));
    }

    public function listPartial(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->get('search')['value'] ?? '';  // DataTables sends the search term in search[value]

        // Pagination parameters
        $page = $request->get('page', 1);  // Default page is 1
        $perPage = $request->get('pageLength', 10);  // Use the pageLength parameter to get the number of items per page

        // Build the query to filter data
        $query = SeatPlan::where('user_id', session('school_id'))
            ->orderBy('id', 'desc');

        // Apply the search filter if a search term is provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($query) use ($searchTerm) {
                    $query->where('title', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Paginate the results
        $seat_plans = $query->paginate($perPage);
        // dd($seat_plans);

        // Return the response in JSON format for DataTables
        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $seat_plans->total(),
            'recordsFiltered' => $seat_plans->total(), // Since we're not using a separate filtered count, this is the same as recordsTotal
            'data' => $seat_plans->items()  // Return the paginated data
        ]);
    }

    public function config()
    {
        //Buildings
        $data['buildings'] = Building::where('user_id', session('school_id'))
            ->get()
            ->toArray();
        //Buildings

        //classes
        $classes = ClassModel::where('user_id', session('school_id'))
            ->with('sections')
            ->whereNull('batch_id')
            ->get()
            ->toArray();

        $faculties = Faculty::where('user_id', session('school_id'))
            ->with('batches.classes.sections')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        // Final structured array
        $finalData = [];

        // Loop through each class
        foreach ($classes as $class) {
            // Skip classes with no sections
            if (empty($class['sections'])) {
                continue;
            }
            // Create a new class structure
            $classData = [
                'id' => $class['id'],
                'name' => $class['title'], // name from class table
                'sections' => [],
            ];

            // Find matching sections for the class
            foreach ($class['sections'] as $section) {
                $classData['sections'][] = [
                    'id' => $section['id'],
                    'title' => $section['title'],
                ];
            }

            // Add the class data to the final array
            $finalData[] = $classData;
        }

        foreach ($faculties as $faculty) {
            // Skip faculty with no batches
            if (empty($faculty['batches'])) {
                continue;
            }
            foreach ($faculty['batches'] as $batch) {
                // Skip batch with no classes
                if (empty($batch['classes'])) {
                    continue;
                }
                foreach ($batch['classes'] as $facultyClass) {
                    // Skip class with no sections
                    if (empty($facultyClass['sections'])) {
                        continue;
                    }
                    // Create new structure for each class based on faculty and batch
                    $classData = [
                        'id' => $facultyClass['id'],
                        'name' => $faculty['title'] . ' - ' . $batch['title'] . ' - ' . $facultyClass['title'], // Combine faculty title, batch title, and class name
                        'sections' => [],
                    ];

                    // Add sections to this class data
                    foreach ($facultyClass['sections'] as $section) {
                        $classData['sections'][] = [
                            'id' => $section['id'],
                            'title' => $section['title'],
                        ];
                    }

                    // Add this class data to the final array
                    $finalData[] = $classData;
                }
            }
        }
        $data['classes'] = $finalData;
        //classes

        //Staffs
        $user_type_id_of_staff = UserType::where('name', 'staff')->first();
        $data['staffs'] = User::where([
            'parent_id' => session('school_id'),
            'user_type_id' => $user_type_id_of_staff->id,

        ])->with('staff.department:id,title')->get()->toArray();
            
        // dd($data['staffs']);

        return view('admin.seat-plan.config', compact('data'));
    }

    public function seatPlanConfigV3()
    {
        return view('admin.seat-plan.config-2');
    }
    
    public function unassignedList(Request $request, $id)
    {
        // Fetch the seat plan by ID
        $seatPlan = SeatPlan::findOrFail($id);

        // Parse unassigned students and staffs
        $unassignedStudents = $seatPlan->unassigned_students ? json_decode($seatPlan->unassigned_students, true) : [];
        $unassignedStaffs = $seatPlan->unassigned_staffs ? json_decode($seatPlan->unassigned_staffs, true) : [];

        // Fetch student details with related class, section, and user
        $students = [];
        if (!empty($unassignedStudents)) {
            $students = Student::whereIn('id', $unassignedStudents)
                ->with(['class', 'section', 'user'])
                ->get()
                ->map(function ($student, $index) {
                    return [
                        'sn' => $index + 1,
                        'id' => $student->id ?? 'N/A',
                        'image' => $student->user->avatar ?? 'default.jpg',
                        'name' => $student->user->name ?? 'N/A',
                        'class' => $student->class->title ?? 'N/A',
                        'section' => $student->section->title ?? 'N/A',
                    ];
                })->toArray();
        }
        // dd($students);

        // Fetch staff details with related department, position, and user
        $staffs = [];
        if (!empty($unassignedStaffs)) {
            $staffs = Staff::whereIn('id', $unassignedStaffs)
                ->with(['department', 'position', 'user'])
                ->get()
                ->map(function ($staff, $index) {
                    return [
                        'sn' => $index + 1,
                        'id' => $staff->id ?? 'N/A',
                        'image' => $staff->user->profile_image ?? 'default.jpg',
                        'name' => $staff->user->name ?? 'N/A',
                        'department' => $staff->department->title ?? 'N/A',
                        'position' => $staff->position->title ?? 'N/A',
                    ];
                })->toArray();
        }

        // <th>Image</th>
        // <td><img src="' . asset('storage/' . $student['image']) . '" alt="image" width="50" height="50"></td>
        // <th>Image</th>
        // <td><img src="' . asset('storage/' . $staff['image']) . '" alt="image" width="50" height="50"></td>

        // HTML for the modal content
        $modalContent = '
        <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Unassigned List</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="unassignedTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="true">Students</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="staffs-tab" data-bs-toggle="tab" data-bs-target="#staffs" type="button" role="tab" aria-controls="staffs" aria-selected="false">Staffs</button>
                </li>
            </ul>
            <div class="tab-content" id="unassignedTabContent">
                <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>';

        if (empty($students)) {
            $modalContent .= '<tr><td colspan="5" class="text-center">No unassigned students found</td></tr>';
        } else {
            foreach ($students as $student) {
                $modalContent .= '
                    <tr>
                        <td>' . $student['sn'] . '</td>
                        <td>' . htmlspecialchars($student['name']) . '</td>
                        <td>' . htmlspecialchars($student['class']) . '</td>
                        <td>' . htmlspecialchars($student['section']) . '</td>
                    </tr>';
            }
        }

        $modalContent .= '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="staffs" role="tabpanel" aria-labelledby="staffs-tab">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                            </tr>
                        </thead>
                        <tbody>';

        if (empty($staffs)) {
            $modalContent .= '<tr><td colspan="5" class="text-center">No unassigned staffs found</td></tr>';
        } else {
            foreach ($staffs as $staff) {
                $modalContent .= '
                    <tr>
                        <td>' . $staff['sn'] . '</td>
                        <td>' . htmlspecialchars($staff['name']) . '</td>
                        <td>' . htmlspecialchars($staff['department']) . '</td>
                        <td>' . htmlspecialchars($staff['position']) . '</td>
                    </tr>';
            }
        }

        $modalContent .= '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>';

        return response()->json(['modal_content' => $modalContent]);
    }

    public function seatInvigilatorLayout($id)
    {
        // Step 1: Get seat plan data
        $data['seat_plan'] = SeatPlan::select('id', 'title', 'unassigned_students', 'unassigned_staffs')
            ->where('id', $id)
            ->first()
            ->toArray();

        // Step 2: Get distinct building IDs from seat_plan_details
        $buildingIds = InvigilatorPlanDetail::where('seat_plan_id', $id)
            ->distinct()
            ->pluck('building_id');

        // Step 3: Get building information from the buildings table
        $buildings = Building::whereIn('id', $buildingIds)
            ->get();
        $data['buildings'] = $buildings->toArray();

        // Step 4: Get all seat plan details for the given seat_plan_id
        $seatPlanDetails = InvigilatorPlanDetail::where('seat_plan_id', $id)
            ->get();

        // Step 5: Group by building_id and room, then map staff details
        $groupedStaff = [];

        foreach ($seatPlanDetails as $detail) {
            // Check if staff exists for the current detail
            // $staff = $detail->staff;
            // if ($staff) {
                // Initialize the group if it doesn't exist
                if (!isset($groupedStaff[$detail->building_id])) {
                    $groupedStaff[$detail->building_id] = [];
                }

                // Initialize the room index if it doesn't exist
                if (!isset($groupedStaff[$detail->building_id][$detail->room])) {
                    $groupedStaff[$detail->building_id][$detail->room] = [];
                }

                // Add the staff details
                $groupedStaff[$detail->building_id][$detail->room][$detail->staff_id] = [
                    'name' => $detail->staff_name,
                    'department' => $detail->staff_department,
                    'position' => $detail->staff_position
                ];
            // }
        }

        // Step 6: Handle unassigned staff
        if (!empty($data['seat_plan']['unassigned_staffs'])) {
            $unassignedStaffIds = json_decode($data['seat_plan']['unassigned_staffs'], true);

            // Fetch details of unassigned staff in one query
            $unassignedStaffs = Staff::whereIn('id', $unassignedStaffIds)
                ->with('user') // Eager load the user relation
                ->get();

            // Store the staff details in an array
            $unassignedStaffDetails = [];
            foreach ($unassignedStaffs as $staff) {
                $unassignedStaffDetails[$staff->id] = [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'department' => $staff->department->title,
                    'position' => $staff->position->title,
                    'avatar' => $staff->user ? $staff->user->avatar : null // Get avatar from the user relation
                ];
            }

            // Add unassigned staff details to data
            $data['unassigned_staffs'] = $unassignedStaffDetails;
        } else {
            // If there are no unassigned staffs, set to empty array
            $data['unassigned_staffs'] = [];
        }

        // Step 7: Return the grouped staff data
        $data['grouped_staff'] = $groupedStaff;
        // dd($data);
        $data['configs'] = HelperFile::getSchoolConfigs();

        return view('admin.seat-plan.invig-show', compact('data'));
        
        // For debugging purposes
    }


    public function seatPlanLayout($id)
    {
        // Step 1: Get seat plan data
        $data['seat_plan'] = SeatPlan::select('id', 'title', 'unassigned_students')
            ->where('id', $id)
            ->first()
            ->toArray();

        // Step 2: Get distinct building IDs from seat_plan_details
        $buildingIds = SeatPlanDetail::where('seat_plan_id', $id)
            ->distinct()
            ->pluck('building_id');

        // Step 3: Get building information from the buildings table
        $buildings = Building::whereIn('id', $buildingIds)
            ->get();
        $data['buildings'] = $buildings->toArray();

        // Step 4: Get all seat plan details with student relationship loaded
        // $seatPlanDetails = SeatPlanDetail::with(['student', 'student.class', 'student.section']) // Eager load relationships
        //     ->where('seat_plan_id', $id)
        //     ->get();

        $seatPlanDetails = SeatPlanDetail::where('seat_plan_id', $id)->get();
        // dd($seatPlanDetails);
        // Group by class (class_id => [roll_no's])
        // $classGrouped = $seatPlanDetails->groupBy(function($item) {
        //     return $item->student->class->title; // Group by class title
        // })->map(function($group) {
        //     return $group->pluck('student.roll_no'); // Get all roll_no's
        // });

        // Group by class and section (class_id.section_id => [roll_no's])
        // $classSectionGrouped = $seatPlanDetails->groupBy(function($item) {
        //     return $item->student->class->title . ' ' . $item->student->section->title; // Group by class title + section title
        // })->map(function($group) {
        //     return $group->pluck('student.roll_no'); // Get all roll_no's
        // });
        // Output the result
        // Step 5: Organize the data into the required structure
        $arrangedData = [];

        $groupedByBuildingAndRoom = [];
        $studentDataForAttendance = [];
        $groupedByBuildingRoomClass = [];
        $groupedByBuildingRoomClassSection = [];

        foreach ($seatPlanDetails as $detail) {
            $buildingId = $detail->building_id;
            $room = $detail->room; // Room number or identifier (this can be modified if needed)
            $bench = $detail->bench;
            $seat = $detail->seat;

            // Initialize building and room if not already created
            if (!isset($arrangedData[$buildingId])) {
                $arrangedData[$buildingId] = [];
            }

            if (!isset($arrangedData[$buildingId][$room])) {
                $arrangedData[$buildingId][$room] = [];
            }

            if (!isset($arrangedData[$buildingId][$room][$bench])) {
                $arrangedData[$buildingId][$room][$bench] = [];
            }

            // Fetch the student details
            $student = $detail->student; // Eager-loaded student
            $studentData = [
                // 'id' => $student->id,
                'name' => $detail->student_name,
                'class' => $detail->student_class ?? 'N/A', // Check if class exists before accessing
                'section' => $detail->student_section ?? 'N/A', // Check if section exists before accessing
                // 'gender' => $student->gender,
                // 'handicapped' => $student->handicapped,
                'roll_no' => $detail->student_roll_no,
            ];
            $studentDataForAttendance[$buildingId][$room][] = $studentData;

            // Add the student data to the seat
            $arrangedData[$buildingId][$room][$bench][$seat] = $studentData;

            // 1. Group by Building and Room (roll numbers)
            $groupedByBuildingAndRoom[$buildingId][$room][] = $studentData['roll_no'];

            // 2. Group by Building, Room, and Class (roll numbers)
            $classTitle = $studentData['class'];
            if (!isset($groupedByBuildingRoomClass[$buildingId][$room][$classTitle])) {
                $groupedByBuildingRoomClass[$buildingId][$room][$classTitle] = [];
            }
            $groupedByBuildingRoomClass[$buildingId][$room][$classTitle][] = $studentData['roll_no'];

            // 3. Group by Building, Room, Class, and Section (roll numbers)
            $sectionTitle = $studentData['section'];
            $classSectionTitle = $classTitle . ' ' . $sectionTitle;
            if (!isset($groupedByBuildingRoomClassSection[$buildingId][$room][$classSectionTitle])) {
                $groupedByBuildingRoomClassSection[$buildingId][$room][$classSectionTitle] = [];
            }
            $groupedByBuildingRoomClassSection[$buildingId][$room][$classSectionTitle][] = $studentData['roll_no'];
        }
        $data['arrangedData'] = $arrangedData;
        $data['studentDataForAttendance'] = $studentDataForAttendance;
        $data['groupedByBuildingAndRoom'] = $groupedByBuildingAndRoom;
        $data['groupedByBuildingRoomClass'] = $groupedByBuildingRoomClass;
        $data['groupedByBuildingRoomClassSection'] = $groupedByBuildingRoomClassSection;
        $data['configs'] = HelperFile::getSchoolConfigs();
        // Output the grouped data
        // dd($groupedByBuildingAndRoom);


        // Step 6: Return view with arranged data
        return view('admin.seat-plan.show', compact('data'));
    }
    
    public function roomEdit($id){
        return view('admin.seat-plan.room-edit');
    }

    public function generateSeatPlan(Request $request)
    {
        // dd($request->all());
        // try {
        // // 1. Create a seat plan record in seat_plans table
        $seatPlan = SeatPlan::create([
            'title' => $request->input('title'),
            'user_id' => session('school_id'),
            'added_by' => auth()->id(),
        ]);
        $seat_plan_id = $seatPlan->id; // Get the ID of the created seat plan
        // $seat_plan_id = 1;
        // 2. Get building data from the buildings table using room ids
        $roomIds = array_keys($request->input('rooms')); // Get the room ids (145, 146, etc.)
        $buildings = Building::whereIn('id', $roomIds)->get();

        if ($buildings->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No buildings found for the given room IDs.'
            ], 404);
        }

        // 3. Get the list of students based on class_id & section_id combinations
        $students = [];
        $studentData = [];
        $studentDetails = []; // New array to store student details: [student_id => [name, class_name, section_name, roll_no]]

        foreach ($request->input('sections') as $class_id => $sections) {
            foreach ($sections as $section_id) {
                $sectionStudents = Student::where('school_id', session('school_id'))
                    ->where('class_id', $class_id)
                    ->where('section_id', $section_id)
                    ->with(['class', 'section']) // Eager-load class and section relationships
                    ->orderBy('roll_no', 'asc')
                    ->select('id', 'name','class_id','section_id', 'roll_no') // Select only needed fields from students
                    ->get();
                // dd($sectionStudents);
                // Extract student IDs for existing logic
                $studentIds = $sectionStudents->pluck('id')->toArray();

                // Store students grouped by class_id
                $studentData[$class_id][] = $studentIds;

                // Build studentDetails array
                foreach ($sectionStudents as $student) {
                    $studentDetails[$student->id] = [
                        'name' => $student->name,
                        'class_name' => $student->class ? $student->class->title : 'N/A', // Handle null class
                        'section_name' => $student->section ? $student->section->title : 'N/A', // Handle null section
                        'roll_no' => $student->roll_no,
                    ];
                }
            }
        }

        // Reorder the studentData array based on the classes input order
        $reorderedStudentData = [];
        foreach ($request->input('classes') as $class_id) {
            if (isset($studentData[$class_id])) {
                // Move the class's section students to the reordered array
                $reorderedStudentData[$class_id] = $studentData[$class_id];
            }
        }

        // Now reorder the student IDs according to the selected class order
        $students = [];
        foreach ($request->input('classes') as $class_id) {
            if (isset($studentData[$class_id])) {
                // Merge the section students for the current class
                foreach ($studentData[$class_id] as $sectionStudents) {
                    $students = array_merge($students, $sectionStudents);
                }
            }
        }
        // dd([
        //     $studentDetails,
        //     $reorderedStudentData,
        //     $students
        // ]);

        // echo '<pre>';
        // print_r($reorderedStudentData);
        // echo '</pre>';
        // 4. Shuffle the staff array and get staff data
        $staffIds = $request->input('staff');

        if(!empty($staffIds)){
            shuffle($staffIds); // Shuffle the staff array

            // $user_type_id_of_staff = UserType::where('name', 'staff')->first();
            // $staffs = User::where([
            //     'parent_id' => session('school_id'),
            //     'user_type_id' => $user_type_id_of_staff->id,
            // ])->whereIn('id', $staffIds)->get();

            $staffs = Staff::where([
                'school_id' => session('school_id')
            ])->whereIn('id', $staffIds)->get();
            // dd($staffs->toArray());

            if ($staffs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No staff found for the given staff IDs.'
                ], 404);
            }
        }

        // Initialize arrays to store unassigned students and staff
        $unassigned_students = [];
        $unassigned_staffs = [];

        // 5. Initialize response arrays
        $seat_plan_details = [];
        $invigilator_plan_details = [];

        // 6. Determine seating pattern and call corresponding function
        $seatingPattern = $request->input('seatingPattern');
        $seatingData = [];

        if ($seatingPattern['type'] == 'sequential') {
            $seatingData = $this->assignSeatsSequentially($buildings, $students,$studentDetails, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } elseif ($seatingPattern['type'] == 'rowbased') {
            $seatingData = $this->assignSeatsRowBased($buildings, $students,$studentDetails, $reorderedStudentData, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } 
        // elseif ($seatingPattern['type'] == 'random') {
            // $seatingData = $this->assignSeatsIndividually($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        // } elseif ($seatingPattern['type'] == 'alternate') {
            // $seatingData = $this->assignSeatsIndividually($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        // } 
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid seating pattern type provided.'
            ], 400);
        }

        // 7. Process the response data for seat plan details
        $seat_plan_details = $seatingData['seat_plan_details'];
        $unassigned_students = $seatingData['unassigned_students'];
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // 8. Initialize staff index and loop through rooms to assign staff to each room
        $staffIndex = 0; // Initialize staffIndex before looping

        // 9. Insert seat plan details for students into the seat_plan_details table
        SeatPlanDetail::Insert($seat_plan_details);

        if(!empty($staffIds)){
            foreach ($buildings as $building) {
                // Decode the rooms JSON to an array
                $rooms = json_decode($building->rooms, true); // Decode rooms data from JSON

                // Process only the rooms selected by the user
                foreach ($rooms as $roomIndex => $roomData) {
                    // Check if this room is selected by the user
                    if (isset($request->input('rooms')[$building->id]) && in_array($roomIndex, $request->input('rooms')[$building->id])) {
                        if ($staffIndex < count($staffs)) {
                            // If there's still staff available for the room
                            $invigilator_plan_details[] = [
                                'seat_plan_id' => $seat_plan_id,
                                'building_id' => $building->id,
                                'room' => $roomIndex, // Room index (0, 1, 2, etc.)
                                'staff_id' => $staffs[$staffIndex]->id,
                                'staff_name' => $staffs[$staffIndex]->name,
                                'staff_department' => $staffs[$staffIndex]->department->title,
                                'staff_position' => $staffs[$staffIndex]->position->title,
                                'created_at' => $now,
                                'updated_at' => $now
                            ];
                            $staffIndex++; // Increment the staff index
                        }
                        // No else block needed; rooms without staff will simply not have an invigilator assigned
                    }
                }
            }
            // 10. Insert invigilator plan details into the invigilator_plan_details table
            InvigilatorPlanDetail::Insert($invigilator_plan_details);
        }
        
        // Collect unassigned staff
        while ($staffIndex < count($staffs)) {
            // If there are staff left without rooms assigned
            $unassigned_staffs[] = $staffs[$staffIndex]->id;
            $staffIndex++; // Move to the next staff
        }

        // dd($seat_plan_details);
        // dd($seat_plan_details);

        // 11. Save unassigned students and staff as JSON in the seat_plans table
        $seatPlan->update([
            'unassigned_students' => json_encode($unassigned_students),
            'unassigned_staffs' => json_encode($unassigned_staffs)
        ]);
        // dd('here123');

        return response()->json([
            'status' => 'success',
            'message' => 'Seat plan generated successfully.',
            'seatPlanId' => $seat_plan_id
        ], 200);
        // } 
        // catch (\Exception $e) {
        //     // Catch any exceptions that occur and return a generic error message
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
    }


    private function assignSeatsSequentially($buildings, $students, $studentDetails,$seat_plan_id, &$seat_plan_details, &$unassigned_students, $selectedRooms)
    {
        $studentIndex = 0;
        $now = Carbon::now()->format('Y-m-d H:i:s'); // Format the current timestamp for MySQL

        foreach ($buildings as $building) {
            $rooms = json_decode($building->rooms, true); // Decode rooms data from JSON

            foreach ($rooms as $roomIndex => $roomData) {
                // Process only the rooms selected by the user
                if (isset($selectedRooms[$building->id]) && in_array($roomIndex, $selectedRooms[$building->id])) {

                    // Check the type of seating for the room (individual or total)
                    if ($roomData['selected_type'] == 'individual') {
                        // Individual seating: Loop through each row and bench, assign seats
                        // foreach ($roomData['individual'] as $rowIndex => $row) {
                        //     foreach ($row['bench'] as $benchIndex => $bench) {
                        //         for ($seatIndex = 0; $seatIndex < $bench['seats']; $seatIndex++) {
                        //             // Check if there are students to assign
                        //             if ($studentIndex < count($students)) {
                        //                 //here is issue
                        //                 $seat_plan_details[] = [
                        //                     'seat_plan_id' => $seat_plan_id,
                        //                     'building_id' => $building->id,
                        //                     'room' => $roomIndex,
                        //                     'bench' => $bench['name'],  // Use the bench name directly from the data
                        //                     'seat' => $seatIndex + 1,   // Assign seat number starting from 1
                        //                     'student_id' => $students[$studentIndex],
                        //                     'student_name' => $student['name'],
                        //                     'student_class' => $student['class_name'],
                        //                     'student_section' => $student['section_name'],
                        //                     'student_roll_no' => $student['roll_no'],
                        //                     'created_at' => $now,
                        //                     'updated_at' => $now
                        //                 ];
                        //                 $studentIndex++;
                        //             } else {
                        //                 // Break if no students are left to assign
                        //                 break 2;
                        //             }
                        //         }
                        //     }
                        // }
                    } elseif ($roomData['selected_type'] == 'total') {
                        // Total seating: All benches are treated as a single block
                        $totalBenches = $roomData['total']['benches'];  // Number of benches
                        $seatsPerBench = $roomData['total']['seats'];  // Seats per bench

                        for ($benchIndex = 0; $benchIndex < $totalBenches; $benchIndex++) {
                            for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
                                $studentId = $students[$studentIndex];
                                $student = $studentDetails[$studentId] ?? [
                                    'name' => $studentDetails[$studentId]['name'],
                                    'class_name' => $studentDetails[$studentId]['class_name'] ?? 'N/A',
                                    'section_name' => $studentDetails[$studentId]['section_name'] ?? 'N/A',
                                    'roll_no' => $studentDetails[$studentId]['roll_no'] ?? 'N/A',
                                ];
                                // Check if there are students to assign
                                if ($studentIndex < count($students)) {
                                    $seat_plan_details[] = [
                                        'seat_plan_id' => $seat_plan_id,
                                        'building_id' => $building->id,
                                        'room' => $roomIndex,
                                        'bench' => "Bench " . ($benchIndex + 1),  // Assign the bench number
                                        'seat' => $seatIndex + 1,                 // Assign seat number
                                        'student_id' => $studentId,
                                        'student_name' => $student['name'],
                                        'student_class' => $student['class_name'],
                                        'student_section' => $student['section_name'],
                                        'student_roll_no' => $student['roll_no'],
                                        'created_at' => $now,
                                        'updated_at' => $now
                                    ];
                                    $studentIndex++;
                                } else {
                                    // Break if no students are left to assign
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        }

        // If there are any students left, add them to the unassigned students list
        if ($studentIndex < count($students)) {
            // Add remaining students to unassigned_students
            for ($i = $studentIndex; $i < count($students); $i++) {
                $unassigned_students[] = $students[$i];
            }
        }
        // dd(['seat_plan_details' => $seat_plan_details, 'unassigned_students' => $unassigned_students]);

        return ['seat_plan_details' => $seat_plan_details, 'unassigned_students' => $unassigned_students];
    }

    private function assignSeatsRowBased($buildings, $students, $studentDetails, $studentClassWiseData, $seat_plan_id, &$seat_plan_details, &$unassigned_students, $selectedRooms)
    {
        $studentIndex = 0;
        $now = Carbon::now()->format('Y-m-d H:i:s'); // Current timestamp

        // Initialize class rotation data
        $classIds = array_keys($studentClassWiseData); // Ordered list of class IDs
        $activeClasses = $classIds; // Track classes with remaining students

        // Iterate through all buildings
        foreach ($buildings as $building) {
            $rooms = json_decode($building->rooms, true); // Decode room data

            // Iterate through selected rooms
            foreach ($rooms as $roomIndex => $roomData) {
                // Process only selected rooms
                if (isset($selectedRooms[$building->id]) && in_array($roomIndex, $selectedRooms[$building->id])) {
                    // Ensure we're working with 'total' seating
                    if ($roomData['selected_type'] == 'total') {
                        $totalBenches = $roomData['total']['benches']; // Number of benches
                        $seatsPerBench = $roomData['total']['seats']; // Seats per bench

                        // Divide the benches equally into two rows
                        $row1Benches = ceil($totalBenches / 2); // First row
                        $row2Benches = $totalBenches - $row1Benches; // Second row

                        // Assign students to Row 1 benches
                        for ($benchIndex = 0; $benchIndex < $row1Benches; $benchIndex++) {
                            if ($studentIndex >= count($students) || empty($activeClasses)) {
                                break 2; // Exit both loops if no students or classes remain
                            }
                            $this->assignSeatsToRow($studentClassWiseData,$studentDetails, $students, $studentIndex, $seat_plan_id, $seat_plan_details, $building->id, $roomIndex, $benchIndex + 1, $seatsPerBench, $now, $activeClasses);
                        }

                        // Assign students to Row 2 benches
                        for ($benchIndex = 0; $benchIndex < $row2Benches; $benchIndex++) {
                            if ($studentIndex >= count($students) || empty($activeClasses)) {
                                break 2; // Exit both loops if no students or classes remain
                            }
                            $this->assignSeatsToRow($studentClassWiseData,$studentDetails, $students, $studentIndex, $seat_plan_id, $seat_plan_details, $building->id, $roomIndex, $row1Benches + $benchIndex + 1, $seatsPerBench, $now, $activeClasses);
                        }
                    }
                }
            }
        }

        // Add remaining students to unassigned list
        if ($studentIndex < count($students)) {
            for ($i = $studentIndex; $i < count($students); $i++) {
                $unassigned_students[] = $students[$i];
            }
        }

        return ['seat_plan_details' => $seat_plan_details, 'unassigned_students' => $unassigned_students];
    }

    private function assignSeatsToRow(&$studentClassWiseData, &$studentsDetailData, &$students, &$studentIndex, $seat_plan_id, &$seat_plan_details, $buildingId, $roomIndex, $benchNumber, $seatsPerBench, $now, &$activeClasses)
    {
        $totalActiveClasses = count($activeClasses); // Number of classes with remaining students
        $usedClasses = []; // Track classes used on this bench
        $classCounter = 0; // Track current class index for rotation

        // If only one class remains, assign one student per bench to seat 1
        if ($totalActiveClasses === 1) {
            $classId = $activeClasses[0];
            if ($studentIndex < count($students) && !empty($studentClassWiseData[$classId])) {
                // Take one student from the current class
                $studentId = array_shift($studentClassWiseData[$classId][0]);

                // If the current section is empty, move to the next section
                if (empty($studentClassWiseData[$classId][0])) {
                    array_shift($studentClassWiseData[$classId]);
                }

                // If the class is now empty, remove it
                if (empty($studentClassWiseData[$classId])) {
                    unset($studentClassWiseData[$classId]);
                    $activeClasses = array_values(array_keys($studentClassWiseData));
                }
                // Assign the student to the first seat of the bench with details
                $student = $studentsDetailData[$studentId] ?? [
                    'name' => $studentsDetailData[$studentId]['name'],
                    'class_name' => $studentsDetailData[$studentId]['class_name'] ?? 'N/A',
                    'section_name' => $studentsDetailData[$studentId]['section_name'] ?? 'N/A',
                    'roll_no' => $studentsDetailData[$studentId]['roll_no'] ?? 'N/A',
                ];
                // Assign the student to the first seat of the bench
                $seat_plan_details[] = [
                    'seat_plan_id' => $seat_plan_id,
                    'building_id' => $buildingId,
                    'room' => $roomIndex,
                    'bench' => "Bench " . $benchNumber,
                    'seat' => 1,
                    'student_id' => $studentId,
                    // 'student_id' => null,
                    'student_name' => $student['name'],
                    'student_class' => $student['class_name'],
                    'student_section' => $student['section_name'],
                    'student_roll_no' => $student['roll_no'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $studentIndex++; // Move to the next student
            }
            return; // Exit to ensure one student per bench
        }

        // Assign students to each seat on the bench (multiple classes)
        for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
            // Stop if no students or active classes are left
            if ($studentIndex >= count($students) || empty($activeClasses)) {
                break;
            }

            // Try to find a valid class for this seat
            $startCounter = $classCounter;
            $triedAllClasses = false;
            $assigned = false;

            while (!$triedAllClasses && !$assigned) {
                $classId = $activeClasses[$classCounter % $totalActiveClasses];

                // Check if the class has students and hasn't been used on this bench
                if (isset($studentClassWiseData[$classId]) && !empty($studentClassWiseData[$classId]) && !in_array($classId, $usedClasses)) {
                    // Assign student from the current class
                    $studentId = array_shift($studentClassWiseData[$classId][0]);

                    // If the current section is empty, move to the next section
                    if (empty($studentClassWiseData[$classId][0])) {
                        array_shift($studentClassWiseData[$classId]);
                    }

                    // If the class is now empty, remove it
                    if (empty($studentClassWiseData[$classId])) {
                        unset($studentClassWiseData[$classId]);
                        $activeClasses = array_values(array_keys($studentClassWiseData));
                        $totalActiveClasses = count($activeClasses);
                        if ($totalActiveClasses > 0) {
                            $classCounter = $classCounter % $totalActiveClasses;
                        }
                    }

                    // Assign the student to the first seat of the bench with details
                    $student = $studentsDetailData[$studentId] ?? [
                        'name' => $studentsDetailData[$studentId]['name'],
                        'class_name' => $studentsDetailData[$studentId]['class_name'] ?? 'N/A',
                        'section_name' => $studentsDetailData[$studentId]['section_name'] ?? 'N/A',
                        'roll_no' => $studentsDetailData[$studentId]['roll_no'] ?? 'N/A',
                    ];
                    // Assign the seat to the student
                    $seat_plan_details[] = [
                        'seat_plan_id' => $seat_plan_id,
                        'building_id' => $buildingId,
                        'room' => $roomIndex,
                        'bench' => "Bench " . $benchNumber,
                        'seat' => $seatIndex + 1,
                        'student_id' => $studentId,
                        // 'student_id' => null,
                        'student_name' => $student['name'],
                        'student_class' => $student['class_name'],
                        'student_section' => $student['section_name'],
                        'student_roll_no' => $student['roll_no'],
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    $usedClasses[] = $classId; // Mark class as used on this bench
                    $studentIndex++; // Move to the next student
                    $classCounter++; // Move to the next class
                    $assigned = true; // Mark as assigned to exit the while loop
                } else {
                    // Move to the next class
                    $classCounter++;
                    if ($classCounter % $totalActiveClasses === $startCounter % $totalActiveClasses) {
                        $triedAllClasses = true; // We've tried all available classes
                    }
                }
            }

            // If no valid class was found (all remaining classes are used on this bench), skip the seat
            if (!$assigned) {
                break; // Move to the next seat or end the loop
            }
        }
    }

    private function assignSeatsToRow3(&$studentClassWiseData, &$students, &$studentIndex, $seat_plan_id, &$seat_plan_details, $buildingId, $roomIndex, $benchNumber, $seatsPerBench, $now, &$classIds, $totalClasses)
    {
        // For each seat on the bench, assign students from different classes and sections alternately
        $finishedAssigning = false; // Flag to control when to stop assigning students
        $classCounter = 0; // Counter to rotate between classes

        // For each seat on the bench
        for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
            // Check if there are students to assign
            echo 'seatIndex -' . $seatIndex . '<br>';
            echo 'studentIndex -' . $studentIndex . '<br>';

            // Check if there are students to assign
            if ($studentIndex < count($students)) {
                // Get the class ID for the current seat (alternating between classes)
                $classId = $classIds[$classCounter % $totalClasses];
                echo 'classId -' . $classId . '<br>';

                // If the class has students
                if (isset($studentClassWiseData[$classId]) && !empty($studentClassWiseData[$classId])) {
                    // Assign student from the current class and section
                    $studentId = array_shift($studentClassWiseData[$classId][0]); // Get the first student in the first section of the current class
                    echo 'studentId -' . $studentId . '<br>';

                    // If the current section is exhausted, move to the next section
                    if (empty($studentClassWiseData[$classId][0])) {
                        array_shift($studentClassWiseData[$classId]); // Move to the next section if the current section is empty
                    }
                } else {
                    $classId = $classIds[$classCounter % $totalClasses];
                    echo 'no students left in class - ' . $classId . '<br>';
                    // If no students are left in the current class, move to the next class
                    $classCounter++;

                    // If we have gone past the available classes, set the flag to stop
                    if ($classCounter >= $totalClasses) {
                        $finishedAssigning = true;
                        break;
                    }

                    // Get the first student from the next class
                    $classId = $classIds[$classCounter % $totalClasses];
                    $studentId = array_shift($studentClassWiseData[$classId][0]);
                }

                // Assign the seat to the student
                $seat_plan_details[] = [
                    'seat_plan_id' => $seat_plan_id,
                    'building_id' => $buildingId,
                    'room' => $roomIndex,
                    'bench' => "Bench " . $benchNumber,  // Assign bench number
                    'seat' => $seatIndex + 1,             // Assign seat number starting from 1
                    'student_id' => $studentId,          // Assign student ID
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $studentIndex++; // Move to the next student
            } else {
                // No more students to assign, set the flag
                $finishedAssigning = true;
                break;
            }

            // If we are done assigning, exit the loop
            if ($finishedAssigning) {
                break;
            }

            // Move to the next class (round-robin)
            $classCounter++;
        }
    }



    private function assignSeatsToRow2(&$studentClassWiseData, &$studentIndex, $seat_plan_id, &$seat_plan_details, $buildingId, $roomIndex, $benchNumber, $seatsPerBench, $now)
    {
        // For each seat on the bench, assign students from different classes and sections alternately
        $classIndex = 0; // Track the class we're assigning to
        $finishedAssigning = false; // Flag to control when to stop assigning students

        // For each seat on the bench
        for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
            // Check if there are students to assign
            if ($studentIndex < count($studentClassWiseData)) {
                echo 'studentIndexInsideAssignSeatsToRow - ' . $studentIndex . '<br>';
                $classId = array_keys($studentClassWiseData)[$classIndex % count($studentClassWiseData)]; // Get the class ID for the current seat
                echo 'Class Id -' . $classId . '<br>';
                if (isset($studentClassWiseData[$classId]) && !empty($studentClassWiseData[$classId])) {
                    // Assign student from the current class and section
                    $studentId = array_shift($studentClassWiseData[$classId][0]); // Get the first student in the first section of the current class
                    echo 'studentId Id -' . $studentId . '<br>';
                    if (empty($studentClassWiseData[$classId][0])) {
                        array_shift($studentClassWiseData[$classId]); // Move to the next section if the current section is empty
                    }
                } else {
                    // If no students are left in this class, move to the next class
                    $classIndex++;
                    if (isset($studentClassWiseData[array_keys($studentClassWiseData)[$classIndex]])) {
                        $studentId = array_shift($studentClassWiseData[array_keys($studentClassWiseData)[$classIndex]][0]); // Get the first student from the next section
                    } else {
                        // If no students left, set the flag to stop the loop
                        echo 'finishedAssigning -' . '<br>';
                        $finishedAssigning = true;
                        break;
                    }
                }

                // Assign the seat to the student
                $seat_plan_details[] = [
                    'seat_plan_id' => $seat_plan_id,
                    'building_id' => $buildingId,
                    'room' => $roomIndex,
                    'bench' => "Bench " . $benchNumber,  // Assign bench number
                    'seat' => $seatIndex + 1,             // Assign seat number starting from 1
                    'student_id' => $studentId,          // Assign class name
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $studentIndex++; // Move to the next student
                $classIndex++; // Move to the next class (round-robin)
            } else {
                // No more students to assign, set the flag
                $finishedAssigning = true;
                break;
            }

            // If we are done assigning, exit the loop
            if ($finishedAssigning) {
                break;
            }
        }
    }
    private function assignSeatsToRow1(&$studentsByClass, &$distinctClasses, &$studentIndex, $seat_plan_id, &$seat_plan_details, $buildingId, $roomIndex, $benchNumber, $seatsPerBench, $now)
    {
        // For each seat on the bench, assign students in alternating class order
        $classIndex = 0; // Track the class we're assigning to
        $finishedAssigning = false; // Flag to control when to stop assigning students

        // For each seat on the bench
        for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
            // Check if there are students to assign
            if ($studentIndex < count($studentsByClass)) {
                $classId = $distinctClasses[$classIndex % count($distinctClasses)]; // Get the class for the current seat
                if (isset($studentsByClass[$classId]) && !empty($studentsByClass[$classId])) {
                    $studentId = array_shift($studentsByClass[$classId]); // Get the first student from the current class
                } else {
                    // If no students are left in this class, move to the next class
                    $classIndex++;
                    if (isset($studentsByClass[$distinctClasses[$classIndex % count($distinctClasses)]])) {
                        $studentId = array_shift($studentsByClass[$distinctClasses[$classIndex % count($distinctClasses)]]); // Get the first student from the next class
                    } else {
                        // If no students left, set the flag to stop the loop
                        $finishedAssigning = true;
                        break;
                    }
                }

                // Assign the seat to the student
                $seat_plan_details[] = [
                    'seat_plan_id' => $seat_plan_id,
                    'building_id' => $buildingId,
                    'room' => $roomIndex,
                    'bench' => "Bench " . $benchNumber,  // Assign bench number
                    'seat' => $seatIndex + 1,             // Assign seat number starting from 1
                    'student_id' => $studentId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $studentIndex++; // Move to the next student
                $classIndex++; // Move to the next class (round-robin)
            } else {
                // No more students to assign, set the flag
                $finishedAssigning = true;
                break;
            }

            // If we are done assigning, exit the loop
            if ($finishedAssigning) {
                break;
            }
        }
    }


    private function assignSeatsIndividually($buildings, $students, $seat_plan_id, &$seat_plan_details, &$unassigned_students, $selectedRooms)
    {
        $studentIndex = 0;

        foreach ($buildings as $building) {
            $rooms = json_decode($building->rooms, true); // Decode rooms data from JSON

            foreach ($rooms as $roomIndex => $roomData) {
                // Process only the rooms selected by the user
                if (isset($selectedRooms[$building->id]) && in_array($roomIndex, $selectedRooms[$building->id])) {
                    foreach ($roomData['individual'] as $rowIndex => $row) {
                        foreach ($row['bench'] as $benchIndex => $bench) {
                            for ($seatIndex = 0; $seatIndex < $bench['seats']; $seatIndex++) {
                                if ($studentIndex < count($students)) {
                                    $seat_plan_details[] = new SeatPlanDetail([
                                        'seat_plan_id' => $seat_plan_id,
                                        'building_id' => $building->id,
                                        'room' => $roomIndex,
                                        'bench' => "Bench " . ($benchIndex + 1),
                                        'seat' => $seatIndex + 1,
                                        'student_id' => $students[$studentIndex]
                                    ]);
                                    $studentIndex++;
                                } else {
                                    // Add unassigned students
                                    $unassigned_students[] = $students[$studentIndex];
                                    $studentIndex++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['seat_plan_details' => $seat_plan_details, 'unassigned_students' => $unassigned_students];
    }

    private function assignSeatsTotal($buildings, $students, $seat_plan_id, &$seat_plan_details, &$unassigned_students, $selectedRooms)
    {
        $studentIndex = 0;

        foreach ($buildings as $building) {
            $rooms = json_decode($building->rooms, true); // Decode rooms data from JSON

            foreach ($rooms as $roomIndex => $roomData) {
                // Process only the rooms selected by the user
                if (isset($selectedRooms[$building->id]) && in_array($roomIndex, $selectedRooms[$building->id])) {
                    foreach ($roomData['total'] as $totalBenchIndex => $totalBench) {
                        for ($benchIndex = 0; $benchIndex < $totalBench['benches']; $benchIndex++) {
                            for ($seatIndex = 0; $seatIndex < $totalBench['seats']; $seatIndex++) {
                                if ($studentIndex < count($students)) {
                                    $seat_plan_details[] = new SeatPlanDetail([
                                        'seat_plan_id' => $seat_plan_id,
                                        'building_id' => $building->id,
                                        'room' => $roomIndex,
                                        'bench' => "Bench " . ($benchIndex + 1),
                                        'seat' => $seatIndex + 1,
                                        'student_id' => $students[$studentIndex]
                                    ]);
                                    $studentIndex++;
                                } else {
                                    // Add unassigned students
                                    $unassigned_students[] = $students[$studentIndex];
                                    $studentIndex++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['seat_plan_details' => $seat_plan_details, 'unassigned_students' => $unassigned_students];
    }

    public function seatPlanLayout1($id)
    {
        // Step 1: Get seat plan data
        $data['seat_plan'] = SeatPlan::select('id', 'title', 'unassigned_students')
            ->where('id', $id)
            ->first()
            ->toArray();

        // Step 2: Get distinct building IDs from seat_plan_details
        $buildingIds = SeatPlanDetail::where('seat_plan_id', $id)
            ->distinct()
            ->pluck('building_id');

        // Step 3: Get building information from the buildings table
        $buildings = Building::whereIn('id', $buildingIds)
            ->get();

        // Step 4: Group seat_plan_details by building_id and count students
        // $studentCounts = SeatPlanDetail::where('seat_plan_id', $id)
        //     ->groupBy('building_id')
        //     ->selectRaw('building_id, COUNT(student_id) as student_count')
        //     ->pluck('student_count', 'building_id'); // This will give a map of building_id => student_count

        // Step 5: Add the student count to each building in the buildings array
        // $buildingData = $buildings->map(function ($building) use ($studentCounts) {
        //     $building->student_count = $studentCounts->get($building->id, 0); // Default to 0 if no students found
        //     return $building;
        // });

        // Combine seat plan data with building data
        // $data['buildings'] = $buildingData->toArray();
        $data['buildings'] = $buildings->toArray();

        // Debugging: Dump the seat plan data
        // dd($data);

        // Step 6: Return view with data
        return view('admin.seat-plan.show', compact('data'));
    }
}
