<?php

declare(strict_types=1);

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/files', [FileController::class, 'index'])->name('file.index');
Route::post('/upload', [FileController::class, 'upload']);
Route::get('/file/delete/{id}', [FileController::class, 'delete'])->name('file.delete');
