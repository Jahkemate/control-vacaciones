<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'type_of_payroll'; // nombre real de la tabla
    protected $fillable = [
        'payroll_type',
        'vacations_days', 
        'vacations_bonus',
        ];

    public function employee(){
        return $this->hasMany(Employee::class); //relacion con la tabla de Nominas/Type_of_Payroll
    }


    // PARA MOSTRAR LOS DATOS DE LA TABLA DE tIPO DE NOMINA AL FORMULARIO 
      public static function getDaysByYears($id)
    {
        return self::where('payroll_type', '<=', $id)
            ->orderByDesc('payroll_type')
            ->first();
    }
}
