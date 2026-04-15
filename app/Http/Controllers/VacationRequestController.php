<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VacationRequestController extends Controller
{
    public function print($id)
    {
       
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
            'hiring_date' => $hiring_date,
        ]);

        return $pdf->stream('solicitud_vacaciones.pdf');
    }
}
