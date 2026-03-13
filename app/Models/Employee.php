<?php

namespace App\Models;

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
        'employee_state',
        'payroll_id',
        'user_id'
        ];

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function department(){
        return $this->belongsTo(Department::class);// relacion con la tabla department
    }

    public function user(){
        return $this->belongsTo(User::class);// relacion con la tabla usuario
    }

      public function payroll(){
        return $this->belongsTo(Payroll::class); //relacion con la tabla de Nominas/Type_of_Payroll
    }

}
