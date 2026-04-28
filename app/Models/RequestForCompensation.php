<?php

namespace App\Models;

use App\States\RequestStatus;
use App\Traits\HasRequestLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class RequestForCompensation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRequestLogs;
    use Notifiable;

    protected $table = 'request_for_compensation'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'date_creation',
        'total_days',
        'status',
        'approval_date',
        'pending_date',
        'comment',
        'accrued_compensation',
        'used',
        'total_compensation',
        'days_to_compensate',
        'start_date',
        'end_date'
    ];

    // ----------Para desabilitar los botones de acuerdo al estado de la Solicitud-------------
    protected $casts = [
        'status' => RequestStatus::class,
    ];

    // Relacion con la tabla de Employees
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }

    // Relacion con la tabla de RequestComments
    public function commentsAdditional()
    {
        return $this->morphMany(RequestComments::class, 'commentable');
    }

    // Realacion con la tabla de historico de las Solicitudes
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
