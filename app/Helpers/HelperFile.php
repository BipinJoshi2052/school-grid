<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HelperFile
{
    /**
     * Get the required data for the landing page.
     *
     * @return array
     */
    public static function uploadFile($request,$path)
    {
        if ($request->hasFile('avatar')) {
            // Get the school ID from the session
            $schoolId = session('school_id');
            
            // Define the folder path inside 'public' storage
            $folderPath = "{$schoolId}/{$path}";

            // Create the folder if it does not exist
            $storagePath = storage_path("app/public/{$folderPath}");
            
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);  // Creates the folder and subfolders if they don't exist
            }

            // Store the file in the defined path
            $avatarPath = $request->file('avatar')->store($folderPath, 'public');
            // Log::info('Avatar stored at: ' . $avatarPath);
            // dd($avatarPath);

            return $avatarPath;
        }

        return null;
    }
    
    public static function uploadMultipleFiles($request, $path)
    {
        // Initialize an empty array to store file paths
        $filePaths = [];

        if ($request->hasFile('files')) {
            // Get the school ID from the session (you may need to adjust this to your app's requirements)
            $schoolId = session('school_id');
            
            // Define the folder path inside 'public' storage
            $folderPath = "{$schoolId}/{$path}";

            // Create the folder if it doesn't exist
            $storagePath = storage_path("app/public/{$folderPath}");
            
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);  // Create the folder and subfolders
            }

            // Loop through the uploaded files and store them
            foreach ($request->file('files') as $file) {
                $filePath = $file->store($folderPath, 'public');
                $filePaths[] = $filePath;  // Add file path to the array
            }
        }

        return $filePaths;
    }

    public static function uploadFileSuperAdmin($request,$path,$user_id)
    {
        if ($request->hasFile('avatar')) {
            // Get the school ID from the session
            $schoolId = $user_id;
            
            // Define the folder path inside 'public' storage
            $folderPath = "admin/{$schoolId}/{$path}";

            // Create the folder if it does not exist
            $storagePath = storage_path("app/public/{$folderPath}");
            
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);  // Creates the folder and subfolders if they don't exist
            }

            // Store the file in the defined path
            $avatarPath = $request->file('avatar')->store($folderPath, 'public');
            // Log::info('Avatar stored at: ' . $avatarPath);
            // dd($avatarPath);

            return $avatarPath;
        }

        return null;
    }
    public static function getSchoolConfigs(){
        $configs = [];
        if (app()->environment('local')) {
            if(session('school_id') == 1502){
                $configs = [
                    'custom-seatplan-attendance-print' => 1
                ];
            }
        }else{
            if(session('school_id') == 20){
                $configs = [
                    'custom-seatplan-attendance-print' => 0
                ];
            }
        }

        return $configs;
    }
}
