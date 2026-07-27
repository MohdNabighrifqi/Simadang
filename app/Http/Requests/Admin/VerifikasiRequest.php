<?php
// app/Http/Requests/Admin/VerifikasiRequest.php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status'  => ['required', 'in:terverifikasi,ditolak'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return ['status.required' => 'Status verifikasi wajib dipilih.'];
    }
}
