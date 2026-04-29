<?php

namespace App\Models;

use App\Models\VacationRequest;
use App\States\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = 'employees'; // nombre real de la tabla
    protected $fillable = [
        'first_name',
        'last_name',
        'identity_number',
        'address_number',
        'hiring_date',
        'anniversary_date',
        'department_id',
        'employee_status',
        'payroll_id',
        'user_id'
    ];

    // Para poder usar los colores y los iconos del enum de employee_status
    protected $casts = [
        'employee_status' => EmployeeStatus::class,
    ];

    //----------Accesor para mostar el nombre completo----------
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Medodo para la tabla de empleado 
    public function scopeVisibleToUser($query, $user)
    {
        if ($user->hasRole('admin')) {
            return $query;
        }

        if ($user->hasRole('manager')) {
            return $query->where('department_id', $user->employee?->department_id);
        }

        if ($user->hasRole('employee')) {
            return $query->where('id', $user->employee?->id);
        }

        return $query;
    }



    //---------------------Relaciones----------------------------
    public function department()
    {
        return $this->belongsTo(Department::class); // relacion con la tabla department
    }

    public function user()
    {
        return $this->belongsTo(User::class); // relacion con la tabla usuario
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class); //relacion con la tabla de Nominas/Type_of_Payroll
    }

    public function balanceVacation()
    {
        return $this->hasOne(BalanceVacation::class, 'employee_id'); //relacion con la tabla de Balance Vacation
    }

    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class);
    }

    public function paidRequests()
    {
        return $this->hasMany(PaidRequest::class);
    }

    public function requestForCompensation()
    {
        return $this->hasMany(RequestForCompensation::class);
    }
}
