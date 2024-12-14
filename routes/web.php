<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\admin\AdminLoginController;
use App\http\Controllers\admin\HomeController;
use App\http\Controllers\admin\CategoryController;
use App\http\Controllers\admin\TempImagesController;
use Illuminate\http\Request;



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

// Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category Route
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

        //temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');


        Route::get('/getSlug',function(Request $reques){
            $slug = '';
            if (!empty($reques->title)) {
               $slug = Str::slug($reques->title);
            }
            return response()->json([
                'status' => true,
                'slug'   => $slug
            ]);
        })->name('getSlug');
    });
});

