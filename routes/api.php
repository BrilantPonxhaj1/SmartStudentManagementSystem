<?php

use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\ProfessorController;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api'] // to ensure type=superadmin
], function () {
    // students
    Route::get('/students', [UserManagementController::class, 'listStudents']);

    // professors
    Route::get('/professors', [ProfessorController::class, 'index']);
    Route::post('/professors', [ProfessorController::class, 'store']);
    Route::delete('/professors/{id}', [ProfessorController::class, 'destroy']);
    Route::put('/professors/{id}', [ProfessorController::class, 'update']);
});
Route::post('/login', [AuthController::class, 'login'])->middleware('auth:api');

?>

