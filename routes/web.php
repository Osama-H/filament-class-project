<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('{student}/pdf/generate',[\App\Http\Controllers\InvoicesController::class,'generatePDF'])->name('student.pdf.generate');
