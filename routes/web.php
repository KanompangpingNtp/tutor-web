<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users\HomeController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\admin\tutor_information\TutorInformationController;
use App\Http\Controllers\admin\subject\SubjectController;
use App\Http\Controllers\admin\subject\SubjectUsersController;
use App\Http\Controllers\admin\income_summary\IncomeSummaryController;
use App\Http\Controllers\admin\courses_offered\CoursesOfferedController;
use App\Http\Controllers\admin\booking_history\BookingHistoryController;

use App\Http\Controllers\tutor\TutorController;
use App\Http\Controllers\tutor\teacher_information\TeacherInformationController;
use App\Http\Controllers\tutor\subject\SubjectTutorController;
use App\Http\Controllers\tutor\courses_offered\TutorCoursesOfferedController;
use App\Http\Controllers\tutor\teaching_schedule\TeachingScheduleController;
use App\Http\Controllers\tutor\teaching_history\TeachingHistoryController;

use App\Http\Controllers\users\subject_category\SubjectCategoryController;
use App\Http\Controllers\users\course\CourseController;
use App\Http\Controllers\users\account\UsersController;

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

Route::get('/test', function () {
    return view('pages.user-account.test');
});


Route::get('/', [HomeController::class, 'Home'])->name('Home');

Route::get('/subject_category/page', [HomeController::class, 'SubjectCategory'])->name('SubjectCategory');

Route::get('/subject_category/course/page/{id}', [HomeController::class, 'CoursePage'])->name('CoursePage');
Route::get('/course_bookings/{id}', [CourseController::class, 'BookingPage'])->name('BookingPage');

Route::get('/subject_category/course/teacher_history/{id}', [HomeController::class, 'TeacherHistoryPage'])->name('TeacherHistoryPage');

Route::get('/subject_category/course/detail/page/{id}', [HomeController::class, 'CourseDetail'])->name('CourseDetail');

//auth
Route::get('/LoginPage', [AuthController::class, 'LoginPage'])->name('LoginPage');
Route::get('/RegisterPage', [AuthController::class, 'RegisterPage'])->name('RegisterPage');
Route::post('/login', [AuthController::class, 'Login'])->name('Login');
Route::post('/register', [AuthController::class, 'Register'])->name('Register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'check.level:1'])->group(function () {
    Route::get('/admin/page', [AdminController::class, 'AdminIndex'])->name('AdminIndex');

    Route::get('/admin/tutor_information', [TutorInformationController::class, 'TutorInformationPage'])->name('TutorInformationPage');
    Route::put('/admin/tutor_information/update/{id}', [TutorInformationController::class, 'updateTutorInformation'])->name('updateTutorInformation');
    Route::delete('/admin/tutor_information/delete/{id}', [TutorInformationController::class, 'deleteTutorInformation'])->name('deleteTutorInformation');
    Route::get('/admin/tutor_information/detail/{id}', [TutorInformationController::class, 'TutorInformationDetailPage'])->name('TutorInformationDetailPage');
    Route::post('/admin/teacher_information/create/detail/{id}', [TutorInformationController::class, 'AdminTeacherResumeCreate'])->name('AdminTeacherResumeCreate');
    Route::put('/admin/teacher_information/profile/update/{id}', [TutorInformationController::class, 'AdminTutorUpdateDetails'])->name('AdminTutorUpdateDetails');

    Route::get('/admin/subject', [SubjectController::class, 'SubjectPage'])->name('SubjectPage');
    Route::post('/admin/subject/create', [SubjectController::class, 'SubjectCreate'])->name('SubjectCreate');
    Route::post('/admin/subject/update/{id}', [SubjectController::class, 'SubjectUpdate'])->name('SubjectUpdate');
    Route::delete('/admin/subject/delete/{id}', [SubjectController::class, 'SubjectDelete'])->name('SubjectDelete');

    Route::get('/admin/subject_users', [SubjectUsersController::class, 'SubjectUsersPage'])->name('SubjectUsersPage');
    Route::post('/admin/subject_users/create', [SubjectUsersController::class, 'SubjectUsersCreate'])->name('SubjectUsersCreate');
    Route::delete('/admin/subject_users/delete/{id}/', [SubjectUsersController::class, 'SubjectUsersDelete'])->name('SubjectUsersDelete');
    Route::post('/admin/subject_users/update/{id}', [SubjectUsersController::class, 'SubjectUsersUpdate'])->name('SubjectUsersUpdate');

    Route::get('/admin/courses_offered', [CoursesOfferedController::class, 'CoursesOfferedPage'])->name('CoursesOfferedPage');
    Route::get('/admin/courses_offered/create/page', [CoursesOfferedController::class, 'CoursesOfferedCreatePage'])->name('CoursesOfferedCreatePage');
    Route::get('/admin/courses-offered/update/page/{id}', [CoursesOfferedController::class, 'CoursesOfferedUpdatePage'])->name('CoursesOfferedUpdatePage');
    Route::post('/admin/courses_offered/create', [CoursesOfferedController::class, 'CoursesOfferedCreate'])->name('CoursesOfferedCreate');
    Route::delete('/admin/courses_offered/delete/{id}', [CoursesOfferedController::class, 'deleteCourse'])->name('deleteCourse');
    Route::put('/admin/courses_offered/update/{id}', [CoursesOfferedController::class, 'CoursesOfferedUpdate'])->name('CoursesOfferedUpdate');

    Route::get('/admin/income_summary', [IncomeSummaryController::class, 'IncomeSummaryPage'])->name('IncomeSummaryPage');

    Route::get('/admin/booking_history', [BookingHistoryController::class, 'AdminBookingHistoryPage'])->name('AdminBookingHistoryPage');
    Route::put('/admin/booking_history/update-status/{id}', [BookingHistoryController::class, 'BookingHistoryUpdateStatus'])->name('BookingHistoryUpdateStatus');
    Route::put('/admin/booking_history/update-payment_status/{id}', [BookingHistoryController::class, 'BookingHistoryUpdatePayment'])->name('BookingHistoryUpdatePayment');
    Route::delete('/admin/booking_history/booking-history/{id}', [BookingHistoryController::class, 'AdminBookingHistoryDelete'])->name('AdminBookingHistoryDelete');
});

