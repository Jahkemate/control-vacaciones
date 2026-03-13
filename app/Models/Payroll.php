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

   
}
