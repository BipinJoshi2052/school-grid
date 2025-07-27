<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function downloadSample()
    {
        // Define the path to the file
        $filePath = public_path('downloadables/Sample Staff Import Data - Sheet1.csv');
        
        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            // Return an error if file does not exist
            return response()->json(['error' => 'File not found'], 404);
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
                
                // $status = ($row[$mappings['Status']] == 0 || strtolower($row[$mappings['Status']]) == 'inactive') ? 0 : 1;

                // Create or find the user based on email
                $user = User::updateOrCreate(
                    ['email' => $userData['email']], // Unique email
                    $userData
                );

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

                // $address = null;
                // if (isset($mappings['Address'])) {
                //     $address = 
                // }

                

                // Department: check if department exists, create if not
                $department = (isset($mappings['Department']) && $row[$mappings['Department']]) ? Department::firstOrCreate(
                    [
                        'title' => $row[$mappings['Department']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Department']] ?? null,'user_id' => session('school_id'),'added_by' => $user->id
                    ]
                ) : null;

                // Position: check if position exists, create if not
                $position = (isset($mappings['Position']) && $row[$mappings['Position']]) ? Position::firstOrCreate(
                    [
                        'title' => $row[$mappings['Position']] ?? null
                    ],
                    [
                        'title' => $row[$mappings['Position']] ?? null,'user_id' => session('school_id'),'added_by' => $user->id
                    ]
                ) : null;

                // Create the staff record, only map values that exist in the mappings
                Staff::updateOrCreate(
                    [
                        'school_id' => session('school_id'),
                        'user_id' => $user->id,
                    ],
                    [
                    'school_id' => session('school_id'),
                    'user_id' => $user->id,
                    'name' => $row[$mappings['Name']] ?? null,
                    'department_id' => $department->id ?? null,
                    'position_id' => $position->id ?? null,
                    'gender' => $gender, // Map gender, if present
                    'joined_date' => $joined_date, // Map joined_date, if present
                    'address' => isset($mappings['Address']) ? $row[$mappings['Address']] : null, // Map address, if present
                ]);

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
