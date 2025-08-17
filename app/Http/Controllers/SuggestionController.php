<?php

namespace App\Http\Controllers;

use App\Helpers\HelperFile;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        dd(Suggestion::all()->toArray());
        return view('suggestions.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'files.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image types and max size
        ]);

        $suggestion = Suggestion::create([
            'name' => session('name'),
            'user_id' => session('school_id'),
            'added_by' => session('user_id'),
            'message' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        if ($suggestion) {
            // Upload the files if there are any
            if ($request->hasFile('files')) {
                $filePaths = HelperFile::uploadMultipleFiles($request, 'suggestions');  // You can specify a path like 'suggestions'
                $suggestion->file = json_encode($filePaths);  // Store file paths as JSON in the suggestion
                $suggestion->save();
            }

            $request->session()->flash('success', 'Thank you for your suggestion!');
        } else {
            $request->session()->flash('error', 'There was an issue with your suggestion.');
        }

        return back();
    }

    public function store3(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'name' => $request->name,
            'message' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        if ($suggestion) {
            // Flash success message to session
            $request->session()->flash('success', 'Thank you for your suggestion!');
        } else {
            $request->session()->flash('error', 'Thank you for your suggestion!');
        }
        return back();
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'name' => $request->name,
            'message' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        if ($suggestion) {
            return redirect()->back()->with('success', 'Thank you for your suggestion!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
        }
    }
}