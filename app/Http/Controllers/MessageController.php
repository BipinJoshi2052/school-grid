<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return view('suggestions.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $suggestion = Message::create([
            'name' => $request->name,
            'message' => $request->message,
            'phone' => $request->phone,
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

        // if ($suggestion) {
        //     return response()->json(['status' => 'success', 'message' => 'Thank you for your suggestion!']);
        // } else {
        //     return response()->json(['status' => 'error', 'message' => 'Something went wrong, please try again!']);
        // }
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $suggestion = Message::create([
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
