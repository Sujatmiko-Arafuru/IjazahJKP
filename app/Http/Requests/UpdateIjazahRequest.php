<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIjazahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Protected by auth middleware in routes
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:Belum Siap,Siap Diambil,Sudah Diambil',
            'tanggal_siap' => 'nullable|date',
            'tanggal_diambil' => 'nullable|date',
            'lokasi_pengambilan' => 'nullable|string|max:255',
            'jam_operasional' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ];
    }
}
