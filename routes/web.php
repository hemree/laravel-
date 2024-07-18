<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicExcelController;


Route::get('/', function () {
    return redirect('/upload');
});

Route::get('/upload', [DynamicExcelController::class, 'index']);
Route::post('/upload', [DynamicExcelController::class, 'upload'])->name('upload');
