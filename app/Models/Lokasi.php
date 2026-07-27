<?php
// app/Models/Lokasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table    = 'lokasi';
    protected $fillable = ['nama','latitude','longitude','wilayah','keterangan'];

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'lokasi_id');
    }
}
