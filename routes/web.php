<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PowerPointController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\WordController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/generate-preview', [PreviewController::class, 'generatePreview']);
Route::post('/save-ppt',[PowerPointController::class,'generatePPT']);
Route::post('/save-word',[WordController::class,'generateWordFile']);
