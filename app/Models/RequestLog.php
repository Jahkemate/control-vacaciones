<?php

namespace App\Models;

use App\States\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestLog extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'request_logs'; // nombre real de la tabla
    protected $fillable = [
        'user_id',
        'status',
        'comment',
    ];

    //en cada modelo de las solicitudes pegar esto
    public function loggable()
    {
        return $this->morphTo();
    }
    //—--------------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
    'status' => RequestStatus::class,
];
}
