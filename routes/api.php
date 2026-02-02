<?php

//use Illuminate\Http\Request;

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\SettingController;

Route::post('auth/register', [UserController::class, 'userRegister']);
Route::post('auth/login', [UserController::class, 'userLogin']);
Route::post('auth/validate', [UserController::class, 'userValidate']);
Route::post('user/all', [UserController::class, 'userAll']);
Route::post('user/password/reset', [UserController::class, 'userPasswordReset']);

Route::post('auth/otp/generate', [UserController::class, 'generateOTP']);
Route::post('auth/otp/verify', [UserController::class, 'verifyOTP']);

Route::post('role/add', [UserController::class, 'addSystemRole']);
Route::post('role/edit', [UserController::class, 'editSystemRole']);
Route::post('role/all', [UserController::class, 'getSystemRoles']);
Route::post('role/delete', [UserController::class, 'deleteSystemRole']);

Route::post('permission/add', [UserController::class, 'addPermission']);
Route::post('permission/edit', [UserController::class, 'editPermission']);
Route::post('permission/all', [UserController::class, 'getPermissions']);
Route::post('permission/delete', [UserController::class, 'deletePermission']);


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('auth/user', [UserController::class, 'userData']);
    // Route::post('role/add', [UserController::class, 'addSystemRole']);


});

Route::post('category/create', [MovieController::class, 'createCategory']);
