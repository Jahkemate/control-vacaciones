<?php

namespace App\Models;

use App\States\RequestStatus;
use App\Traits\HasRequestLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PaidRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRequestLogs;
    use Notifiable;

    protected $table = 'paid_request'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'total_days',
        'status',
        'request_date',
        'comment',
        'paid_accrued',
        'start_date',
        'end_date',
        'used',
        'paid_total',
        'days_to_compensate'
    ];

    // ----------Para desabilitar los botones de acuerdo al estado de la Solicitud-------------
    protected $casts = [
        'status' => RequestStatus::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }

    // Relacion con la tabla de RequestComments
    public function commentsAdditional()
    {
        return $this->morphMany(RequestComments::class, 'commentable');
    }

    // Relacion con la tabla de historico de las Solicitudes
    public function logs()
    {
        return $this->morphMany(RequestLog::class, 'loggable');
    }

    // ----------------------- Logica del filtro que se muestra en la parte de arriba de los list --------------------------
    public function scopeVisibleFor($query, \App\Models\User $user)
    {
        if ($user->role === 'admin') {
            return $query;
        }

        $employeeId = $user->employee?->id;

        if ($user->role === 'manager') {
            $employeeIds = \App\Models\Employee::where('department_id', $user->employee->department_id)
                ->pluck('id')
                ->push($employeeId);

            return $query->whereIn('employee_id', $employeeIds);
        }

        return $query->where('employee_id', $employeeId);
    }
    //-------------------------------------------------------------------------------------------------------------------------

}
