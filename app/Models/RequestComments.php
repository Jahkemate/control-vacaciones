<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestComments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'request_comments'; // nombre real de la tabla
    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'additional_comment',
        'type_comment',
    ];

    //--------------------Relaciones de la tabla de RequestComments---------------

    // relacion con la tabla de usuarios
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     //  RELACIÓN POLIMÓRFICA
    public function commentable()
    {
        return $this->morphTo();
    }
    //-----------------------------------------------------------------------------
}
