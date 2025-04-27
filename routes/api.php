<?php

use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\StudentController;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api'] // to ensure type=superadmin
], function () {
    Route::get('/students', [UserManagementController::class, 'listStudents']);
    //students
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
});

Route::post('/login', [AuthController::class, 'login'])->middleware('auth:api');


?>

