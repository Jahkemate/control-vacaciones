<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BalanceVacation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'balance_vacation'; // nombre real de la tabla
    protected $fillable = [
        'accrued_total',
        'accrued_this_year',
        'used',
        'balance',
        'employee_id',
        'notes',
        'pendings'
    ];

// -------------------Funcion para que en la tabla de balance, se muestre solo la del suario dependiendo su rol------------------------
    public function scopeVisibleToUser($query, $user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return $query;
        }

        // Admin → todo
        if ($user->hasRole('admin')) {
            return $query;
        }

        $employee = $user->employee;

        if (!$employee) {
            return $query->whereRaw('1 = 0');
        }

        // Employee → solo su balance
        if ($user->hasRole('employee')) {
            return $query->where('employee_id', $employee->id);
        }

        // Manager → su balance + su departamento
        if ($user->hasRole('manager')) {
            return $query->where(function ($q) use ($employee) {

                $q->where('employee_id', $employee->id)
                    ->orWhereHas('employee', function ($emp) use ($employee) {
                        $emp->where('department_id', $employee->department_id);
                    });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    // ---------------Relaciones de la tabla de Balance---------------------------------
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }


    //Esta relaciones son nada mas para los campos del formulario del balance
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
