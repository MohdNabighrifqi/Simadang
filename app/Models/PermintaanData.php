<?php
// app/Models/PermintaanData.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanData extends Model
{
    protected $table = 'permintaan_data';

    protected $fillable = [
        'user_id','nama_pemohon','email_pemohon','institusi',
        'tujuan','jenis_data','format_file','periode_dari','periode_sampai',
        'keterangan','status','catatan_admin',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getJenisDataLabelAttribute(): string
    {
        return match($this->jenis_data) {
            'laporan'   => 'Data Laporan Dugong',
            'statistik' => 'Data Statistik Konservasi',
            'lengkap'   => 'Data Lengkap',
            default     => $this->jenis_data,
        };
    }
}
