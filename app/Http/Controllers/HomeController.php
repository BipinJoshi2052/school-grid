<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\HelperFile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function index()
    {
        $data = [];
        return view('landing', compact('data'));
    }   

    public function import()
    {
        return view('admin.import');
    }
    public function seatPlan()
    {
        return view('admin.seat-plan.index');
    }

}
