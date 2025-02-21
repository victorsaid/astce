<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/pdf/meetings/{user}', [\App\Http\Controllers\PdfController::class, 'pdfMeetings'])
    ->name('pdf.example');
Route::get('/pdf/meeting/{meeting}', [\App\Http\Controllers\PdfController::class, 'exportMeetingPdf'])
    ->name('pdf.export');


Route::get('/pdf/users/', [\App\Http\Controllers\PdfController::class, 'pdfUsers'])
    ->name('pdf.users');
Route::get('/pdf/user/{user}', [\App\Http\Controllers\PdfController::class, 'memberDeclaration'])
    ->name('pdf.memberDeclaration');

Route::get('/pdf/agreement/{agreement}', [\App\Http\Controllers\PdfController::class, 'beneficiariesAgreement'])
    ->name('pdf.beneficiariesAgreement');
