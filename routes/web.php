<?php

use App\Http\Controllers\SSO\SSOController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get("/login/get", [SSOController::class, 'userLogin'])->name('sso-login');
Route::get("/callback", [SSOController::class, 'getCallback'])->name("callback");
Route::get("/authUser", [SSOController::class, 'ssoUser'])->name("connect");
Route::get("/user-blogs", [SSOController::class, 'getBlogs'])->name("blogs");
Route::get("/beta-Tester", [SSOController::class, 'chkBetaTester'])->name("betaTester");
Route::get("/join-beta-Tester", [SSOController::class, 'becomeBetaTester'])->name("joinBetaTesters");

Auth::routes(['register' => false, 'reset' => false]);

Route::get("/home", [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get("/", [App\Http\Controllers\HomeController::class, 'index'])->name('index');
