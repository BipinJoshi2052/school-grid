<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BuildingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SeatPlanController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuggestionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/feedback', [SuggestionController::class, 'store'])->name('feedback');

Auth::routes(['verify' => true]);

// Custom logout route, if necessary
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/seat-plan-config-v3', [SeatPlanController::class, 'seatPlanConfigV3'])->name('seat-plan.configV3');
    Route::post('/generate-seat-plan', [SeatPlanController::class, 'generateSeatPlan'])->name('seat-plan.generate');
    
    Route::get('/seat-plan/list-partial', [SeatPlanController::class, 'listPartial'])->name('seat-plan.list-partial');
    Route::get('/seat-plan/create', [SeatPlanController::class, 'config'])->name('seat-plan.create');
    Route::get('/seat-plan/{id}', [SeatPlanController::class, 'seatPlanLayout'])->name('seatPlanLayout');
    Route::get('/seat-plan/invigilator/{id}', [SeatPlanController::class, 'seatPlanLayout'])->name('invigilatorPlanLayout');
    Route::get('/seat-plan', [SeatPlanController::class, 'index'])->name('seat-plan');

    Route::get('/faculty', [AcademicController::class, 'facultyBatch'])->name('academics.faculty');
    Route::get('/class', [AcademicController::class, 'ClassSection'])->name('academics.class');
    Route::get('/departments', [AcademicController::class, 'departments'])->name('academics.departments');
    Route::post('/add-element', [AcademicController::class, 'addElement'])->name('academics.addElement');
    Route::post('/delete-element', [AcademicController::class, 'deleteElement'])->name('academics.deleteElement');
    Route::put('/change-title', [AcademicController::class, 'changeTitle'])->name('academics.changeTitle');
    
    Route::get('/departments/partial', [DepartmentController::class, 'partial'])->name('departments.partial');
    Route::get('/positions/partial', [PositionController::class, 'partial'])->name('positions.partial');
    Route::post('/entity/save', [DepartmentController::class, 'saveEntity'])->name('entity.save'); 
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);

    Route::get('/staffs/list-partial', [StaffController::class, 'listPartial'])->name('staffs.list-partial');
    Route::post('staffs/update/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staffs/v2', [StaffController::class, 'listV2'])->name('staffs.v2');
    Route::resource('staffs', StaffController::class);

    Route::get('/students/list-partial', [StudentController::class, 'listPartial'])->name('students.list-partial');
    Route::get('/students/get-list', [StudentController::class, 'getList'])->name('students.get-list');
    Route::get('/get-batches/{facultyId}', [StudentController::class, 'getBatches']);
    Route::get('/get-classes/{batchId}', [StudentController::class, 'getClasses']);
    Route::get('/get-sections/{classId}', [StudentController::class, 'getSections']);
    Route::get('/get-classes-without-batch', [StudentController::class, 'getClassesWithoutBatch']);
    Route::resource('students', StudentController::class);
    Route::post('students/update/{id}', [StudentController::class, 'update'])->name('students.update');

    Route::get('/download/sample', [ImportController::class, 'downloadSample'])->name('import.downloadSample');
    Route::post('/validate-staff-import', [ImportController::class, 'validateStaffImport'])->name('import.staff.validate');
    Route::post('/import-staff-data', [ImportController::class, 'staffImport'])->name('import.staff-data');
    Route::get('/import-staff', [HomeController::class, 'importStaff'])->name('import.staff');
    

    Route::get('/download/sample/student', [ImportController::class, 'downloadSampleStudent'])->name('import.downloadSampleStudent');
    Route::post('/validate-student-import', [ImportController::class, 'validateStudentImport'])->name('import.student.validate');
    Route::post('/import-student-data', [ImportController::class, 'StudentImport'])->name('import.student-data');
    Route::get('/import-student', [HomeController::class, 'importStudent'])->name('import.student');

    Route::post('/buildings/add-element', [BuildingsController::class, 'addElement'])->name('buildings.addElement');
    Route::delete('/buildings/delete-element', [BuildingsController::class, 'deleteElement']);
    Route::get('/buildings/visualize', [BuildingsController::class, 'visualize'])->name('buildings.visualize');
    Route::get('/buildings/visualize-v2', [BuildingsController::class, 'visualize2'])->name('buildings.visualizev2');
    Route::get('/buildings', [BuildingsController::class, 'index'])->name('buildings.index');
    
    // Route::get('/seat-plan', [HomeController::class, 'seatPlan'])->name('seat-plan');
    // Route::get('/seat-plan-config', [HomeController::class, 'seatPlanConfig'])->name('seat-plan-config');

    Route::post('/erase-data', [AcademicController::class, 'eraseData'])->name('erase-data');
    Route::post('/populate-data', [AcademicController::class, 'populateData'])->name('populate-data');
    Route::post('/populate-student-data', [AcademicController::class, 'populateStudentData'])->name('populate-student-data');
    

    // Route::resource('staff', StaffController::class);
    // Route::resource('students', StudentController::class);
});



Route::middleware('guest')->group(function () {
    // OTP routes
    Route::get('otp/verify', [RegisterController::class, 'showOtpVerificationForm'])->name('otp.verify');
    Route::post('otp/verify', [RegisterController::class, 'verifyOtp']);
});


