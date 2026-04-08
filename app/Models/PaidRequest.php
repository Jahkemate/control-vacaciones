<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaidRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'paid_request'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'total_days',
        'status',
        'request_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }
}
