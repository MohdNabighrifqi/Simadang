<?php
// app/Http/Controllers/PermintaanDataController.php
namespace App\Http\Controllers;

use App\Models\PermintaanData;
use Illuminate\Http\Request;

class PermintaanDataController extends Controller
{
    public function create()
    {
        return view('permintaan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pemohon'  => ['required','string','max:100'],
            'email_pemohon' => ['required','email','max:150'],
            'institusi'     => ['nullable','string','max:150'],
            'tujuan'        => ['required','string','max:255'],
            'jenis_data'    => ['required','in:laporan,statistik,lengkap'],
            'format_file'   => ['required','in:xlsx,pdf'],
            'periode_dari'  => ['nullable','digits:4','integer','min:2000'],
            'periode_sampai'=> ['nullable','digits:4','integer','min:2000'],
            'keterangan'    => ['nullable','string','max:1000'],
        ], [
            'nama_pemohon.required'  => 'Nama wajib diisi.',
            'email_pemohon.required' => 'Email wajib diisi.',
            'tujuan.required'        => 'Tujuan penggunaan data wajib diisi.',
            'jenis_data.required'    => 'Jenis data wajib dipilih.',
        ]);

        $data['user_id'] = auth()->id();
        $permintaan = PermintaanData::create($data);

        return redirect()->route('permintaan.sukses', $permintaan->id);
    }

    public function sukses(PermintaanData $permintaan)
    {
        return view('permintaan.sukses', compact('permintaan'));
    }
}
