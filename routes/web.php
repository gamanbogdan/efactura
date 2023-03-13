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

Route::get('/admin/efactura', [EfacturaController::class, 'index'])->name('efactura.index');
Route::get('/admin/efacturaa', [EfacturaController::class, 'indexx'])->name('efactura.indexx');
Route::get('/admin/efactura/{factura}', [EfacturaController::class, 'show'])->name('efactura.show');

Route::put('/admin/efactura/{factura}', [EfacturaController::class, 'update'])->name('efactura.update');

Route::get('/admin/pdf_anaf/{id}', [EfacturaController::class, 'pdf_anaf'])->name('efactura.pdf_anaf');
Route::get('/admin/semnatura_anaf/{id}', [EfacturaController::class, 'semnatura_anaf'])->name('efactura.semnatura_anaf');


Route::post('/admin/efactura-upload', [EfacturaController::class, 'upload'])->name('efactura.upload');

