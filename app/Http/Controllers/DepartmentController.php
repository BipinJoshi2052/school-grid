<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('admin.academic.departments.departments');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    public function edit($id)
    {
        return response()->json(Department::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $department->update($validated);

        return response()->json($department);
    }

    public function destroy($id)
    {
        Department::destroy($id);

        return response()->json(['message' => 'Department deleted']);
    }
    
    public function partial()
    {
        $departments = Department::with('user')->orderBy('id','desc')->get();
        return view('admin.academic.departments..department-partial', compact('departments'));
    }

    public function saveEntity(Request $request)
    {
        if ($request->type === 'department') {
            if ($request->id) {
                $dept = Department::findOrFail($request->id);
                $dept->update(['title' => $request->title]);
            } else {
                Department::create(['user_id' => auth()->id(), 'title' => $request->title]);
            }
            return response()->json(['success' => true, 'type' => 'department']);
        }else{
            if ($request->id) {
                $dept = Position::findOrFail($request->id);
                $dept->update(['title' => $request->title]);
            } else {
                Position::create(['user_id' => auth()->id(), 'title' => $request->title]);
            }
            return response()->json(['success' => true, 'type' => 'position']);
        }
    }

}
