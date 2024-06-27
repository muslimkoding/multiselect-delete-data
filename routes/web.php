<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::delete('/delete-selected', [ArticleController::class, 'deleteSelected'])->name('delete.selected');
Route::get('/show-items', [ArticleController::class, 'showItems'])->name('show.items');
