<?php

use App\Http\Controllers\VacationRequestController;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para imprimir solicitudes de vacaciones (vacation_request)
Route::get('/print-vacation/{id}', [VacationRequestController::class, 'print'])->name('print.vacation');



Route::get('/vacation-request/{id}', [VacationRequestController::class, 'detailsRequest'])->name('detailsRejected');


/* Route::get('/test-mail', function () {
    Mail::raw('Este es un correo de prueba desde Laravel', function ($message) {
        $message->to('test@example.com')
            ->subject('Prueba Mailtrap');
    });

    return 'Correo enviado (revisa Mailtrap)';
    
});
 */

/* Route::get('test', function(){
    $recipient = filament()->auth()->user();

    Notification::make()
        ->title('Notificacion de prueba')
        ->sendToDatabase($recipient);
   
        dd('done sending');
})->middleware('auth');
 */