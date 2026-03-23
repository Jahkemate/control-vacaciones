<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacationRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vacation_request'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'state',
        'request_date',
        'total_business_days',
        'comment'
        ];


    //Relacion con la tabla de empleados
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
 
}
