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
        'role',    // admin | warga
        'daerah',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* ── Relasi ── */
    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    /* ── Helper ── */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
