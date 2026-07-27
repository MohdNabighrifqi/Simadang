<?php
// app/Models/FotoLaporan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoLaporan extends Model
{
    protected $table    = 'foto_laporan';
    protected $fillable = ['laporan_id','path','keterangan'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    public function getIsVideoAttribute(): bool
    {
        return in_array(strtolower(pathinfo($this->path, PATHINFO_EXTENSION)), ['mp4','mov','webm']);
    }
}
