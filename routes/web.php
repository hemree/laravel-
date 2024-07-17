<?php

use App\Http\Controllers\ExcelDataController;

Route::get('/', [ExcelDataController::class, 'index']);
Route::post('/import', [ExcelDataController::class, 'import'])->name('import');
