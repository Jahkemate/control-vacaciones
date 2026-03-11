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

     public function department(){
        return $this->hasMany(EmployeesTable::class);
    }
}
