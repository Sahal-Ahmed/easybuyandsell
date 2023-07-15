<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
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
/*
Route::get('/', function () {
    return view('frontend.welcome');
});
*/
// backend route
Route::get('/admins',[AdminController::class,'index']);
Route::post('/admin-dashboard',[AdminController::class,'show_dashboard']);
Route::get('/dashboard',[SuperAdminController::class,'dashboard']);
Route::get('/logout',[SuperAdminController::class,'logout']);

//category route here...
Route::resource('/categories',CategoryController::Class);
Route::get('/cat-status{category}',[CategoryController::class,'change_status']);
Route::get('/categories-trash',[CategoryController::class,'trash']);
Route::get('/categories/restore/{id}',[CategoryController::class,'restore'])->name('category.restore');
Route::get('/categories/delete/{id}',[CategoryController::class,'forceDelete'])->name('category.delete');

//subcategory route here...
Route::resource('/sub-categories',SubCategoryController::Class);
Route::get('/cat-status{subcategory}',[SubCategoryController::class,'change_status']);

// frontend route
Route::get('/',[HomeController::class,'index']);