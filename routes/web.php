<?php

use App\Http\Controllers\CoffeeBeansController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Coffee Beans Routes
Route::resource('coffee', CoffeeBeansController::class);
Route::post('coffee/{coffee}/reclassify', [CoffeeBeansController::class, 'reclassify'])->name('coffee.reclassify');

// Batch Routes
Route::get('coffee/batch/results/{batchId}', [CoffeeBeansController::class, 'batchResults'])->name('coffee.batch-results');
Route::get('coffee/batch/list', [CoffeeBeansController::class, 'batches'])->name('coffee.batches');

// Folder Routes
Route::get('coffee/folder/results/{batchId}', [CoffeeBeansController::class, 'folderResults'])->name('coffee.folder-results');

Route::get('roasting-info', function () {
    return view('coffee.roasting-info');
})->name('coffee.roasting-info');
