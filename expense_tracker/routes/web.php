<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ExpenseController;

Route::resource('expenses', ExpenseController::class);
