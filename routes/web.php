<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
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

    Route::get('/seat-plan-config', [SeatPlanController::class, 'config'])->name('seat-plan.config');
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


    Route::resource('staffs', StaffController::class);
    Route::resource('students', StudentController::class);
    
    Route::get('/import', [HomeController::class, 'import'])->name('import');
    Route::get('/seat-plan', [HomeController::class, 'seatPlan'])->name('seat-plan');
    // Route::get('/students', [StudentController::class, 'partial'])->name('positions.partial');

    Route::post('/erase-data', [AcademicController::class, 'eraseData'])->name('erase-data');
    Route::post('/populate-data', [AcademicController::class, 'populateData'])->name('populate-data');

});



Route::middleware('guest')->group(function () {
    // OTP routes
    Route::get('otp/verify', [RegisterController::class, 'showOtpVerificationForm'])->name('otp.verify');
    Route::post('otp/verify', [RegisterController::class, 'verifyOtp']);
});


