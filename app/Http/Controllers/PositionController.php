<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return response()->json(Position::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
        ]);

        $position = Position::create($validated);

        return response()->json($position, 201);
    }

    public function edit($id)
    {
        return response()->json(Position::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $position->update($validated);

        return response()->json($position);
    }

    public function destroy($id)
    {
        Position::destroy($id);

        return response()->json(['message' => 'Position deleted']);
    }
    
    public function partial()
    {
        $positions = Position::with('user')->orderBy('id','desc')->get();
        return view('admin.academic.departments..position-partial', compact('positions'));
    }
}