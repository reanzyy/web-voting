<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisionController;

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

Route::redirect('/', '/login');

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([Authenticate::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(ClassroomController::class)->prefix('classrooms')->name('classrooms.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');

        Route::controller(StudentController::class)->name('students.')->group(function () {
            Route::get('/{id}/students', 'index')->name('index');
            Route::get('/{id}/students/create', 'create')->name('create');
            Route::post('/{id}/students', 'store')->name('store');
            Route::get('/{id_classroom}/students/edit/{id_student}', 'edit')->name('edit');
            Route::put('/{id_classroom}/{id_student}', 'update')->name('update');
            Route::delete('/{id_classroom}/{id_student}', 'destroy')->name('destroy');
            Route::post('/{classroomId}/import', 'import')->name('import');
        });
    });

    Route::controller(CandidateController::class)->prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');

        Route::controller(VisionController::class)->name('visions.')->group(function () {
            Route::get('/{id}/visions', 'index')->name('index');
            Route::post('/{id}/visions', 'store')->name('store');
            Route::put('/{id_candidate}/{id_visions}', 'update')->name('update');
            Route::delete('/{id_candidate}/{id_visions}', 'destroy')->name('destroy');
        });

        Route::controller(MissionController::class)->name('missions.')->group(function () {
            Route::get('/{id}/missions', 'index')->name('index');
            Route::post('/{id}/missions', 'store')->name('store');
            Route::put('/{id_candidate}/{id_missions}', 'update')->name('update');
            Route::delete('/{id_candidate}/{id_missions}', 'destroy')->name('destroy');
        });
    });
});
