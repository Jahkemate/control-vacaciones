<?php

namespace App\Http\Controllers;

use App\Models\PaidRequest;
use Illuminate\Http\Request;

class PaidRequestController extends Controller
{
    public function paidPrint($id)
    {
       $record = PaidRequest::with('employee')->findOrFail($id);

        $pdf = app('dompdf.wrapper')->loadView('print.paid_request', [
            'record' => $record
        ])
            ->setPaper('letter');

        return $pdf->stream('solicitud-compensacion_por_pago.pdf'); // o download()

    }
}
