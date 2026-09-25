<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminComplaintController;
Route::get('/', [ComplaintController::class,'home'])->name('home');
Route::post('/complaints', [ComplaintController::class,'store'])->middleware('throttle:10,1')->name('complaints.store');
Route::get('/complaints/success/{reference}', [ComplaintController::class,'success'])->name('complaints.success');
Route::get('/track', [ComplaintController::class,'track'])->name('complaints.track');
Route::get('/admin/login', [AdminComplaintController::class,'login'])->name('admin.login');
Route::post('/admin/login', [AdminComplaintController::class,'authenticate'])->name('admin.authenticate');
Route::post('/admin/logout', [AdminComplaintController::class,'logout'])->name('admin.logout');
Route::get('/admin', [AdminComplaintController::class,'dashboard'])->name('admin.dashboard');
Route::get('/admin/complaints/{complaint}', [AdminComplaintController::class,'show'])->name('admin.complaints.show');
Route::patch('/admin/complaints/{complaint}', [AdminComplaintController::class,'update'])->name('admin.complaints.update');
