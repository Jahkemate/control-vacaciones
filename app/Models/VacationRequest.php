<?php

namespace App\Models;

use App\States\RequestStatus;
use App\Traits\HasRequestLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class VacationRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRequestLogs;
    use Notifiable;

    protected $table = 'vacation_request'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'status',
        'request_date',
        'total_business_days',
        'comment'
    ];


    //Relacion con la tabla de empleados
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ----------Para desabilitar los botones de acuerdo al estado de la Solicitud-------------
    protected $casts = [
        'status' => RequestStatus::class,
    ];

    // Relacion con la tabla de RequestComments
    public function commentsAdditional()
    {
        return $this->morphMany(RequestComments::class, 'commentable');
    }

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
