<?php

namespace App\Http\Controllers;

use App\Helpers\HelperFile;
use App\Models\Batch;
use App\Models\ClassModel;
use App\Models\Faculty;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all faculties related to the user's school_id
        $faculties = Faculty::where('user_id', session('school_id'))->orderBy('title', 'asc')->get();

        // Fetch batches related to the user's school_id
        // $batches = Batch::where('user_id', session('school_id'))->orderBy('title', 'asc')->get();

        // Fetch all classes with their sections related to the user's school_id
        // $classes = ClassModel::where('user_id', session('school_id'))->with('sections')->orderBy('title', 'asc')->get();

        // Fetch classes with no batch_id (classes without a batch)
        $classesWithNoBatch = ClassModel::where('user_id', session('school_id'))->whereNull('batch_id')->orderBy('title', 'asc')->get();

        return view('admin.student.index', compact('faculties','classesWithNoBatch'));
    }
    public function getBatches($facultyId)
    {
        // Get batches related to the selected faculty
        $batches = Batch::where('user_id', session('school_id'))->where('faculty_id', $facultyId)->get();
        return response()->json($batches);
    }

    public function getClasses($batchId)
    {
        // Get classes related to the selected batch
        $classes = ClassModel::where('user_id', session('school_id'))->where('batch_id', $batchId)->get();
        return response()->json($classes);
    }

    public function getSections($classId)
    {
        // Get sections related to the selected class
        $sections = Section::where('user_id', session('school_id'))->where('class_id', $classId)->get();
        return response()->json($sections);
    }
    public function getClassesWithoutBatch()
    {
        // Fetch classes without a batch_id
        $classes = ClassModel::where('user_id', session('school_id'))->whereNull('batch_id')->get();
        
        return response()->json($classes);
    }


    public function listPartial(Request $request) {
        return view('admin.student.index-partial');
    }

    public function getList(Request $request) {
        // dd($request->all());
        // Get the search term from the request
        $searchTerm = $request->get('search')['value'] ?? '';  // DataTables sends the search term in search[value]

        // Get the filter parameters for class, section, faculty, and batch from the request
        $facultyId = $request->get('facultyId');
        $batchId = $request->get('batchId');
        $classId = $request->get('classId');
        $sectionId = $request->get('sectionId');

        // Pagination parameters
        $page = $request->get('page', 1);  // Default page is 1
        $perPage = $request->get('pageLength', 10); 

        // Build the query to filter data
        $query = Student::where('school_id', session('school_id'))
                        ->with('user')
                        ->with('class')
                        ->with('section')
                        ->orderBy('name', 'desc');

        // Apply the search filter if a search term is provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Apply the additional filters if they are provided
        // if ($facultyId) {
        //     $query->where('faculty_id', $facultyId);
        // }

        // if ($batchId) {
        //     $query->where('batch_id', $batchId);
        // }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }
        // Paginate the results
        $users = $query->paginate($perPage);
        // dd($users->items());

        // Return the response in JSON format for DataTables
        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(), // Since we're not using a separate filtered count, this is the same as recordsTotal
            'data' => $users->items()  // Return the paginated data
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faculties = Faculty::where('user_id', session('school_id'))->orderBy('title', 'asc')->get();
        $classesWithNoBatch = ClassModel::where('user_id', session('school_id'))->whereNull('batch_id')->orderBy('title', 'asc')->get();

        return view('admin.student.create-partial', compact('faculties','classesWithNoBatch'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|min:10',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'joined_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'faculty_id' => 'nullable|integer',
            'batch_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'section_id' => 'nullable|integer',
            'handicapped' => 'nullable|integer'
        ]);
        // dd('here');
        try{
            // Create the user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('secret'),  // Set password as 'secret'
                'phone' => $request->phone,
                'avatar' => HelperFile::uploadFile($request,'avatars/student'), // Handle avatar upload
                'parent_id' => session('school_id'),
                'user_type_id' => 4, // Staff
                'added_by' => auth()->id(),
            ]);

            // Create the staff record
            $staff = Student::create([
                'name' => $request->name,
                'school_id' => session('school_id'),
                'user_id' => $user->id,
                'address' => $request->address,
                'gender' => $request->gender,
                'faculty_id' => $request->faculty_id,
                'batch_id' => $request->batch_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'roll_no' => $request->roll_no,
                'handicapped' => $request->handicapped,
                'added_by' => auth()->id(),
            ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);    
        }

        return response()->json(['success' => true, 'staff' => $staff]);

        // $student = Student::create($request->all());
        // return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::where('school_id',session('school_id'))
                        ->where('id',$id)
                        ->first();
        
        $faculties = Faculty::where('user_id', session('school_id'))->orderBy('title', 'asc')->get();
        $classesWithNoBatch = ClassModel::where('user_id', session('school_id'))->whereNull('batch_id')->orderBy('title', 'asc')->get();

        // Check if the student has a class with a batch_id
        $class = ClassModel::where('id', $student->class_id)->first();
        $sections = Section::where('class_id', $student->class_id)->orderBy('title', 'asc')->get();

        // If class has a batch_id, get the batch and its associated faculty
        if ($class->batch_id) {
            $batch = Batch::where('id', $class->batch_id)->first();
            
            // Get all batches for that faculty
            $batches = Batch::where('faculty_id', $batch->faculty_id)->orderBy('title', 'asc')->get();

            $faculty_classes = ClassModel::where('batch_id', $class->batch_id)->get();
            // Pass all required data to the view

            $selected_faculty = $batch->faculty_id;
            $selected_batch = $class->batch_id;

            return view('admin.student.edit-partial', compact(
                'student', 
                'faculties', 
                'classesWithNoBatch', 
                'batches', 
                'sections',
                'faculty_classes',
                'selected_faculty',
                'selected_batch',
            ));
        }

        return view('admin.student.edit-partial', compact('student','faculties','classesWithNoBatch','sections'));
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
            $student = Student::findOrFail($id);

            // Find the associated user based on the user_id in the staff table
            $user = User::findOrFail($student->user_id);
            $old_avatar = $user->avatar;

            // Update the user's table with the new data
            // dd($this->uploadAvatar($request));
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'avatar' => $request->hasFile('avatar') ? HelperFile::uploadFile($request,'avatars/student') : $user->avatar,  // Update avatar if a new one is uploaded
                'phone' => $request->input('phone'),
            ]);

            // Update the staff's table with the new data
            $student->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),
                'faculty_id' => $request->faculty_id,
                'batch_id' => $request->batch_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'roll_no' => $request->roll_no,
                'handicapped' => $request->handicapped
            ]);

            // Check if the staff already has an avatar, and delete it if it exists
            if ($old_avatar && file_exists(storage_path('app/public/' . $old_avatar))) {
                // Delete the old avatar image from the storage
                unlink(storage_path('app/public/' . $old_avatar));
            }

            // Commit the transaction after both user and staff updates
            DB::commit();

            // Return the updated staff record as a JSON response
            return response()->json($student);
        } catch (\Exception $e) {
            // Rollback in case of an error
            DB::rollBack();

            // Return error response
            return response()->json(['message' => $e->getMessage()], 500);
        }

        // $student = Student::findOrFail($id);
        // $student->update($request->all());
        // return response()->json($student);
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
            $student = Student::where('user_id',$id)->first();
            
            // Get the user associated with this staff
            $user = User::where('id',$id)->first();

            // Delete the user record first
            $userDeleted = $user ? $user->delete() : true;

            // Delete the staff record
            $studentDeleted = $student->delete();

            // Check if both deletions were successful
            if ($userDeleted && $studentDeleted) {
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
