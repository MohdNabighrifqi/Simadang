<?php
// app/Models/Kegiatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table    = 'kegiatan';
    protected $fillable = [
        'judul','tanggal','lokasi','deskripsi',
        'penyelenggara','foto','status','created_by',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function scopeAktif($q)   { return $q->where('status','aktif'); }
    public function scopeSelesai($q) { return $q->where('status','selesai'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif'      => 'Aktif',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }
}
