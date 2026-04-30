<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Relaciones de la tabla de usuarios

    public function employee()
    {
        return $this->hasOne(Employee::class); // Se hace relacion con la tabla de empleados
    }

    // Atributo para mostrar el rol en español en el puesto al momento de imprimir la solicitud
    public function getRoleLabelAttribute()
    {
        $role = $this->getRoleNames()->first();

        return match ($role) {
            'admin' => 'Administrador',
            'manager' => 'Jefe',
            'employee' => 'Empleado',
            default => $role ?? 'Sin rol',
        };
    }

    // helper pra traer los roles del usuario
    public function hasAnyAppRole(array $roles): bool
    {
        // compatibilidad: primero revisa Spatie
        return $this->hasAnyRole($roles);
    }

    public function commentsAdditional()
    {
        return $this->hasMany(RequestComments::class);
    }
}
