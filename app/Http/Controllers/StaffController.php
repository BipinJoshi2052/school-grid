<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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

    public function listPartial(){
        $users = Staff::where('school_id',session('school_id'))
                        ->with('user')
                        ->with('department')
                        ->with('position')
                        ->orderBy('id','desc')
                        ->get();
                        // dd($users->toArray());
        return view('admin.staff.index-partial', compact('users'));

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
        //
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
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
        ]);
// dd('here');
        try{
            // Create the user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('secret'),  // Set password as 'secret'
                'phone' => $request->phone,
                'avatar' => $this->uploadAvatar($request), // Handle avatar upload
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
                'gender' => $request->gender,
                'joined_date' => $request->joined_date,
                'added_by' => auth()->id(),
            ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);    
        }

        return response()->json(['success' => true, 'staff' => $staff]);
    }

    protected function uploadAvatar($request)
    {
        if ($request->hasFile('avatar')) {
            // Get the school ID from the session
            $schoolId = session('school_id');
            
            // Define the folder path inside 'public' storage
            $folderPath = "avatars/{$schoolId}/staff";

            // Create the folder if it does not exist
            $storagePath = storage_path("app/public/{$folderPath}");
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);  // Creates the folder and subfolders if they don't exist
            }

            // Store the file in the defined path
            $avatarPath = $request->file('avatar')->store($folderPath, 'public');

            return $avatarPath;
        }

        return null;
    }


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
        //
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
        $staff = Staff::findOrFail($id);
        $staff->update($request->all());
        return response()->json($staff);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return response()->json(null, 204);
    }
}
