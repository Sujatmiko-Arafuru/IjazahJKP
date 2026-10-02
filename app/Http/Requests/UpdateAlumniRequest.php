<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlumniRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorized via auth middleware in routes
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_verifikasi' => 'required|string|in:Sudah Diverifikasi,Ditolak,Belum Diverifikasi',
            'catatan_admin' => 'nullable|string|required_if:status_verifikasi,Ditolak',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_admin.required_if' => 'Catatan / alasan penolakan wajib diisi jika verifikasi ditolak.',
        ];
    }
}
