<?php

use App\Http\Controllers\VacationRequestController;
use App\Http\Controllers\PaidRequestController;
use App\Http\Controllers\RequestForCompensationtController;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones (vacation_request)
Route::get('/print-vacation/{id}', [VacationRequestController::class, 'print'])->middleware(ValidateSignature::class)->name('print.vacation');

Route::get('/vacation-request/{id}', [VacationRequestController::class, 'detailsRequest'])->name('detailsRejected');

// Ruta para imprimir la solicitud pagada (paid_request)
Route::get('/paid-request/{id}', [PaidRequestController::class, 'paidPrint'])->middleware(ValidateSignature::class)->name('print.paid');

// Ruta para imprimir solicitud por compensacion (request_for_compensation)
Route::get('/request-for-compensation/{id}', [RequestForCompensationtController::class, 'compensationPrint'])->middleware(ValidateSignature::class)->name('print.compensation');
