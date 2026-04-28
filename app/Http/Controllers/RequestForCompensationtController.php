<?php

namespace App\Http\Controllers;

use App\Models\RequestForCompensation;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class RequestForCompensationtController extends Controller
{
    public function compensationPrint($id)
    {
        $record = RequestForCompensation::with('employee')->findOrFail($id);

        $pdf = app('dompdf.wrapper')->loadView('print.request_for_compensation', [
            'record' => $record
        ])
            ->setPaper('letter');

        return $pdf->stream('solicitud-compensacion.pdf'); // o download()

    }
}
