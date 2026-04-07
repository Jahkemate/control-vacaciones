<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones
Route::get('/print-vacation/{id}', function ($id) {

    $employee = App\Models\Employee::with(['user', 'department'])->findOrFail($id);

    $pdf = app('dompdf.wrapper')->loadView('print.vacation-request', [
        'employee' => $employee
    ]);

    return $pdf->stream('solicitud_vacaciones.pdf');
    // stream = abre en navegador
    // download = descarga directa

})->name('print.vacation');
