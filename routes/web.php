<?php

use App\Http\Controllers\VacationRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones (vacation_request)
Route::get('/print-vacation/{id}', [VacationRequestController::class, 'print'])->name('print.vacation');
