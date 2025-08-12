<?php

namespace App\Http\Controllers;

use App\Helpers\HelperFile;
use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.staff.index');
    }

    public function listV2() {
        return view('admin.staff.v2');
    }

    public function listPartial(Request $request) {
        // Get the search term from the request
        $searchTerm = $request->get('search')['value'] ?? '';  // DataTables sends the search term in search[value]

        // Pagination parameters
        $page = $request->get('page', 1);  // Default page is 1
        $perPage = $request->get('pageLength', 10);  // Use the pageLength parameter to get the number of items per page

        // Build the query to filter data
        $query = Staff::where('school_id', session('school_id'))
                        ->with('user')
                        ->with('department')
                        ->with('position')
                        ->orderBy('id', 'desc');

        // Apply the search filter if a search term is provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('department', function ($query) use ($searchTerm) {
                    $query->where('title', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('position', function ($query) use ($searchTerm) {
                    $query->where('title', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Paginate the results
        $users = $query->paginate($perPage);

        // Return the response in JSON format for DataTables
        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(), // Since we're not using a separate filtered count, this is the same as recordsTotal
            'data' => $users->items()  // Return the paginated data
        ]);
    }

    
    public function createPartial(){
        $departments = Department::select('id','title')->where('user_id',session('school_id'))->get();
        $positions = Position::select('id','title')->where('user_id',session('school_id'))->get();
        return view('admin.staff.create-partial', compact('departments','positions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::select('id','title')->where('user_id',session('school_id'))->get();
        $positions = Position::select('id','title')->where('user_id',session('school_id'))->get();
        return view('admin.staff.create-partial', compact('departments','positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $staff = Staff::create($request->all());
    //     return response()->json($staff, 201);
    // }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|min:10',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'joined_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
            // 'department_id' => 'required|exists:departments,id',
            // 'position_id' => 'required|exists:positions,id',
        // dd('here');
        try{
            // Create the user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('secret'),  // Set password as 'secret'
                'phone' => $request->phone,
                'avatar' => HelperFile::uploadFile($request,'avatars/staff'), // Handle avatar upload
                'parent_id' => session('school_id'),
                'user_type_id' => 3, // Staff
                'added_by' => auth()->id(),
            ]);

            // Create the staff record
            $staff = Staff::create([
                'name' => $request->name,
                'school_id' => session('school_id'),
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'address' => $request->address,
                'gender' => $request->gender,
                'joined_date' => $request->joined_date,
                'added_by' => auth()->id(),
            ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);    
        }

        return response()->json(['success' => true, 'staff' => $staff]);
    }

    // protected function uploadAvatar($request,$path)
    // {
    //     if ($request->hasFile('avatar')) {
    //         // Get the school ID from the session
    //         $schoolId = session('school_id');
            
    //         // Define the folder path inside 'public' storage
    //         $folderPath = "{$schoolId}/avatars/staff";

    //         // Create the folder if it does not exist
    //         $storagePath = storage_path("app/public/{$folderPath}");
            
    //         if (!file_exists($storagePath)) {
    //             mkdir($storagePath, 0777, true);  // Creates the folder and subfolders if they don't exist
    //         }

    //         // Store the file in the defined path
    //         $avatarPath = $request->file('avatar')->store($folderPath, 'public');
    //         Log::info('Avatar stored at: ' . $avatarPath);
    //         // dd($avatarPath);

    //         return $avatarPath;
    //     }

    //     return null;
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return response()->json($staff);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = Staff::where('school_id',session('school_id'))
                        ->where('id',$id)
                        // ->with('user')
                        ->first();
        // dd($staff);
        $departments = Department::select('id','title')->where('user_id',session('school_id'))->get();
        $positions = Position::select('id','title')->where('user_id',session('school_id'))->get();
        return view('admin.staff.edit-partial', compact('staff','departments','positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Start a transaction for the update process
        // echo 'asdfasdfasdfasfd';
        // dd($request->all());
        DB::beginTransaction();

        try {
            // Find the staff record
            $staff = Staff::findOrFail($id);

            // Find the associated user based on the user_id in the staff table
            $user = User::findOrFail($staff->user_id);
            $old_avatar = $user->avatar;

            // Update the user's table with the new data
        // dd($this->uploadAvatar($request));
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                // 'avatar' => $this->uploadAvatar($request),
                'avatar' => $request->hasFile('avatar') ? HelperFile::uploadFile($request,'avatars/staff') : $user->avatar,  // Update avatar if a new one is uploaded
                'phone' => $request->input('phone'),
            ]);

            // Update the staff's table with the new data
            $staff->update([
                'name' => $request->input('name'),
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
                'gender' => $request->input('gender'),
                'joined_date' => $request->input('joined_date'),
                'address' => $request->input('address'),
            ]);

            // Check if the staff already has an avatar, and delete it if it exists
            if ($old_avatar && file_exists(storage_path('app/public/' . $old_avatar))) {
                // Delete the old avatar image from the storage
                unlink(storage_path('app/public/' . $old_avatar));
            }

            // Commit the transaction after both user and staff updates
            DB::commit();

            // Return the updated staff record as a JSON response
            return response()->json($staff);
        } catch (\Exception $e) {
            // Rollback in case of an error
            DB::rollBack();

            // Return error response
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();  // Start a transaction

        try {
            // Find the staff record
            $staff = Staff::where('user_id',$id)->first();
            
            // Get the user associated with this staff
            $user = User::where('id',$id)->first();

            // Delete the user record first
            $userDeleted = $user ? $user->delete() : true;

            // Delete the staff record
            $staffDeleted = $staff->delete();

            // Check if both deletions were successful
            if ($userDeleted && $staffDeleted) {
                // Now delete the avatar image after the deletions are successful
                if ($user && $user->avatar) {
                    $avatarPath = storage_path('app/public/' . $user->avatar);  // Get the avatar path
                    if (file_exists($avatarPath)) {
                        unlink($avatarPath);  // Delete the avatar image file
                    }
                }

                DB::commit();  // Commit the transaction if everything was successful
                return response()->json(null, 204);  // Return success response
            } else {
                DB::rollBack();  // Rollback if any deletion failed
                return response()->json(['message' => 'Error deleting staff or user'], 500);
            }
        } catch (\Exception $e) {
            // Rollback in case of any exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


}
