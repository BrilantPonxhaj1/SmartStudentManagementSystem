<?php

use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api'] // to ensure type=superadmin
], function () {
    Route::get('/students', [UserManagementController::class, 'listStudents']);
});

Route::post('/login', [AuthController::class, 'login'])->middleware('auth:api');

?>

