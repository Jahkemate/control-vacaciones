<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestForCompensationtController extends Controller
{
     public function compensationPrint($id)
    {

       

        $pdf = app('dompdf.wrapper')->loadView('print.vacation-request', [
           
        ])
            ->setPaper('letter');

        return $pdf->stream('solicitud_de_pago.pdf');
    }

}
