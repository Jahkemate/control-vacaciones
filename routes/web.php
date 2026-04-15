<?php

use App\Http\Controllers\VacationRequestController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones (vacation_request)
Route::get('/print-vacation/{id}', [VacationRequestController::class, 'print'])->name('print.vacation');


Route::get('/test-mail', function () {
    Mail::raw('Este es un correo de prueba desde Laravel', function ($message) {
        $message->to('test@example.com')
            ->subject('Prueba Mailtrap');
    });

    return 'Correo enviado (revisa Mailtrap)';
    
});
