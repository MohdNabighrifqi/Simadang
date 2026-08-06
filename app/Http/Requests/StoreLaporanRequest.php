<?php
// app/Http/Requests/StoreLaporanRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'jenis'          => ['required','in:dugong,habitat'],
            'kondisi_dugong' => ['nullable','in:hidup,mati_terdampar,mati_tertangkap'],
            'tanggal'        => ['required','date','before_or_equal:today'],
            'waktu'          => ['nullable','date_format:H:i'],
            'lokasi'         => ['required','string','max:100'],
            'jumlah_dugong'  => ['nullable','integer','min:1','max:999'],
            'lat_coord'      => ['nullable','numeric','between:-90,90'],
            'lng_coord'      => ['nullable','numeric','between:-180,180'],
            'deskripsi'      => ['required','string','min:10','max:3000'],
            'nama_pelapor'   => ['required','string','min:3','max:100'],
            'no_hp'          => ['required','string','max:30'],
            'email'          => ['nullable','email','max:100'],
            'foto'           => ['required','array','min:1','max:5'],
            'foto.*'         => ['file','mimes:jpg,jpeg,png,webp,mp4,mov,webm','max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.required'          => 'Jenis laporan wajib dipilih.',
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'lokasi.required'         => 'Lokasi wajib dipilih.',
            'deskripsi.required'      => 'Deskripsi wajib diisi.',
            'deskripsi.min'           => 'Deskripsi minimal 10 karakter.',
            'nama_pelapor.required'   => 'Nama pelapor wajib diisi.',
            'no_hp.required'          => 'Nomor HP wajib diisi agar tim admin dapat menghubungi Anda kembali.',
            'email.email'             => 'Format email tidak valid.',
            'foto.required'           => 'Foto atau video bukti wajib dilampirkan agar laporan dapat diverifikasi.',
            'foto.max'                => 'Maksimal 5 file per laporan.',
            'foto.*.mimes'            => 'File harus berupa foto (JPG/PNG/WEBP) atau video (MP4/MOV/WEBM).',
            'foto.*.max'              => 'Ukuran tiap file maksimal 20MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (auth()->check() && empty($this->nama_pelapor)) {
            $this->merge(['nama_pelapor' => auth()->user()->name]);
        }
        if ($this->jenis === 'habitat') {
            $this->merge(['kondisi_dugong' => null, 'jumlah_dugong' => null]);
        }
    }
}
