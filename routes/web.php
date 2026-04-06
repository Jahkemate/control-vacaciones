<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones
Route::post('/print-vacation', function (\Illuminate\Http\Request $request) {
    return view('print.vacation-request', [
        'data' => $request->all()
    ]);
})->name('print.vacation');
