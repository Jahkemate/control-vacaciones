<?php

namespace App\Models;

use App\Filament\Resources\Employees\Tables\EmployeesTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    //
    protected $table = 'department'; // nombre real de la tabla
    protected $fillable = ['id', 'name','employee_id', 'roles_id'];

     public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
