<?php

use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\ProfessorController;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api'] // to ensure type=superadmin
], function () {
    Route::get('/students', [UserManagementController::class, 'listStudents']);
    //professors
    Route::get('/professors', [ProfessorController::class, 'index']);
    Route::get('/professors/{id}', [ProfessorController::class, 'show']);
    Route::post('/professors', [ProfessorController::class, 'store']);
    Route::put('/professors/{id}', [ProfessorController::class, 'update']);
    Route::delete('/professors/{id}', [ProfessorController::class, 'destroy']);


    //students
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    Route::put('/students/{id}', [StudentController::class, 'update']);

    Route::get('/user', [AuthController::class, 'me']);

});

Route::post('/login', [AuthController::class, 'login'])->middleware('auth:api');


?>

