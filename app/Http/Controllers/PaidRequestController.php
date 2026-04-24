<?php

namespace App\Http\Controllers;

use App\Models\PaidRequest;
use Illuminate\Http\Request;

class PaidRequestController extends Controller
{
    public function paidPrint($id)
    {
        $paidRequest = PaidRequest::with('employee.user', 'employee.department')
            ->findOrFail($id);

        $employee = $paidRequest->employee;

        $pdf = app('dompdf.wrapper')->loadView('print.vacation-request', [])
            ->setPaper('letter');

        return $pdf->stream('solicitud_de_pago.pdf');
    }
}
