<?php
// app/Models/Kondisi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kondisi extends Model
{
    protected $table    = 'kondisi';
    protected $fillable = ['nama', 'deskripsi'];

    // Label tampilan
    public function getLabelAttribute(): string
    {
        return match($this->nama) {
            'hidup'           => 'Hidup',
            'mati_terdampar'  => 'Mati Terdampar',
            'mati_tertangkap' => 'Mati Tertangkap',
            default           => ucfirst($this->nama),
        };
    }

    // Warna untuk peta/badge
    public function getWarnaAttribute(): string
    {
        return match($this->nama) {
            'hidup'           => '#2196F3',
            'mati_terdampar'  => '#212121',
            'mati_tertangkap' => '#E65100',
            default           => '#888',
        };
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'kondisi_id');
    }
}
