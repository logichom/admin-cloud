<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
//等同以下route:
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/account', [HomeController::class, 'account_list'])->name('account');
Route::any('/account', [HomeController::class, 'account_search'])->name('account_search');

Route::get('/sidebar_menu', [HomeController::class, 'sidebar_menu_list'])->name('sidebar_menu');
Route::any('/sidebar_menu', [HomeController::class, 'sidebar_menu_search'])->name('sidebar_menu_search');
Route::get('/sidebar_menu_create', [HomeController::class, 'sidebar_menu_create_index'])->name('sidebar_menu_create');
Route::post('/sidebar_menu_create', [HomeController::class, 'sidebar_menu_create'])->name('sidebar_menu_create');
Route::post('/sidebar_menu_delete', [HomeController::class, 'sidebar_menu_delete']);
Route::get('/sidebar_menu_update/{id}', [HomeController::class, 'sidebar_menu_update_index']);
Route::put('/sidebar_menu_update', [HomeController::class, 'sidebar_menu_update'])->name('sidebar_menu_update');

Route::get('/permission', [HomeController::class, 'permission_list'])->name('permission');
Route::any('/permission', [HomeController::class, 'permission_search'])->name('permission_search');
Route::get('/permission_create', [HomeController::class, 'permission_create_index'])->name('permission_create');
Route::post('/permission_create', [HomeController::class, 'permission_create'])->name('permission_create');
Route::post('/permission_delete', [HomeController::class, 'permission_delete']);
Route::get('/permission_update/{id}', [HomeController::class, 'permission_update_index']);
Route::put('/permission_update', [HomeController::class, 'permission_update'])->name('permission_update');

Route::get('/brand', [AdminController::class, 'brand_list'])->name('brand');
Route::any('/brand', [AdminController::class, 'brand_search'])->name('brand_search');
Route::get('/brand_create', [AdminController::class, 'brand_create_index'])->name('brand_create');
Route::post('/brand_create', [AdminController::class, 'brand_create'])->name('brand_create');
Route::post('/brand_delete', [AdminController::class, 'brand_delete']);
Route::get('/brand_update/{id}', [AdminController::class, 'brand_update_index']);
Route::put('/brand_update', [AdminController::class, 'brand_update'])->name('brand_update');