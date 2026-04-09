<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones (vacation_request)
Route::get('/print-vacation/{id}', function ($id) {

    $logo = base64_encode(file_get_contents(public_path('images/HVD LOGOTIPO.jpeg')));
    $logo2 = base64_encode(file_get_contents(public_path('images/FUNDAHRSE1.jpeg')));


    // Buscar la solicitud de vacaciones específica y traer al empleado con user y department
    $vacationRequest = App\Models\VacationRequest::with('employee.user', 'employee.department')
        ->findOrFail($id);

    $employee = $vacationRequest->employee;

    // Formatear fechas de la solicitud
    $start_date = \Carbon\Carbon::parse($vacationRequest->start_date)
        ->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');

    $end_date = \Carbon\Carbon::parse($vacationRequest->end_date)
        ->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');

    // Fecha de ingreso del empleado
    $hiring_date = \Carbon\Carbon::parse($employee->hiring_date)
        ->locale('es')->isoFormat('D [de] MMMM [de] YYYY');


    $pdf = app('dompdf.wrapper')->loadView('print.vacation-request', [
        'employee' => $employee,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'vacationRequest' => $vacationRequest,
        'logo' => $logo,
        'logo2' => $logo2,
        'hiring_date' => $hiring_date,
    ]);

    return $pdf->stream('solicitud_vacaciones.pdf');
    // stream = abre en navegador
    // download = descarga directa

})->name('print.vacation');
