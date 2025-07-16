<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\HelperFile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SeatPlanController extends Controller
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
    
    public function index()
    {
        $data = [];
        return view('admin.seat-plan.index', compact('data'));
    }

    public function config()
    {
        $data = [];
        return view('admin.seat-plan.config', compact('data'));
    }
}
