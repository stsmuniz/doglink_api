<?php

use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\PageSectionController;
use App\Http\Controllers\API\PageSocialNetworkController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserPageController;
use App\Http\Controllers\API\UserProfilePicturecontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
});

Route::controller(RegisterController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('reset-password', [RegisterController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/page', [UserPageController::class, 'get']);
    Route::resource('pages', UserPageController::class)
        ->only(['index', 'store', 'show', 'destroy', 'update']);
    Route::resource('pages.sections', PageSectionController::class)
        ->only(['index', 'store', 'show', 'destroy', 'update']);
    Route::resource('pages.social-networks', PageSocialNetworkController::class)
        ->only(['store', 'destroy', 'update']);
    Route::post('profile-picture', [UserProfilePicturecontroller::class, 'store']);
    Route::delete('profile-picture', [UserProfilePicturecontroller::class, 'destroy']);
    Route::delete('/user', [RegisterController::class, 'destroy']);
    Route::name('admin.')->prefix('admin')->prefix('admin')->group(function () {
        Route::resource('pages', PageController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);
    });
});
