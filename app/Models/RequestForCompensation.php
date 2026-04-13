<?php

namespace App\Models;

use App\States\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestForCompensation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'request_for_compensation'; // nombre real de la tabla
    protected $fillable = [
        'employee_id',
        'date_creation',
        'total_days',
        'status',
        'approval_date',
        'pending_date',
        'comment',
    ];

     // ----------Para desabilitar los botones de acuerdo al estado de la Solicitud-------------
    protected $casts = [
        'status' => RequestStatus::class,
    ];

    // Relacion con la tabla de Employees
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); //relacion con la tabla de Employees
    }

     // Relacion con la tabla de RequestComments
    public function commentsAdditional()
    {
        return $this->morphMany(RequestComments::class, 'commentable');
    }
}
