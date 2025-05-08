<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\admin\tutor_information\TutorInformationController;
use App\Http\Controllers\admin\subject\SubjectController;
use App\Http\Controllers\admin\subject\SubjectUsersController;
use App\Http\Controllers\admin\income_summary\IncomeSummaryController;
use App\Http\Controllers\admin\courses_offered\CoursesOfferedController;

use App\Http\Controllers\tutor\TutorController;
use App\Http\Controllers\tutor\teacher_information\TeacherInformationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

//auth
Route::get('/', [AuthController::class, 'LoginPage'])->name('LoginPage');
Route::post('/login', [AuthController::class, 'Login'])->name('Login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'check.level:1'])->group(function () {
    Route::get('/admin/page', [AdminController::class, 'AdminIndex'])->name('AdminIndex');

    Route::get('/admin/tutor_information', [TutorInformationController::class, 'TutorInformationPage'])->name('TutorInformationPage');
    Route::put('/admin/tutor_information/update/{id}', [TutorInformationController::class, 'updateTutorInformation'])->name('updateTutorInformation');
    Route::get('/admin/tutor_information/detail/{id}', [TutorInformationController::class, 'TutorInformationDetailPage'])->name('TutorInformationDetailPage');

    Route::get('/admin/subject', [SubjectController::class, 'SubjectPage'])->name('SubjectPage');
    Route::post('/admin/subject/create', [SubjectController::class, 'SubjectCreate'])->name('SubjectCreate');
    Route::post('/admin/subject/update/{id}', [SubjectController::class, 'SubjectUpdate'])->name('SubjectUpdate');
    Route::delete('/admin/subject/delete/{id}', [SubjectController::class, 'SubjectDelete'])->name('SubjectDelete');

    Route::get('/admin/subject_users', [SubjectUsersController::class, 'SubjectUsersPage'])->name('SubjectUsersPage');
    Route::post('/admin/subject_users/create', [SubjectUsersController::class, 'SubjectUsersCreate'])->name('SubjectUsersCreate');
    Route::delete('/admin/subject_users/delete/{id}', [SubjectUsersController::class, 'SubjectUsersDelete'])->name('SubjectUsersDelete');
    Route::post('/admin/subject_users/update/{id}', [SubjectUsersController::class, 'SubjectUsersUpdate'])->name('SubjectUsersUpdate');

    Route::get('/admin/courses_offered', [CoursesOfferedController::class, 'CoursesOfferedPage'])->name('CoursesOfferedPage');
    Route::post('/admin/courses_offered/create', [CoursesOfferedController::class, 'CoursesOfferedCreate'])->name('CoursesOfferedCreate');
    Route::delete('/admin/courses_offered/delete/{id}', [CoursesOfferedController::class, 'deleteCourse'])->name('deleteCourse');
    Route::post('/admin/courses_offered/update/{id}', [CoursesOfferedController::class, 'updateCourse'])->name('updateCourse');
    Route::delete('/admin/courses_offered/delete/files/{id}', [CoursesOfferedController::class, 'deleteFile'])->name('deleteFile');

    Route::get('/admin/income_summary', [IncomeSummaryController::class, 'IncomeSummaryPage'])->name('IncomeSummaryPage');
});

Route::middleware(['auth', 'check.level:2'])->group(function () {
    Route::get('/tutor/page', [TutorController::class, 'TutorIndex'])->name('TutorIndex');

    Route::get('/tutor/teacher_information', [TeacherInformationController::class, 'TeacherInformationPage'])->name('TeacherInformationPage');
});
