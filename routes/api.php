<?php

use App\Http\Controllers\Api\Admin\DepartmentController;
use App\Http\Controllers\Api\Admin\UniversityController;
use App\Http\Controllers\Api\Admin\SubjectController;
use App\Http\Controllers\Api\Admin\SemesterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Professor\ExamController;
use App\Http\Controllers\Api\Student\CourseOfferingController;
use App\Http\Controllers\Api\Student\EnrollmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\ProfessorController;
use App\Http\Controllers\Api\Professor\AssignmentController;
use App\Http\Controllers\Api\SemesterController as GeneralSemesterController;
use App\Http\Controllers\Api\Admin\AdminComplaintController;
use App\Http\Controllers\Api\Professor\ProfessorComplaintController;
use App\Http\Controllers\Api\Student\StudentComplaintController;

Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api','role:superadmin'],
], function () {
    //professors
    Route::get('/professors', [ProfessorController::class, 'index']);
    Route::get('/professors/{id}', [ProfessorController::class, 'show']);
    Route::get('/professors/department/{id}', [ProfessorController::class, 'getProfessorsByDepartment']);
    Route::post('/professors', [ProfessorController::class, 'store']);
    Route::put('/professors/{id}', [ProfessorController::class, 'update']);
    Route::delete('/professors/{id}', [ProfessorController::class, 'destroy']);

    //students
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    Route::put('/students/{id}', [StudentController::class, 'update']);

    Route::get('/user', [AuthController::class, 'me']);


    Route::get('/universities', [UniversityController::class, 'index']);
    Route::get('/departments/university/{uni}', [DepartmentController::class, 'getDeptByUniversity']);


    //courses
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/{id}', [SubjectController::class, 'show']);
    Route::get('/subjects/department/{id}', [SubjectController::class, 'byDepartment']);
    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

    Route::get('/semesters/university/{id}', [SemesterController::class, 'getByUniversity']);
    Route::post('/semester', [SemesterController::class, 'store']);
    Route::put('/semester/{id}', [SemesterController::class, 'update']);
    Route::delete('/semester/{id}', [SemesterController::class, 'destroy']);


    // universities
    Route::get('/universities', [UniversityController::class, 'index']);
    Route::get('/departments/{uni}', [DepartmentController::class, 'getDeptByUniversity']);
    Route::get('/universities/{id}', [UniversityController::class, 'show']);
    Route::post('/universities', [UniversityController::class, 'store']);
    Route::put('/universities/{id}', [UniversityController::class, 'update']);
    Route::delete('/universities/{id}', [UniversityController::class, 'destroy']);

    Route::get('/course-offerings', [CourseOfferingController::class, 'getAll']);
    Route::get('/course-offerings/{id}', [CourseOfferingController::class, 'show']);
    Route::post('/course-offerings', [CourseOfferingController::class, 'store']);
    Route::put('/course-offerings/{id}', [CourseOfferingController::class, 'update']);
    Route::delete('/course-offerings/{id}', [CourseOfferingController::class, 'destroy']);

    Route::get   ('/departments',                   [DepartmentController::class, 'index']);
    Route::get   ('/departments/{id}',              [DepartmentController::class, 'show']);
    Route::post  ('/departments',                   [DepartmentController::class, 'store']);
    Route::put   ('/departments/{id}',              [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}',              [DepartmentController::class, 'destroy']);

    // Complaint routes (Admin)
    Route::get('/complaints', [AdminComplaintController::class, 'index']);
    Route::put('/complaints/{id}', [AdminComplaintController::class, 'update']);
    Route::get('/complaints/open', [AdminComplaintController::class, 'getOpenComplaints']);

    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{id}', [ExamController::class, 'show']);
    Route::post('/exams', [ExamController::class, 'store']);
    Route::put('/exams/{id}', [ExamController::class, 'update']);
    Route::delete('/exams/{id}', [ExamController::class, 'destroy']);

});
Route::middleware(['auth:api'])->group(function () {
    Route::get('/semesters', [GeneralSemesterController::class, 'index']);
    Route::get('/semesters/{id}', [GeneralSemesterController::class, 'show']);
});
Route::group([
    'prefix'=>'student',
    'middleware' => ['auth:api','role:student'],
    ], function () {

// List available course offerings
        Route::get('course_offerings', [CourseOfferingController::class, 'index']);
        Route::post('course_offerings/{courseOffering}/register', [EnrollmentController::class, 'register']);
        Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);

        // Complaint routes (Student)
        Route::post('/complaints/storeStudentComplaint', [StudentComplaintController::class, 'storeStudentComplaint']);
        Route::get('/complaints/getStudentComplaints/{id}', [StudentComplaintController::class, 'getStudentComplaintsByUserId']);
    });

Route::group([
    'prefix' => 'professor',
    'middleware' => ['auth:api','role:professor'],
], function () {
   Route::get('/course-offerings/{id}', [CourseOfferingController::class, 'coursesOfProfessor']);

   Route::get('/assignments', [AssignmentController::class, 'index']);
   Route::get('/assignments/{id}', [AssignmentController::class, 'show']);
   Route::post('/assignments', [AssignmentController::class, 'store']);
   Route::put('/assignments/{id}', [AssignmentController::class, 'update']);
   Route::delete('/assignments/{id}', [AssignmentController::class, 'destroy']);

    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{id}', [ExamController::class, 'show']);
    Route::post('/exams', [ExamController::class, 'store']);
    Route::put('/exams/{id}', [ExamController::class, 'update']);
    Route::delete('/exams/{id}', [ExamController::class, 'destroy']);

    // Complaint routes (Professor)
    Route::post('/complaints/storeProfessorComplaint', [ProfessorComplaintController::class, 'storeProfessorComplaint']);
    Route::get('/complaints/getProfessorComplaints/{id}', [ProfessorComplaintController::class, 'getProfessorComplaintsByUserId']);


});


?>

