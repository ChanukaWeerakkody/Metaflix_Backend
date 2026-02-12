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
Route::put('category/{category_id}', [MovieController::class, 'updateCategory']);
Route::delete('category/{category_id}', [MovieController::class, 'deleteCategory']);
Route::get('category/all', [MovieController::class, 'getAllCategories']);
Route::post('category/{category_id}', [MovieController::class, 'getcategoryById']);

Route::post('language/create', [MovieController::class, 'createLanguage']);
Route::put('language/{language_id}', [MovieController::class, 'updateLanguage']);
Route::delete('language/{language_id}', [MovieController::class, 'deleteLanguage']);
Route::get('language/all', [MovieController::class, 'getAllLanguages']);
Route::get('language/{language_id}', [MovieController::class, 'getLanguageById']);

Route::post('movieRoll/create', [MovieController::class, 'createMovieRoll']);
Route::get('movieRoll/all', [MovieController::class, 'getAllMovieRoll']);
Route::put('movieRoll/{movieRoll_id}', [MovieController::class, 'updateMovieRoll']);
Route::delete('movieRoll/{movieRoll_id}', [MovieController::class, 'deleteMovieRoll']);
Route::get('movieRoll/{movieRoll_id}', [MovieController::class, 'getMovieRollById']);

Route::post('movie/create', [MovieController::class, 'createMovie']);
Route::get('movie/all', [MovieController::class, 'getAllMovies']);
Route::get('movie/{movie_id}', [MovieController::class, 'getMovieById']);
Route::put('movie/{movie_id}', [MovieController::class, 'updateMovie']);
Route::delete('movie/{movie_id}', [MovieController::class, 'deleteMovie']);
