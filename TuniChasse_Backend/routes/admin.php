<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminIndexController;
use App\Http\Controllers\AdminListuserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminAdduserController;
use App\Http\Controllers\AdminPolygonController;
use App\Http\Controllers\AdminListproiController;
use App\Http\Controllers\AdminCalendarController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminListnotifController;
use App\Http\Controllers\AdminalertsController;
use App\Http\Controllers\AdminloisController;
use App\Http\Controllers\AdminaddloisController;
use App\Http\Controllers\AdminMapController;
use App\Http\Controllers\AdminPositionController;
use Illuminate\Support\Facades\File;


Route::get('Login-Admin', [LoginController::class, 'ShowloginAdmin'])->name('admin.login.show');
Route::post('Login-Admin', [LoginController::class, 'loginAdmin'])->name('admin.login.submit');

Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('suivimap', [App\Http\Controllers\Admin\PositionController::class,'suivimap'])->name('suivimap');
    Route::get('index', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');
    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('listalerts' , [App\Http\Controllers\Admin\alertsController::class, 'listalerts'])->name('listalerts');
    Route::get('listusers' , [App\Http\Controllers\Admin\ListuserController::class, 'listusers'])->name('listusers');
    Route::get('addusers' , [App\Http\Controllers\Admin\AdduserController::class, 'addusers'])->name('addusers');
    

    // Edit user (form)
     // search users

     Route::get('listproies' , [App\Http\Controllers\Admin\ListproiController::class, 'listproies'])->name('listproies');
     Route::get('addproies' , [App\Http\Controllers\Admin\AddproiController::class, 'addproies'])->name('addproies');
     Route::post('listproies/{pr}/update', [App\Http\Controllers\Admin\ListproiController::class, 'update'])->name('listproies.update');
     Route::post('addproies', [App\Http\Controllers\Admin\AddproiController::class, 'store'])->name('addproies.store');
     Route::delete('listproies/{pr}/destroy', [App\Http\Controllers\Admin\ListproiController::class, 'destroy'])->name('listproies.destroy');
    // add notif
    Route::get('listnotif' , [App\Http\Controllers\Admin\ListnotifController::class, 'listnotif'])->name('listnotif');
    Route::get('addnotif' , [App\Http\Controllers\Admin\NotificationController::class, 'addnotif'])->name('addnotif');
    Route::post('addnotif', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('addnotif.store');
    Route::post('listnotif/{pr}/update', [App\Http\Controllers\Admin\ListnotifController::class, 'update'])->name('listnotif.update');
    Route::delete('listnotif/{pr}/destroy', [App\Http\Controllers\Admin\ListnotifController::class, 'destroy'])->name('listnotif.destroy');
    // Update user (POST)
    Route::post('listusers/{user}/update', [App\Http\Controllers\Admin\ListuserController::class, 'update'])->name('listusers.update');
    Route::post('savePolygon',[App\Http\Controllers\Admin\PolygonController::class,'store'])->name('store');
    Route::get('getPolygons', [App\Http\Controllers\Admin\PolygonController::class, 'getPolygons'])->name('getPolygons');
    Route::post('update-polygon', [App\Http\Controllers\Admin\MapController::class, 'updatePolygon'])->name('update-polygon');
    Route::post('newpoly', [App\Http\Controllers\Admin\MapController::class, 'storePolygon'])->name('newpoly');

    //calendar
    Route::post('enregistrer-evenement' , [App\Http\Controllers\Admin\CalendarController::class, 'store'])->name('store');
    Route::get('recuperer-evenements', [App\Http\Controllers\Admin\CalendarController::class, 'getEvents'])->name('getEvents');
    // list lois
    Route::get('listlois' , [App\Http\Controllers\Admin\loisController::class, 'listlois'])->name('listlois');
    Route::post('listlois/{loi}/update', [App\Http\Controllers\Admin\loisController::class, 'update'])->name('listlois.update');
    Route::delete('listlois/{loi}/destroy', [App\Http\Controllers\Admin\loisController::class, 'destroy'])->name('listlois.destroy');
    // add lois
    Route::get('addloi' , [App\Http\Controllers\Admin\addloisController::class, 'addloi'])->name('addloi');
    Route::post('addloi', [App\Http\Controllers\Admin\addloisController::class, 'store'])->name('addloi.store');

    // Destroy user (DELETE)
    Route::delete('listusers/{user}/destroy', [App\Http\Controllers\Admin\ListuserController::class, 'destroy'])->name('listusers.destroy');
    Route::post('addusers', [App\Http\Controllers\Admin\AdduserController::class, 'store'])->name('addusers.store');
    Route::get('map' , [App\Http\Controllers\Admin\MapController::class, 'maps'])->name('maps');
    Route::get('calendar' , [App\Http\Controllers\Admin\CalendarController::class, 'calendar'])->name('calendar');
    Route::post('savePolygonProi', [App\Http\Controllers\Admin\MapController::class, 'savePolygonProi'])->name('savePolygonProi');



});
