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

    public function importStaff()
    {
        return view('admin.import.import-staff');
    }

    public function importStudent()
    {
        return view('admin.import.import-student');
    }

    public function seatPlan()
    {
        return view('admin.seat-plan.index');
    }
    public function seatPlanConfig()
    {
        return view('admin.seat-plan.config');
    }
    public function seatPlanConfigV3()
    {
        return view('admin.seat-plan.config-2');
    }

}
