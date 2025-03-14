<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function () {
    return 'sanctum';
})->middleware('auth:sanctum');


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('quotes', QuoteController::class);
    Route::post('logout',[UserController::class,'logout']);
    Route::get('randomQuotes',[QuoteController::class,'randomQuote']);
    Route::get('userQuotes',[QuoteController::class,'showUserQuotes']);
    Route::get('popularQuotes',[QuoteController::class,'popularQuotes']);
    Route::get('allQuotes',[QuoteController::class,'showAllQuotes']);
    Route::get('popularQuotes', [QuoteController::class,'allPopularQuotes']);
    Route::get('userPopularQuotes',[QuoteController::class,'userPopularQuotes']);
    Route::post('filterQuotes',[QuoteController::class,'filteredQuotes']);
    // Route::post('auth',[QuoteController::class, 'store']);
    // Route::apiResource('users', UserController::class);
});

