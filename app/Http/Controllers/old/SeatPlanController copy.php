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
        $user_type_id_of_staff = UserType::where('name','staff')->first();
        $data['staffs'] = User::where([
            'parent_id' => session('school_id'),
            'user_type_id' => $user_type_id_of_staff->id,

        ])->with('staff.department:id,title')->get()->toArray();

        return view('admin.seat-plan.config', compact('data'));
    }

    public function seatPlanConfigV3()
    {
        return view('admin.seat-plan.config-2');
    }

    public function generateSeatPlan2(Request $request)
    {
        // 1. Create a seat plan record in seat_plans table
        $seatPlan = SeatPlan::create([
            'title' => $request->input('title'),
            'user_id' => session('school_id'),
            'added_by' => auth()->id(),
        ]);

        $seat_plan_id = $seatPlan->id; // Get the ID of the created seat plan

        // 2. Get building data from the buildings table using room ids
        $roomIds = array_keys($request->input('rooms')); // Get the room ids (145, 146, etc.)
        $buildings = Building::whereIn('id', $roomIds)->get();

        // 3. Get the list of students based on class_id & section_id combinations
        $students = [];
        foreach ($request->input('sections') as $class_id => $sections) {
            foreach ($sections as $section_id) {
                $students = array_merge($students, Student::where('school_id', session('school_id'))
                    ->where('class_id', $class_id)
                    ->where('section_id', $section_id)
                    ->pluck('id')
                    ->toArray());
            }
        }

        // 4. Shuffle the staff array and get staff data
        $staffIds = $request->input('staff');
        shuffle($staffIds); // Shuffle the staff array

        $staffs = Staff::where('school_id', session('school_id'))
            ->whereIn('id', $staffIds)
            ->get();

        // Initialize arrays to store unassigned students and staff
        $unassigned_students = [];
        $unassigned_staffs = [];

        // 5. Process the seating pattern
        $seatingPattern = $request->input('seatingPattern');
        $seat_plan_details = [];
        $invigilator_plan_details = [];
        $studentIndex = 0; // Index to iterate over the students
        $staffIndex = 0; // Index to iterate over the staff
        dd($buildings);
        foreach ($buildings as $building) {
            // Process rooms for each building
            foreach ($building->rooms as $roomIndex => $roomData) {
                $selectedType = $roomData['selected_type']; // "individual" or "total"

                // 6. Assign students to seats based on the seating pattern type
                if ($selectedType === 'individual') {
                    // Process individual seating
                    foreach ($roomData['individual'] as $rowIndex => $row) {
                        foreach ($row['bench'] as $benchIndex => $bench) {
                            // Assign students to seats in the bench
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
                                    // Add unassigned students if there are more students than seats
                                    $unassigned_students[] = $students[$studentIndex];
                                    $studentIndex++;
                                }
                            }
                        }
                    }
                } else if ($selectedType === 'total') {
                    // Process total seating (e.g., seats per bench)
                    foreach ($roomData['total'] as $totalBenchIndex => $totalBench) {
                        // Assign students to total benches
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
                                    // Add unassigned students if there are more students than seats
                                    $unassigned_students[] = $students[$studentIndex];
                                    $studentIndex++;
                                }
                            }
                        }
                    }
                }
            }

            // 7. Assign staff to rooms (one staff per room)
            foreach ($staffs as $staffIndex => $staff) {
                if ($staffIndex < count($building->rooms)) {
                    $invigilator_plan_details[] = new InvigilatorPlanDetail([
                        'seat_plan_id' => $seat_plan_id,
                        'building_id' => $building->id,
                        'room' => $staffIndex, // Room index (0, 1, 2, etc.)
                        'staff_id' => $staff->id,
                    ]);
                } else {
                    // Add unassigned staff if there are more staff than rooms
                    $unassigned_staffs[] = $staff->id;
                }
            }
        }

        // 8. Insert seat plan details for students into the seat_plan_details table
        SeatPlanDetail::insert($seat_plan_details);

        // 9. Insert invigilator plan details into the invigilator_plan_details table
        InvigilatorPlanDetail::insert($invigilator_plan_details);

        // 10. Save unassigned students and staff as JSON in the seat_plans table
        $seatPlan->update([
            'unassigned_students' => json_encode($unassigned_students),
            'unassigned_staffs' => json_encode($unassigned_staffs)
        ]);

        // Return a success response or redirect to the seat plan index page
        return redirect()->route('admin.seat-plan.index')->with('success', 'Seat plan generated successfully.');
    }

    public function generateSeatPlan3(Request $request)
    {
        // 1. Create a seat plan record in seat_plans table
        $seatPlan = SeatPlan::create([
            'title' => $request->input('title'),
            'user_id' => session('school_id'),
            'added_by' => auth()->id(),
        ]);
        // dd($request->all());

        $seat_plan_id = $seatPlan->id; // Get the ID of the created seat plan

        // 2. Get building data from the buildings table using room ids
        $roomIds = array_keys($request->input('rooms')); // Get the room ids (145, 146, etc.)
        $buildings = Building::whereIn('id', $roomIds)->get();

        // 3. Get the list of students based on class_id & section_id combinations
        $students = [];
        foreach ($request->input('sections') as $class_id => $sections) {
            foreach ($sections as $section_id) {
                $students = array_merge($students, Student::where('school_id', session('school_id'))
                    ->where('class_id', $class_id)
                    ->where('section_id', $section_id)
                    ->pluck('id')
                    ->toArray());
            }
        }

        // 4. Shuffle the staff array and get staff data
        $staffIds = $request->input('staff');
        shuffle($staffIds); // Shuffle the staff array

        // $staffs = Staff::where('school_id', session('school_id'))
        //     ->whereIn('id', $staffIds)
        //     ->get();
            
        $user_type_id_of_staff = UserType::where('name','staff')->first();
        $staffs = User::where([
            'parent_id' => session('school_id'),
            'user_type_id' => $user_type_id_of_staff->id,

        ])->whereIn('id', $staffIds)->get();

        // Initialize arrays to store unassigned students and staff
        $unassigned_students = [];
        $unassigned_staffs = [];

        // 5. Initialize response arrays
        $seat_plan_details = [];
        $invigilator_plan_details = [];
        // 6. Determine seating pattern and call corresponding function
        $seatingPattern = $request->input('seatingPattern');
        $seatingData = [];
        
        // dd($seatingPattern);
        if ($seatingPattern['type'] == 'sequential') {
            $seatingData = $this->assignSeatsSequentially($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } elseif ($seatingPattern['type'] == 'individual') {
            $seatingData = $this->assignSeatsIndividually($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } elseif ($seatingPattern['type'] == 'total') {
            $seatingData = $this->assignSeatsTotal($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        }
        // dd($seatingData['']);
        // Convert the SeatPlanDetail instances to an array of attributes
        // $seat_plan_details_data = array_map(function($seatPlanDetail) {
        //     return $seatPlanDetail->toArray();
        // }, $seat_plan_details);
        // dd($seat_plan_details_data);
        // 7. Process the response data for seat plan details
        $seat_plan_details = $seatingData['seat_plan_details'];
        $unassigned_students = $seatingData['unassigned_students'];
        $now = Carbon::now()->format('Y-m-d H:i:s');
        // $now = new DateTime(date('Y-m-d h:i:s'));
        // dd($now);
        // 8. Initialize staff index and loop through rooms to assign staff to each room
        $staffIndex = 0; // Initialize staffIndex before looping
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
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                        $staffIndex++; // Increment the staff index
                    } else {
                        // Add remaining staff to unassigned_staffs
                        $unassigned_staffs[] = $staffs[$staffIndex]->id;
                        $staffIndex++; // Increment the staff index
                    }
                }
            }
        }
        // dd($invigilator_plan_details);
        // 9. Insert seat plan details for students into the seat_plan_details table
        SeatPlanDetail::Insert($seat_plan_details);

        // 10. Insert invigilator plan details into the invigilator_plan_details table
        InvigilatorPlanDetail::Insert($invigilator_plan_details);

        // 11. Save unassigned students and staff as JSON in the seat_plans table
        $seatPlan->update([
            'unassigned_students' => json_encode($unassigned_students),
            'unassigned_staffs' => json_encode($unassigned_staffs)
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Bench type updated successfully.',
            'data' => $building
        ], 200);
        // Return a success response or redirect to the seat plan index page
        return redirect()->route('admin.seat-plan.index')->with('success', 'Seat plan generated successfully.');
    }

    public function generateSeatPlan4(Request $request)
    {
        // 1. Create a seat plan record in seat_plans table
        $seatPlan = SeatPlan::create([
            'title' => $request->input('title'),
            'user_id' => session('school_id'),
            'added_by' => auth()->id(),
        ]);

        $seat_plan_id = $seatPlan->id; // Get the ID of the created seat plan

        // 2. Get building data from the buildings table using room ids
        $roomIds = array_keys($request->input('rooms')); // Get the room ids (145, 146, etc.)
        $buildings = Building::whereIn('id', $roomIds)->get();

        // 3. Get the list of students based on class_id & section_id combinations
        $students = [];
        foreach ($request->input('sections') as $class_id => $sections) {
            foreach ($sections as $section_id) {
                $students = array_merge($students, Student::where('school_id', session('school_id'))
                    ->where('class_id', $class_id)
                    ->where('section_id', $section_id)
                    ->pluck('id')
                    ->toArray());
            }
        }

        // 4. Shuffle the staff array and get staff data
        $staffIds = $request->input('staff');
        shuffle($staffIds); // Shuffle the staff array
            
        $user_type_id_of_staff = UserType::where('name','staff')->first();
        $staffs = User::where([
            'parent_id' => session('school_id'),
            'user_type_id' => $user_type_id_of_staff->id,
        ])->whereIn('id', $staffIds)->get();

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
            $seatingData = $this->assignSeatsSequentially($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } elseif ($seatingPattern['type'] == 'individual') {
            $seatingData = $this->assignSeatsIndividually($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        } elseif ($seatingPattern['type'] == 'total') {
            $seatingData = $this->assignSeatsTotal($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
        }
        
        // 7. Process the response data for seat plan details
        $seat_plan_details = $seatingData['seat_plan_details'];
        $unassigned_students = $seatingData['unassigned_students'];
        $now = Carbon::now()->format('Y-m-d H:i:s');
        
        // 8. Initialize staff index and loop through rooms to assign staff to each room
        $staffIndex = 0; // Initialize staffIndex before looping
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
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                        $staffIndex++; // Increment the staff index
                    } else {
                        // Add remaining staff to unassigned_staffs
                        $unassigned_staffs[] = $staffs[$staffIndex]->id;
                        $staffIndex++; // Increment the staff index
                    }
                }
            }
        }
        
        // 9. Insert seat plan details for students into the seat_plan_details table
        SeatPlanDetail::Insert($seat_plan_details);

        // 10. Insert invigilator plan details into the invigilator_plan_details table
        InvigilatorPlanDetail::Insert($invigilator_plan_details);

        // 11. Save unassigned students and staff as JSON in the seat_plans table
        $seatPlan->update([
            'unassigned_students' => json_encode($unassigned_students),
            'unassigned_staffs' => json_encode($unassigned_staffs)
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Bench type updated successfully.',
            'data' => $building
        ], 200);
    }

    public function generateSeatPlan(Request $request)
    {
        // dd($request->all());
        try {
            // 1. Create a seat plan record in seat_plans table
            $seatPlan = SeatPlan::create([
                'title' => $request->input('title'),
                'user_id' => session('school_id'),
                'added_by' => auth()->id(),
            ]);
            $seat_plan_id = $seatPlan->id; // Get the ID of the created seat plan

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
            foreach ($request->input('sections') as $class_id => $sections) {
                foreach ($sections as $section_id) {
                    $sectionStudents = Student::where('school_id', session('school_id'))
                        ->where('class_id', $class_id)
                        ->where('section_id', $section_id)
                        ->pluck('id')
                        ->toArray();
                    
                    // Store students grouped by class_id
                    $studentData[$class_id][] = $sectionStudents;
                }
            }
        // dd($studentData);

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


            // 4. Shuffle the staff array and get staff data
            $staffIds = $request->input('staff');
            shuffle($staffIds); // Shuffle the staff array
                
            $user_type_id_of_staff = UserType::where('name', 'staff')->first();
            $staffs = User::where([
                'parent_id' => session('school_id'),
                'user_type_id' => $user_type_id_of_staff->id,
            ])->whereIn('id', $staffIds)->get();

            if ($staffs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No staff found for the given staff IDs.'
                ], 404);
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
                $seatingData = $this->assignSeatsSequentially($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
            } elseif ($seatingPattern['type'] == 'individual') {
                $seatingData = $this->assignSeatsIndividually($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
            } elseif ($seatingPattern['type'] == 'total') {
                $seatingData = $this->assignSeatsTotal($buildings, $students, $seat_plan_id, $seat_plan_details, $unassigned_students, $request->input('rooms'));
            } else {
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
                                'created_at' => $now,
                                'updated_at' => $now
                            ];
                            $staffIndex++; // Increment the staff index
                        } else {
                            // Add remaining staff to unassigned_staffs
                            $unassigned_staffs[] = $staffs[$staffIndex]->id;
                            $staffIndex++; // Increment the staff index
                        }
                    }
                }
            }
            
            // 9. Insert seat plan details for students into the seat_plan_details table
            SeatPlanDetail::Insert($seat_plan_details);

            // 10. Insert invigilator plan details into the invigilator_plan_details table
            InvigilatorPlanDetail::Insert($invigilator_plan_details);

            // 11. Save unassigned students and staff as JSON in the seat_plans table
            $seatPlan->update([
                'unassigned_students' => json_encode($unassigned_students),
                'unassigned_staffs' => json_encode($unassigned_staffs)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Seat plan generated successfully.',
                'seatPlanId' => $seat_plan_id
            ], 200);

        } catch (\Exception $e) {
            // Catch any exceptions that occur and return a generic error message
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function assignSeatsSequentially($buildings, $students, $seat_plan_id, &$seat_plan_details, &$unassigned_students, $selectedRooms)
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
                        foreach ($roomData['individual'] as $rowIndex => $row) {
                            foreach ($row['bench'] as $benchIndex => $bench) {
                                for ($seatIndex = 0; $seatIndex < $bench['seats']; $seatIndex++) {
                                    // Check if there are students to assign
                                    if ($studentIndex < count($students)) {
                                        //here is issue
                                        $seat_plan_details[] = [
                                            'seat_plan_id' => $seat_plan_id,
                                            'building_id' => $building->id,
                                            'room' => $roomIndex,
                                            'bench' => $bench['name'],  // Use the bench name directly from the data
                                            'seat' => $seatIndex + 1,   // Assign seat number starting from 1
                                            'student_id' => $students[$studentIndex],
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
                    } elseif ($roomData['selected_type'] == 'total') {
                        // Total seating: All benches are treated as a single block
                        $totalBenches = $roomData['total']['benches'];  // Number of benches
                        $seatsPerBench = $roomData['total']['seats'];  // Seats per bench

                        for ($benchIndex = 0; $benchIndex < $totalBenches; $benchIndex++) {
                            for ($seatIndex = 0; $seatIndex < $seatsPerBench; $seatIndex++) {
                                // Check if there are students to assign
                                if ($studentIndex < count($students)) {
                                    $seat_plan_details[] = [
                                        'seat_plan_id' => $seat_plan_id,
                                        'building_id' => $building->id,
                                        'room' => $roomIndex,
                                        'bench' => "Bench " . ($benchIndex + 1),  // Assign the bench number
                                        'seat' => $seatIndex + 1,                 // Assign seat number
                                        'student_id' => $students[$studentIndex],
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
        $data['seat_plan'] = SeatPlan::select('id','title','unassigned_students')
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
        $seatPlanDetails = SeatPlanDetail::with(['student', 'student.class', 'student.section']) // Eager load relationships
            ->where('seat_plan_id', $id)
            ->get();

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
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->class ? $student->class->title : 'N/A', // Check if class exists before accessing
                'section' => $student->section ? $student->section->title : 'N/A', // Check if section exists before accessing
                'gender' => $student->gender,
                'handicapped' => $student->handicapped,
                'roll_no' => $student->roll_no,
            ];

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
        $data['groupedByBuildingAndRoom'] = $groupedByBuildingAndRoom;
        $data['groupedByBuildingRoomClass'] = $groupedByBuildingRoomClass;
        $data['groupedByBuildingRoomClassSection'] = $groupedByBuildingRoomClassSection;
        // Output the grouped data
        // dd($groupedByBuildingAndRoom, $groupedByBuildingRoomClass, $groupedByBuildingRoomClassSection);


        // Step 6: Return view with arranged data
        return view('admin.seat-plan.show', compact('data'));
    }
}
