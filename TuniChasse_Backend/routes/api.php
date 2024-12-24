<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\getproiController;
use App\Http\Controllers\getmapfront;
use App\Http\Controllers\notifController;
use App\Http\Controllers\calendarfrontController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\PositionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/user', [AuthController::class, 'getUserDetails']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/update-profile', [AuthController::class, 'updateProfile']);
Route::post('/update-password', [PasswordController::class, 'updatePassword']);
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('getPolygons', [App\Http\Controllers\PolygonController::class, 'getPolygons'])->name('getPolygons');
Route::get('getproies', [App\Http\Controllers\getproiController::class, 'getproies'])->name('getproies');
Route::get('getnotif', [App\Http\Controllers\notifController::class, 'getnotif'])->name('getnotif');
Route::get('getusers', [App\Http\Controllers\usersController::class, 'getusers'])->name('getusers');
Route::get('getmapfront', [App\Http\Controllers\getmapfront::class, 'getmapfront'])->name('getmapfront');
Route::get('getcalendar', [App\Http\Controllers\calendarfrontController::class, 'getcalendar'])->name('getcalendar');
Route::post('/send-alert', [App\Http\Controllers\AlertController::class, 'sendAlert'])->name('sendAlert');
Route::post('/send-location-update', [App\Http\Controllers\PositionController::class,'store'])->name('store');
