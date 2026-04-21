<?php


namespace App\Traits;


use Illuminate\Support\Facades\Auth;
use App\States\RequestStatus;


trait HasRequestLogs
{
    protected static function bootHasRequestLogs()
    {
        static::created(function ($model) {
            $status = $model->status instanceof RequestStatus
                ? $model->status
                : RequestStatus::from($model->status);

            $model->logs()->create([
                'user_id' => Auth::id(),
                'status' => $status?->value,
                'comment' => 'Solicitud creada',
            ]);
        });


        static::updated(function ($model) {
            if ($model->wasChanged('status')) {


                $status = $model->status instanceof RequestStatus
                    ? $model->status
                    : RequestStatus::from($model->status);


                $comment = match ($status) {
                    RequestStatus::Draft => 'Guardado como borrador',
                    RequestStatus::Pending => 'Solicitud enviada',
                    RequestStatus::ApprovedByManager => 'Aprobado por jefe',
                    RequestStatus::ApprovedByRRHH => 'Aprobado por RRHH',
                    RequestStatus::Approved => 'Solicitud aprobada',
                    RequestStatus::Rejected => 'Solicitud rechazada',
                    default => 'Solicitud Guardada como Borrador',
                };


                $model->logs()->create([
                    'user_id' => Auth::id(),
                    'status' => $status->value,
                    'comment' => $comment,
                ]);
            }
        });
    }
}
