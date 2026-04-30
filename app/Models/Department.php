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
    ];

    //--------Para llamar solo a los empleados con rol de Jefe en cada departamento---------
    public function manager()
    {
        return $this->hasMany(Employee::class)
            ->whereHas('user', function ($query) {
                $query->role('manager');
            });
    }
}
