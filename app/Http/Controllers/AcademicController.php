<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\HelperFile;
use App\Models\Batch;
use App\Models\ClassModel;
use App\Models\Faculty;
use App\Models\Section;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AcademicController extends Controller
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
    
    public function facultyBatch()
    {
        $data = Faculty::where('user_id', auth()->user()->id)
                ->with('batches.classes.sections')
                ->orderBy('id','desc')
                ->get()
                ->toArray();
        return view('admin.academic.faculty', compact('data'));
    }
    public function ClassSection()
    {
        $data = ClassModel::where('user_id', auth()->user()->id)
                ->with('sections')
                ->whereNull('batch_id')
                ->get()->toArray();
        return view('admin.academic.class', compact('data'));
    }

    public function changeTitle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'type' => 'required|string|in:class,section,faculty,batch',
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
        ]);

        // Get the type, id, and new title from the request
        $type = $request->input('type');
        $id = $request->input('id');
        $newTitle = $request->input('title');

        // Update title based on the type (classes or sections)
        if ($type == 'class') {
            $class = ClassModel::find($id);  // Find the class by id
            if ($class) {
                $class->title = $newTitle;  // Update the title
                $class->save();  // Save the changes
                return response()->json(['message' => 'Class title updated successfully!'], 200);
            } else {
                return response()->json(['error' => 'Class not found'], 404);
            }
        } elseif ($type == 'section') {
            $section = Section::find($id);  // Find the section by id
            if ($section) {
                $section->title = $newTitle;  // Update the title
                $section->save();  // Save the changes
                return response()->json(['message' => 'Section title updated successfully!'], 200);
            } else {
                return response()->json(['error' => 'Section not found'], 404);
            }
        } elseif ($type === 'faculty') {
            $faculty = Faculty::find($id);  // Find the section by id
            if ($faculty) {
                $faculty->title = $newTitle;  // Update the title
                $faculty->save();  // Save the changes
                return response()->json(['message' => 'Faculty title updated successfully!'], 200);
            } else {
                return response()->json(['error' => 'Faculty not found'], 404);
            }
        } elseif ($type === 'batch') {
            $batch = Batch::find($id);  // Find the section by id
            if ($batch) {
                $batch->title = $newTitle;  // Update the title
                $batch->save();  // Save the changes
                return response()->json(['message' => 'Batch title updated successfully!'], 200);
            } else {
                return response()->json(['error' => 'Batch not found'], 404);
            }
            
        }

        return response()->json(['error' => 'Invalid type'], 400);  // Return error if type is invalid
    }

    public function addElement(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'postData' => 'required|array',
            'type' => 'required|string|in:class,section,faculty,batch', // validate the type
        ]);

        // Get the type and post data
        $type = $request->input('type');
        $postData = $request->input('postData');

        // Handle the type and insert data into respective table
        if ($type === 'class') {
            // Create a new class record
            $class = ClassModel::create([
                'id' => $postData['id'],
                'title' => $postData['title'],
                'user_id' => $postData['user_id'],
                'batch_id' => isset($postData['batch_id']) ? $postData['batch_id'] : null,
                'created_at' => $postData['created_at'],
                'updated_at' => $postData['updated_at'],
            ]);
            
            return response()->json(['message' => 'Class added successfully!', 'data' => $class, 'type' => $type], 201);
            
        } elseif ($type === 'section') {
            // Create a new section record
            $section = Section::create([
                'id' => $postData['id'],
                'title' => $postData['title'],
                'user_id' => $postData['user_id'],
                'class_id' => $postData['class_id'],
                'created_at' => $postData['created_at'],
                'updated_at' => $postData['updated_at'],
            ]);
            
            return response()->json(['message' => 'Section added successfully!', 'data' => $section, 'type' => $type], 201);

        } elseif ($type === 'faculty') {
            // Create a new faculty record
            $faculty = Faculty::create([
                'id' => $postData['id'],
                'title' => $postData['title'],
                'user_id' => $postData['user_id'],
                'created_at' => $postData['created_at'],    
                'updated_at' => $postData['updated_at'],
            ]);
            
            return response()->json(['message' => 'Faculty added successfully!', 'data' => $faculty, 'type' => $type], 201);

        } elseif ($type === 'batch') {
            // Create a new batch record
            $batch = Batch::create([
                'id' => $postData['id'],
                'title' => $postData['title'],
                'faculty_id' => $postData['faculty_id'], // Assuming faculty_id is part of the batch model
                'user_id' => $postData['user_id'],
                'created_at' => $postData['created_at'],
                'updated_at' => $postData['updated_at'],
            ]);

            return response()->json(['message' => 'Batch added successfully!', 'data' => $batch, 'type' => $type], 201);
            
        }

        // If the type is not recognized
        return response()->json(['error' => 'Invalid type provided'], 400);
    }

    public function deleteElement(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        try {
            switch ($type) {
                case 'faculty':
                    $element = Faculty::find($id);
                    break;
                case 'batch':
                    $element = Batch::find($id);
                    break;
                case 'class':
                    $element = ClassModel::find($id);
                    break;
                case 'section':
                    $element = Section::find($id);
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
            }

            if ($element) {
                $element->delete();
                return response()->json(['success' => true, 'message' => ucfirst($type) . ' deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => ucfirst($type) . ' not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting ' . $type . ': ' . $e->getMessage()], 500);
        }
    }
    
    public function eraseData()
    {
        // Get the currently authenticated user's ID
        $userId = auth()->id();

        // Delete related data for the authenticated user
        Faculty::where('user_id', $userId)->delete();
        Batch::where('user_id', $userId)->delete();
        ClassModel::where('user_id', $userId)->delete();
        Section::where('user_id', $userId)->delete();

        // Optionally, you can add a success message or redirect
        return redirect()->back()->with('success', 'Your data has been erased successfully.');
    }

    public function populateData()
    {
        // Get the currently authenticated user's ID
        $userId = auth()->id();

        // Add faculties using Eloquent ORM
        $faculties = [
            [
                'user_id' => $userId,
                'title' => 'CSIT',
            ],
            [
                'user_id' => $userId,
                'title' => 'BSW',
            ],
            [
                'user_id' => $userId,
                'title' => 'BIM',
            ]
        ];

        Faculty::insert(array_map(function ($faculty) use ($userId) {
            return array_merge($faculty, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }, $faculties));

        // Add batches using Eloquent ORM
        $facultyIds = Faculty::pluck('id');
        foreach ($facultyIds as $facultyId) {
            Batch::create([
                'user_id' => $userId,
                'title' => '2080',
                'faculty_id' => $facultyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Add classes using Eloquent ORM
        $batchIds = Batch::pluck('id');
        foreach ($batchIds as $batchId) {
            ClassModel::create([
                'user_id' => $userId,
                'title' => $batchId === 1 ? '1st Semester' : '2nd Semester', // Adjust titles based on batch_id
                'batch_id' => $batchId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Add two more records with null batch_id using Eloquent ORM
        ClassModel::create([
            'user_id' => $userId,
            'title' => '1',
            'batch_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        ClassModel::create([
            'user_id' => $userId,
            'title' => '2',
            'batch_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Add sections using Eloquent ORM
        $classes = ClassModel::pluck('id');
        foreach ($classes as $classId) {
            Section::create([
                'user_id' => $userId,
                'title' => $classId === 1 ? 'A' : 'B', // Adjust titles based on class_id
                'class_id' => $classId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Optionally, you can add a success message or redirect
        return redirect()->back()->with('success', 'Your data has been populated successfully.');
    }



}
