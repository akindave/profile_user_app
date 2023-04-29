<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
// use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::controller(GuestController::class)->group(function () {
    // Route::get('/','indexPage')->name('home');
    Route::get('/login','loginPage')->name('loginPage');
    Route::get('/register','registerPage')->name('registerPage');
    // Route::post('register', 'register');
    Route::post('login', 'login');
    // Route::post('logout','logout')->name('logout');

});

// Route::group(['middleware' => 'auth:web'], function () {
//     Route::controller(UserController::class)->group(function () {
//         Route::get('user', 'user');
//         Route::post('/update','update');
//         Route::get('users', 'index');
//         Route::get('users/{id}', 'show');
//         Route::delete('users/{id}', 'destroy');
//         Route::put('users/{id}/activate', 'activate');
//         Route::put('users/{id}/deactivate','deactivate');
//     });
//     Route::group(['middleware' => 'admin'], function () {

//     });

// });

