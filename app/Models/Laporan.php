<?php
// app/Models/Laporan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table    = 'laporan';
    protected $fillable = [
        'kode', 'user_id',
        'jenis_id', 'kondisi_id', 'lokasi_id',
        'tanggal', 'waktu',
        'jumlah_dugong', 'deskripsi',
        'nama_pelapor', 'no_hp', 'email',
        'sumber_data', 'status', 'catatan',
        'latitude', 'longitude',
    ];

    protected $casts = ['tanggal' => 'date'];

    /* ── Relasi ── */
    public function user()     { return $this->belongsTo(User::class); }
    public function jenis()    { return $this->belongsTo(JenisLaporan::class, 'jenis_id'); }
    public function kondisi()  { return $this->belongsTo(Kondisi::class, 'kondisi_id'); }
    public function lokasi()   { return $this->belongsTo(Lokasi::class, 'lokasi_id'); }
    public function fotos()    { return $this->hasMany(FotoLaporan::class, 'laporan_id'); }

    /* ── Scopes ── */
    public function scopeTerverifikasi($q) { return $q->where('status', 'terverifikasi'); }
    public function scopeMenunggu($q)      { return $q->where('status', 'menunggu'); }
    public function scopeDugong($q)
    {
        return $q->whereHas('jenis', fn($j) => $j->where('nama','dugong'));
    }
    public function scopeDataHistoris($q)
    {
        return $q->where('kode','like','DB-%');
    }
    public function scopeDataMasyarakat($q)
    {
        return $q->where('kode','not like','DB-%');
    }

    /* ── Accessors ── */
    public function getJenisNamaAttribute(): string
    {
        return $this->jenis?->nama ?? 'dugong';
    }
    public function getKondisiNamaAttribute(): string
    {
        return $this->kondisi?->nama ?? '';
    }
    public function getKondisiLabelAttribute(): string
    {
        return $this->kondisi?->label ?? '—';
    }
    public function getLokasiNamaAttribute(): string
    {
        return $this->lokasi?->nama ?? $this->catatan ?? '—';
    }
    public function getIsHistorisAttribute(): bool
    {
        return str_starts_with($this->kode ?? '', 'DB-');
    }
    public function getFotoUtamaAttribute(): ?string
    {
        $foto = $this->fotos()->first();
        return $foto ? asset('storage/'.$foto->path) : null;
    }
}
