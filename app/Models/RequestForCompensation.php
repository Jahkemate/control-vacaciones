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
        // ADMIN → todo
        if ($user->hasRole('admin')) {
            return $query;
        }


        $employee = $user->employee;


        // si no tiene employee, no ve nada
        if (! $employee) {
            return $query->whereRaw('1 = 0');
        }


        // MANAGER → departamento + lo suyo
        if ($user->hasRole('manager')) {


            $employeeIds = Employee::where('department_id', $employee->department_id)
                ->pluck('id')
                ->push($employee->id);


            return $query->whereIn('employee_id', $employeeIds);
        }


        // EMPLOYEE → solo lo suyo
        return $query->where('employee_id', $employee->id);
    }
    //-------------------------------------------------------------------------------------------------------------------------


}