Route::middleware(['auth', 'check.level:2'])->group(function () {
    Route::get('/tutor/page', [TutorController::class, 'TutorIndex'])->name('TutorIndex');

    Route::get('/tutor/teacher_information', [TeacherInformationController::class, 'TeacherInformationPage'])->name('TeacherInformationPage');
    Route::post('/tutor/teacher_information/create', [TeacherInformationController::class, 'TutorTeacherResumeCreate'])->name('TutorTeacherResumeCreate');
    Route::put('/tutor/teacher_information/profile/update/{id}', [TeacherInformationController::class, 'TeacherInformationUpdate'])->name('TeacherInformationUpdate');

    Route::get('/tutor/subject_users', [SubjectTutorController::class, 'SubjectTutorPage'])->name('SubjectTutorPage');
    Route::post('/tutor/subject_users/create', [SubjectTutorController::class, 'SubjectTutorCreate'])->name('SubjectTutorCreate');
    Route::delete('/tutor/subject_users/delete/{userId}/{subjectId}', [SubjectTutorController::class, 'SubjectTutorDelete'])->name('SubjectTutorDelete');
    Route::put('/tutor/subject_users/update/{id}', [SubjectTutorController::class, 'SubjectTutorUpdate'])->name('SubjectTutorUpdate');

    Route::get('/tutor/courses_offered', [TutorCoursesOfferedController::class, 'TutorCoursesOfferedPage'])->name('TutorCoursesOfferedPage');
    Route::get('/tutor/courses_offered/create/page', [TutorCoursesOfferedController::class, 'TutorCoursesOfferedCreatePage'])->name('TutorCoursesOfferedCreatePage');
    Route::get('/tutor/courses-offered/update/page/{id}', [TutorCoursesOfferedController::class, 'TutorCoursesOfferedUpdatePage'])->name('TutorCoursesOfferedUpdatePage');
    Route::post('/tutor/courses_offered/create', [TutorCoursesOfferedController::class, 'TutorCoursesOfferedCreate'])->name('TutorCoursesOfferedCreate');
    Route::delete('/tutor/courses_offered/delete/{id}', [TutorCoursesOfferedController::class, 'TutordeleteCourse'])->name('TutordeleteCourse');
    Route::put('/tutor/courses_offered/update/{id}', [TutorCoursesOfferedController::class, 'TutorCoursesOfferedUpdate'])->name('TutorCoursesOfferedUpdate');

    Route::get('/tutor/teaching_schedule', [TeachingScheduleController::class, 'TeachingSchedulePage'])->name('TeachingSchedulePage');
    Route::get('/tutor/teaching_schedule/details/{id}', [TeachingScheduleController::class, 'TeachingScheduleDetails'])->name('TeachingScheduleDetails');
    Route::put('/tutor/teaching_schedule/update-status/{id}', [TeachingScheduleController::class, 'TeachingScheduleUpdateStatus'])->name('TeachingScheduleUpdateStatus');

    Route::get('/tutor/teaching_history', [TeachingHistoryController::class, 'TutorTeachingHistory'])->name('TutorTeachingHistory');
});

Route::middleware(['auth', 'check.level:3'])->group(function () {
    Route::get('/users/account/detail', [UsersController::class, 'UsersAccount'])->name('UsersAccount');
    Route::post('/users/account/booking/schedule/{id}', [UsersController::class, 'schedule'])->name('booking.schedule');
    Route::get('/user-account/booking/{id}', [UsersController::class, 'UsersAccountBooking'])->name('UsersAccountBooking');
    Route::post('/course_bookings/create', [CourseController::class, 'BookingCreate'])->name('BookingCreate');

    Route::get('/users/account/teaching_schedule/{id}', [UsersController::class, 'UserTeachingSchedule'])->name('UserTeachingSchedule');
});
