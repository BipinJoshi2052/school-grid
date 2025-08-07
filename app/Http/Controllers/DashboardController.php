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

class DashboardController extends Controller
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
        $data['faculty'] = Faculty::where('user_id', session('school_id'))->count();
        $data['batch'] = Batch::where('user_id', session('school_id'))->count();
        $data['class'] = ClassModel::where('user_id', session('school_id'))->count();
        $data['section'] = Section::where('user_id', session('school_id'))->count();

        return view('admin.dashboard', compact('data'));
    }

}
