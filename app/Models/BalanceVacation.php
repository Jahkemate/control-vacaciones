<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    //Relaciones de la tabla de Balance
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }

    //Esta relaciones son nada mas para los campos del formulario del balance
    public function department(){
        return $this->belongsTo(Department::class); 
    }

    public function payroll(){
        return $this->belongsTo(Payroll::class); 
    }

    public function user(){
        return $this->belongsTo(User::class); 
    }
}
