<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TransactionController;

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
    return redirect('/announcements');
});

Route::get('/profile',function ()
{
    $user = Auth::user();
    $announcementsNumber = sizeof($user->announcements);
    
    return view('profile',['user'=>$user,'announcementsNumber'=>$announcementsNumber]);
})->middleware('auth');

Route::get('/announcements',[AnnouncementController::class,'index']);
Route::post('/announcements',[AnnouncementController::class,'store']);
Route::get('/announcements/create', [AnnouncementController::class, 'create'])->middleware('auth');
Route::get('/announcements/{id}',[AnnouncementController::class,'show']);
Route::delete('/announcements/{id}',[AnnouncementController::class,'destroy'])->middleware('auth');
Route::put('/announcements/{id}',[AnnouncementController::class,'update'])->middleware('auth');
Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->middleware('auth');
Route::get('/announcements/{id}/transaction',[TransactionController::class,'create'])->middleware('auth');
Route::post('/announcements/{id}/transaction',[TransactionController::class,'store'])->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');