<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [HomeController::class, 'index']);




Route::get('getActivities', [Dashboard::class, 'index'])->middleware('auth');
Route::post('activity_action', [Dashboard::class, 'action'])->middleware('auth');

Route::get('dashboard/', [Dashboard::class, 'index'])->middleware('auth');


Route::get('detail/{id}', [HomeController::class, 'detail'])->name('detail/{id}');




Auth::routes();

Route::post('post-review/', [HomeController::class, 'saveReview'])->middleware('auth');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
