<?php

namespace App\Models;

use App\Filament\Resources\Employees\Tables\EmployeesTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    //
    use HasFactory;
    protected $table = 'department'; // nombre real de la tabla
    protected $fillable = [
        'name',
        'employee_id',
        'roles_id'
        ];

     public function employee(){
        return $this->hasMany(Employee::class);
    }

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
