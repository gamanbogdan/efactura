<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\EfacturaController;

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

Route::get('/admin/login', [AuthController::class, 'getLogin'])->name('getLogin');
Route::post('/admin/login', [AuthController::class, 'postLogin'])->name('postLogin');


Route::get('/admin/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
Route::get('/admin/logout', [ProfileController::class, 'logout'])->name('logout');

Route::get('/admin/efactura-index', [EfacturaController::class, 'index'])->name('efactura.index');
Route::get('/admin/efactura-upload-list', [EfacturaController::class, 'upload_list'])->name('efactura.upload.list');

Route::get('/admin/efactura-info/{id}', [EfacturaController::class, 'info'])->name('efactura.info');


Route::post('/admin/efactura-upload', [EfacturaController::class, 'upload'])->name('efactura.upload');