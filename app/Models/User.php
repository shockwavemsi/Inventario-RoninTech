<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStock::class, 'usuario_id');
    }

    public function devolucionesCompras()
    {
        return $this->hasMany(DevolucionCompra::class, 'usuario_id');
    }

    public function devolucionesVentas()
    {
        return $this->hasMany(DevolucionVenta::class, 'usuario_id');
    }

    public function esAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function tienePermiso($nivelMinimo = 2)
    {
        // admin = nivel 3, user = nivel 2 (según tus roles)
        $niveles = ['admin' => 3, 'user' => 2];
        $nivelUsuario = $niveles[$this->role->name] ?? 1;
        return $nivelUsuario >= $nivelMinimo;
    }
}


<!-- <?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

} -->


