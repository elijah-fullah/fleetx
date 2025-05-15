<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OMCController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.index');
})->name('login');

Route::get('/forget', [MainController::class, 'forget'])->name('forget');

Route::get('/login', [MainController::class, 'login'])->name('login');
Route::post('/login', [MainController::class, 'logins'])->name('logins');

Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

Route::get('/overviewOMC', [OMCController::class, 'overviewOMC'])->name('overviewOMC');
Route::get('/omc/overviewOMC', [OMCController::class, 'getOMCStats'])->name('getOMCStats');
Route::get('/omcs/omc/overviewOMC', [OMCController::class, 'getOMCAverage'])->name('getOMCAverage');
Route::get('/views/omcs/omc/overviewOMC', [OMCController::class, 'getTopOMCs'])->name('getTopOMCs');

Route::get('/addOMC', [OMCController::class, 'addOMC'])->name('addOMC');
Route::post('/addOMC', [OMCController::class, 'addOMCS'])->name('addOMCS');

Route::get('/editOMC/{id}', [OMCController::class, 'editOMC'])->name('editOMC');
Route::post('/editOMC/{id}', [OMCController::class, 'updateOMC'])->name('updateOMC');

Route::get('/omc/viewProfile/{id}', [OMCController::class, 'viewProfile'])->name('viewProfile');
Route::get('/omc/viewDealer/{id}', [OMCController::class, 'viewDealer'])->name('viewDealer');

Route::get('/omcs/omc/viewDealer/{id}', [OMCController::class, 'getOMCDealerStats'])->name('getOMCDealerStats');
Route::get('/views/omc/viewDealer/{id}', [OMCController::class, 'getOMCDealerAverage'])->name('getOMCDealerAverage');

Route::delete('/omc/{id}', [OMCController::class, 'deleteOMC'])->name('deleteOMC');
Route::post('/omc/suspend/{id}', [OMCController::class, 'suspendOMC'])->name('suspendOMC');

Route::get('/overviewDealer', [DealerController::class, 'overviewDealer'])->name('overviewDealer');
Route::get('/dealer/overviewDealer', [DealerController::class, 'getDealerStats'])->name('getDealerStats');
Route::get('/dealers/overviewDealer', [DealerController::class, 'getDealerAverage'])->name('getDealerAverage');
Route::get('/views/dealers/dealer/overviewDealer', [DealerController::class, 'getTopDealers'])->name('getTopDealers');

Route::get('/addDealer', [DealerController::class, 'addDealer'])->name('addDealer');
Route::post('/addDealer', [DealerController::class, 'addDealers'])->name('addDealers');
Route::get('/addDealer', [DealerController::class, 'showAddDealerForm'])->name('addDealer');

Route::get('/editDealer/{id}', [DealerController::class, 'editDealer'])->name('editDealer');
Route::post('/editDealer/{id}', [DealerController::class, 'updateDealer'])->name('updateDealer');

Route::get('/dealer/viewDealerProfile/{id}', [DealerController::class, 'viewDealerProfile'])->name('viewDealerProfile');
Route::get('/dealer/dealerStationOverview/{id}', [DealerController::class, 'dealerStationOverview'])->name('dealerStationOverview');
Route::get('/dealer/dealerOMCOverview/{id}', [DealerController::class, 'dealerOMCOverview'])->name('dealerOMCOverview');

Route::get('/dealers/dealer/dealerStationOverview/{id}', [DealerController::class, 'getDealerStationStats'])->name('getDealerStationStats');
Route::get('/views/dealer/dealerStationOverview/{id}', [DealerController::class, 'getDealerStationAverage'])->name('getDealerStationAverage');
Route::get('/view/dealers/dealer/dealerOMCOverview/{id}', [DealerController::class, 'getDealerOMCStats'])->name('getDealerOMCStats');
Route::get('/views/dealer/dealers/dealerOMCOverview/{id}', [DealerController::class, 'getDealerOMCAverage'])->name('getDealerOMCAverage');

Route::delete('/dealer/{id}', [DealerController::class, 'deleteDealer'])->name('deleteDealer');
Route::post('/dealer/suspend/{id}', [DealerController::class, 'suspendDealer'])->name('suspendDealer');

Route::get('/overviewStation', [StationController::class, 'overviewStation'])->name('overviewStation');
Route::get('/station/overviewStation', [StationController::class, 'getStationStats'])->name('getStationStats');
Route::get('/stations/overviewStation', [StationController::class, 'getStationAverage'])->name('getStationAverage');
Route::get('/views/stations/station/overviewStation', [StationController::class, 'getTopStations'])->name('getTopStations');

Route::get('/addStation', [StationController::class, 'addStation'])->name('addStation');
Route::post('/addStation', [StationController::class, 'addStations'])->name('addStations');
Route::get('/addStation', [StationController::class, 'showAddDealerForm'])->name('addStation');

Route::delete('/station/{id}', [StationController::class, 'deleteStation'])->name('deleteStation');
Route::post('/station/suspend/{id}', [StationController::class, 'suspendStation'])->name('suspendStation');

Route::get('/station/viewStationProfile/{id}', [StationController::class, 'viewStationProfile'])->name('viewStationProfile');
Route::get('/station/stationDealerOverview/{id}', [StationController::class, 'stationDealerOverview'])->name('stationDealerOverview');
Route::get('/view/stations/station/stationDealerOverview/{id}', [StationController::class, 'getStationDealerStats'])->name('getStationDealerStats');
Route::get('/views/station/stations/stationDealerOverview/{id}', [StationController::class, 'getStationDealerAverage'])->name('getStationDealerAverage');

Route::get('/editStation/{id}', [StationController::class, 'editStation'])->name('editStation');
Route::post('/editStation/{id}', [StationController::class, 'updateStation'])->name('updateStation');

Route::get('/overviewUser', [UserController::class, 'overviewUser'])->name('overviewUser');
Route::get('/user/overviewUser', [UserController::class, 'getUserStats'])->name('getUserStats');
Route::get('/users/user/overviewUser', [UserController::class, 'getUserAverage'])->name('getUserAverage');

Route::get('/addUser', [UserController::class, 'addUser'])->name('addUser');
Route::post('/addUser', [UserController::class, 'addUsers'])->name('addUsers');

Route::get('/editUser/{id}', [UserController::class, 'editUser'])->name('editUser');
Route::post('/editUser/{id}', [UserController::class, 'updateUser'])->name('updateUser');

Route::delete('/user/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('/user/suspend/{id}', [UserController::class, 'suspendUser'])->name('suspendUser');

Route::get('/user/viewUserProfile/{id}', [UserController::class, 'viewUserProfile'])->name('viewUserProfile');