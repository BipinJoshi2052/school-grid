<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Position;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function downloadSample()
    {
        // Define the path to the file
        $filePath = public_path('downloadables/Sample Staff Import Data.csv');
        
        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            // Return an error if file does not exist
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function downloadSampleStudent()
    {
        // Define the path to the file
        $filePath = public_path('downloadables/Sample Student Import Data.csv');
        
        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            // Return an error if file does not exist
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function validateStaffImport(Request $request)
    {
        // Get the incoming data
        $data = $request->input('data');
        $mappings = $request->input('mappings');

        $invalidEmails = [];
        $updatedEmails = [];
        $errors = [];

        try {
            $schoolId = session('school_id');

            foreach ($data as $row) {
                $email = $row[$mappings['Email']] ?? null;
                // echo $email;

                // Check if the email belongs to the main school account
                $mainSchoolUser = User::where('email', $email)->where('id', $schoolId)->first();
                if ($mainSchoolUser) {
                    $invalidEmails[] = $email;
                    continue; // Skip this row
                }

                // Check if the email exists in a student account
                $existingStudentUser = User::where('email', $email)->where('parent_id', $schoolId)->where('user_type_id',4)->first();
                if ($existingStudentUser) {
                    $invalidEmails[] = $email;
                    continue; // Skip this row
                }

                // Check if the email exists within the same school (by parent_id)
                $existingUser = User::where('email', $email)->where('parent_id', $schoolId)->where('user_type_id',3)->first();
                if ($existingUser) {
                    $updatedEmails[] = $email;
                } else {
                    // Check if email exists elsewhere
                    $existingUserElsewhere = User::where('email', $email)->where('parent_id', '!=', $schoolId)->first();
                    if ($existingUserElsewhere) {
                        $invalidEmails[] = $email;
                        continue; // Skip this row
                    }
                }
            }

            // Collect error messages
            $errorMessages = [];

            if (count($invalidEmails) > 0) {
                $errorMessages[] = "The following emails are already exists and are invalid to be uploaded: " . implode(', ', $invalidEmails);
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessages,
                    'invalidEmails' => count($invalidEmails) > 0
                ], 400);
            }

            if (count($updatedEmails) > 0) {
                $errorMessages[] = "The following emails already exist and will be updated: " . implode(', ', $updatedEmails);
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessages
                ], 200);
            }

            // If no errors, return a success message
            return response()->json([
                'status' => 'success',
                'message' => 'Validation passed. Ready to upload data.',
            ]);
        }
        catch (\Exception $e) {
            // If there is an error, store the error message for this row
            $errors[] = "Error processing row with email: {$row[$mappings['Email']]}. Error: " . $e->getMessage();
        }
    }

    
    public function staffImport(Request $request)
    {
        // Get the incoming data from the request
        $data = $request->input('data');
        $mappings = $request->input('mappings');
        $csvHeadings = $request->input('originalHeadings');

        $imported = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                // Start with required fields, these will always be present
                $userData = [
                    'name' => $row[$mappings['Name']] ?? null, // Map 'Name' if present
                    'email' => $row[$mappings['Email']] ?? null, // Map 'Email' if present
                    'password' => bcrypt('secret'), // Set a default password
                    'parent_id' => session('school_id'),
                    'user_type_id' => 3, // Default user type (3 - staff)
                ];

                // Check if any other columns were mapped and add them to the user data if present
                if (isset($mappings['Phone'])) {
                    $userData['phone'] = $row[$mappings['Phone']] ?? null;
                }

                // Status mapping (set to 0 if 'inactive', 1 if 'active')
                $userData['status'] = 1;
                if (isset($mappings['Status'])) {
                    $userData['status'] =  ($row[$mappings['Status']] == 0 || strtolower($row[$mappings['Status']]) == 'inactive') ? 0 : 1;;
                }

                $userAlreadyExists = 0;
                $user = User::where('email', $userData['email'])->where('parent_id' ,session('school_id'))->first();
                if (!$user) {
                    $userData['added_by'] = auth()->id();
                    
                    // If the user doesn't exist, create a new one and set 'added_by'
                    $user = User::create($userData);
                }else{
                    // If the user exists, update the existing record without modifying 'added_by'
                    $user->update($userData);
                    $userAlreadyExists = 1;

                }
                // Create or find the user based on email
                // $user = User::updateOrCreate(
                //     [
                //         'email' => $userData['email']
                //     ], // Unique email
                //     $userData
                // );

                $gender = isset($mappings['Gender']) ? $this->mapGender($row[$mappings['Gender']] ?? null) : null;

                $joined_date = Carbon::now();  // Default to today's date
                if (isset($mappings['Joined Date'])) {
                    $joinedDateValue = $row[$mappings['Joined Date']];
                    
                    // Check if the value is a valid date
                    try {
                        $joined_date = Carbon::parse($joinedDateValue);  // Try to parse the date
                    } catch (\Exception $e) {
                        // If not a valid date, fallback to the current date
                        $joined_date = Carbon::now();
                    }
                }                

                // Department: check if department exists, create if not
                $department = (isset($mappings['Department']) && $row[$mappings['Department']]) ? Department::firstOrCreate(
                    [
                        'title' => $row[$mappings['Department']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Department']] ?? null,'user_id' => session('school_id'),'added_by' => auth()->id()
                    ]
                ) : null;

                // Position: check if position exists, create if not
                $position = (isset($mappings['Position']) && $row[$mappings['Position']]) ? Position::firstOrCreate(
                    [
                        'title' => $row[$mappings['Position']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Position']] ?? null,'user_id' => session('school_id'),'added_by' => auth()->id()
                    ]
                ) : null;

                if(!$userAlreadyExists){
                    // If the staff doesn't exist, create a new record and set 'added_by'
                    $staff = Staff::create([
                        'school_id' => session('school_id'),
                        'user_id' => $user->id,
                        'name' => $row[$mappings['Name']] ?? null,
                        'department_id' => $department->id ?? null,
                        'position_id' => $position->id ?? null,
                        'gender' => $gender, // Map gender if present
                        'joined_date' => $joined_date, // Map joined_date if present
                        'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address if present
                        'added_by' => auth()->id(),  // Set 'added_by' only for new staff
                    ]);
                }else{
                    $existingStaff = Staff::where('school_id', session('school_id'))
                                    ->where('user_id', $user->id)
                                    ->first();
                    // If the staff already exists, update the existing record without changing 'added_by'
                    $existingStaff->update([
                        'name' => $row[$mappings['Name']] ?? null,
                        'department_id' => $department->id ?? null,
                        'position_id' => $position->id ?? null,
                        'gender' => $gender, // Map gender if present
                        'joined_date' => $joined_date, // Map joined_date if present
                        'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address if present
                    ]);
                }
                // Create the staff record, only map values that exist in the mappings
                // Staff::updateOrCreate(
                //     [
                //         'school_id' => session('school_id'),
                //         'user_id' => $user->id,
                //     ],
                //     [
                //     'school_id' => session('school_id'),
                //     'user_id' => $user->id,
                //     'name' => $row[$mappings['Name']] ?? null,
                //     'department_id' => $department->id ?? null,
                //     'position_id' => $position->id ?? null,
                //     'gender' => $gender, // Map gender, if present
                //     'joined_date' => $joined_date, // Map joined_date, if present
                //     'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address, if present
                //     'added_by' => auth()->id()
                // ]);

                $imported++;
            } catch (\Exception $e) {
                // If there is an error, store the error message for this row
                $errors[] = "Error processing row with email: {$row[$mappings['Email']]}. Error: " . $e->getMessage();
            }
        }

        // Send a success or error response back to the frontend
        if (count($errors) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => $errors,
                'imported' => $imported,
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => "{$imported} records successfully imported.",
            'imported' => $imported,
        ]);
    }

    public function validateStudentImport(Request $request)
    {
        // Get the incoming data
        $data = $request->input('data');
        $mappings = $request->input('mappings');

        $invalidEmails = [];
        $updatedEmails = [];
        $errors = [];

        try {
            $schoolId = session('school_id');

            foreach ($data as $row) {
                $email = $row[$mappings['Email']] ?? null;
                // echo $email;

                // Check if the email belongs to the main school account
                $mainSchoolUser = User::where('email', $email)->where('id', $schoolId)->first();
                if ($mainSchoolUser) {
                    $invalidEmails[] = $email;
                    continue; // Skip this row
                }

                // Check if the email exists in a student account
                $existingStaffUser = User::where('email', $email)->where('parent_id', $schoolId)->where('user_type_id',3)->first();
                if ($existingStaffUser) {
                    $invalidEmails[] = $email;
                    continue; // Skip this row
                }

                // Check if the email exists within the same school (by parent_id)
                $existingUser = User::where('email', $email)->where('parent_id', $schoolId)->where('user_type_id',4)->first();
                if ($existingUser) {
                    $updatedEmails[] = $email;
                } else {
                    // Check if email exists elsewhere
                    $existingUserElsewhere = User::where('email', $email)->where('parent_id', '!=', $schoolId)->first();
                    if ($existingUserElsewhere) {
                        $invalidEmails[] = $email;
                        continue; // Skip this row
                    }
                }
            }

            // Collect error messages
            $errorMessages = [];

            if (count($invalidEmails) > 0) {
                $errorMessages[] = "The following emails are already exists and are invalid to be uploaded: " . implode(', ', $invalidEmails);
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessages,
                    'invalidEmails' => count($invalidEmails) > 0
                ], 400);
            }

            if (count($updatedEmails) > 0) {
                $errorMessages[] = "The following emails already exist and will be updated: " . implode(', ', $updatedEmails);
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessages
                ], 200);
            }

            // If no errors, return a success message
            return response()->json([
                'status' => 'success',
                'message' => 'Validation passed. Ready to upload data.',
            ]);
        }
        catch (\Exception $e) {
            // If there is an error, store the error message for this row
            $errors[] = "Error processing row with email: {$row[$mappings['Email']]}. Error: " . $e->getMessage();
        }
    }

    public function StudentImport(Request $request)
    {
        // Get the incoming data from the request
        $data = $request->input('data');
        $mappings = $request->input('mappings');
        $csvHeadings = $request->input('originalHeadings');

        $imported = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                // Start with required fields, these will always be present
                $userData = [
                    'name' => $row[$mappings['Name']] ?? null, // Map 'Name' if present
                    'email' => $row[$mappings['Email']] ?? null, // Map 'Email' if present
                    'password' => bcrypt('secret'), // Set a default password
                    'parent_id' => session('school_id'),
                    'user_type_id' => 4, // Default user type (3 - staff)
                    'added_by' => auth()->id()
                ];

                // Check if any other columns were mapped and add them to the user data if present
                if (isset($mappings['Phone'])) {
                    $userData['phone'] = $row[$mappings['Phone']] ?? null;
                }

                // Status mapping (set to 0 if 'inactive', 1 if 'active')
                $userData['status'] = 1;
                if (isset($mappings['Status'])) {
                    $userData['status'] =  ($row[$mappings['Status']] == 0 || strtolower($row[$mappings['Status']]) == 'inactive') ? 0 : 1;;
                }

                $handicapped = 0;
                if (isset($mappings['Handicapped'])) {
                    $handicapped =  ($row[$mappings['Handicapped']] == 0 || strtolower($row[$mappings['Handicapped']]) == 'no') ? 0 : 1;;
                }

                $userAlreadyExists = 0;
                $user = User::where('email', $userData['email'])->where('parent_id' ,session('school_id'))->first();
                if (!$user) {
                    $userData['added_by'] = auth()->id();
                    
                    // If the user doesn't exist, create a new one and set 'added_by'
                    $user = User::create($userData);
                }else{
                    // If the user exists, update the existing record without modifying 'added_by'
                    $user->update($userData);
                    $userAlreadyExists = 1;
                }
                // Create or find the user based on email
                // $user = User::updateOrCreate(
                //     [
                //         'email' => $userData['email']
                //     ], // Unique email
                //     $userData
                // );

                $gender = isset($mappings['Gender']) ? $this->mapGender($row[$mappings['Gender']] ?? null) : null;


                // Faculty: check if Faculty exists, create if not
                $faculty = (isset($mappings['Faculty']) && $row[$mappings['Faculty']]) ? Faculty::firstOrCreate(
                    [
                        'title' => $row[$mappings['Faculty']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Faculty']] ?? null,'user_id' => session('school_id'),'added_by' => auth()->id()
                    ]
                ) : null;

                // Batch: check if Batch exists, create if not
                $batch = (isset($mappings['Batch']) && $row[$mappings['Batch']]) ? Batch::firstOrCreate(
                    [
                        'title' => $row[$mappings['Batch']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Batch']] ?? null,
                        'user_id' => session('school_id'),
                        'faculty_id' => $faculty->id,
                        'added_by' => auth()->id()
                    ]
                ) : null;

                // Faculty: check if Faculty exists, create if not
                $class = (isset($mappings['Class']) && $row[$mappings['Class']]) ? ClassModel::firstOrCreate(
                    [
                        'title' => $row[$mappings['Class']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Class']] ?? null,'user_id' => session('school_id'),'added_by' => auth()->id()
                    ]
                ) : null;

                // Position: check if position exists, create if not
                $section = (isset($mappings['Section']) && $row[$mappings['Section']]) ? Section::firstOrCreate(
                    [
                        'title' => $row[$mappings['Section']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Section']] ?? null,
                        'user_id' => session('school_id'),
                        'class_id' => $class->id,
                        'added_by' => auth()->id()
                    ]   
                ) : null;

                if(!$userAlreadyExists){
                    // If the staff doesn't exist, create a new record and set 'added_by'
                    $staff = Student::create([
                        'school_id' => session('school_id'),
                        'user_id' => $user->id,
                        'name' => $row[$mappings['Name']] ?? null,
                        'faculty_id' => $faculty->id ?? null,
                        'batch_id' => $batch->id ?? null,
                        'class_id' => $class->id ?? null,
                        'section_id' => $section->id ?? null,
                        'handicapped' => $handicapped,
                        'gender' => $gender, // Map gender, if present
                        'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address, if present
                        'added_by' => auth()->id()
                    ]);                    
                }else{
                    $existingStudent = Student::where('school_id', session('school_id'))
                                    ->where('user_id', $user->id)
                                    ->first();                    
                                    
                    $existingStudent->update([
                        'school_id' => session('school_id'),
                        'user_id' => $user->id,
                        'name' => $row[$mappings['Name']] ?? null,
                        'faculty_id' => $faculty->id ?? null,
                        'batch_id' => $batch->id ?? null,
                        'class_id' => $class->id ?? null,
                        'section_id' => $section->id ?? null,
                        'handicapped' => $handicapped,
                        'gender' => $gender, // Map gender, if present
                        'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address, if present
                    ]);
                }
                // Create the staff record, only map values that exist in the mappings
                // Student::updateOrCreate(
                //     [
                //         'school_id' => session('school_id'),
                //         'user_id' => $user->id,
                //     ],
                //     [
                //     'school_id' => session('school_id'),
                //     'user_id' => $user->id,
                //     'name' => $row[$mappings['Name']] ?? null,
                //     'faculty_id' => $faculty->id ?? null,
                //     'batch_id' => $batch->id ?? null,
                //     'class_id' => $class->id ?? null,
                //     'section_id' => $section->id ?? null,
                //     'handicapped' => $handicapped,
                //     'gender' => $gender, // Map gender, if present
                //     'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address, if present
                //     'added_by' => auth()->id()
                // ]);

                $imported++;
            } catch (\Exception $e) {
                // If there is an error, store the error message for this row
                $errors[] = "Error processing row with email: {$row[$mappings['Email']]}. Error: " . $e->getMessage();
            }
        }

        // Send a success or error response back to the frontend
        if (count($errors) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => $errors,
                'imported' => $imported,
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => "{$imported} records successfully imported.",
            'imported' => $imported,
        ]);
    }

    private function mapGender($gender)
    {
        // Gender mapping logic
        switch (strtolower($gender)) {
            case 'male':
                return 0;
            case 'female':
                return 1;
            case 'other':
                return 3;
            default:
                return 3; // Default to 'other' if unrecognized gender
        }
    }
}
