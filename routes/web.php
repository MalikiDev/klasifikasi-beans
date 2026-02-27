<?php

use App\Http\Controllers\CoffeeBeansController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('coffee.index');
});
// Coffee Beans Routes
Route::resource('coffee', CoffeeBeansController::class);
Route::post('coffee/{coffeeBean}/reclassify', [CoffeeBeansController::class, 'reclassify'])->name('coffee.reclassify');
Route::get('roasting-info', function () {
    return view('coffee.roasting-info');
})->name('coffee.roasting-info');

