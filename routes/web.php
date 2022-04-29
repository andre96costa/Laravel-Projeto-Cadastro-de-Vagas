<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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
//All listings
Route::get('/', [ListingController::class, 'index'])->name('listings.index');
//show create form
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
//Store a new listing on db
Route::post('/listings', [ListingController::class, 'store'])->name('listings.store')->middleware('auth');
//Manage listings user logged in
Route::get('/listings/manage', [ListingController::class, 'manage'])->name('listings.manage')->middleware('auth');
//Single listing
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
//Update listing on db
Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update')->middleware('auth');
//Delete a listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.delete')->middleware('auth');
//Show Edit Form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit')->middleware('auth');



//Show Register/Create form
Route::get('/register', [UserController::class, 'create'])->name('register')->middleware('guest');
//Create new User
Route::post('/users', [UserController::class, 'store'])->name('users.store');
//Log user out
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
//Show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
//Log in User
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('authenticate');




