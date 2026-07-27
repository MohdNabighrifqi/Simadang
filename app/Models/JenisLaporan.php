<?php
// app/Models/JenisLaporan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLaporan extends Model
{
    protected $table    = 'jenis_laporan';
    protected $fillable = ['nama', 'deskripsi'];

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'jenis_id');
    }
}
