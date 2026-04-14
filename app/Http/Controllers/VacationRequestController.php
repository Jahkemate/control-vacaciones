<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VacationRequestController extends Controller
{
    public function print($id)
    {
        $logo = cache()->rememberForever('logo_hvd', function () {
            return base64_encode(file_get_contents(public_path('images/HVD LOGOTIPO.jpeg')));
        });

        $logo2 = cache()->rememberForever('logo_fundahrse', function () {
            return base64_encode(file_get_contents(public_path('images/FUNDAHRSE1.jpeg')));
        });

        $vacationRequest = VacationRequest::with('employee.user', 'employee.department')
            ->findOrFail($id);

        $employee = $vacationRequest->employee;

        $start_date = Carbon::parse($vacationRequest->start_date)
            ->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');

        $end_date = Carbon::parse($vacationRequest->end_date)
            ->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');

        $hiring_date = Carbon::parse($employee->hiring_date)
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
    }
}
