<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['jwt', 'admin'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/create-tasks', [TaskController::class, 'create']);
    Route::delete('/tasks/{task}', [TaskController::class, 'delete']);
    Route::get('/attachments/{attachment}', [AttachmentController::class, 'show']);
    Route::get('/get-all-users', [UserController::class, 'index']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
Route::get('/tasks', [TaskController::class, 'index']);

Route::middleware('jwt')->group(function () {
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::post('/change-password', [ResetPasswordController::class, 'changePassword'])->name('password.change');
    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'create']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'delete']);
    Route::get('/generate-report', [TaskController::class, 'generateReport']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
});
