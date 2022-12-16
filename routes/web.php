<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-test', function () {
    if(DB::connection()->getDatabaseName())
    {
        echo "Connected to database ".DB::connection()->getDatabaseName();
    }
});

Route::get('/profile/{slug}', [PageController::class, 'show']);
