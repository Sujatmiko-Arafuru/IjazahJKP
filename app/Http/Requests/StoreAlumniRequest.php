<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumniRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public access
    }

    /**
     * Prepare the data for validation.
     * Normalizes dd/mm/yyyy to Y-m-d format if needed.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('tanggal_lahir') && is_string($this->tanggal_lahir)) {
            $tgl = trim($this->tanggal_lahir);
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $tgl, $m)) {
                $this->merge([
                    'tanggal_lahir' => sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1])
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Step 1: Biodata
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:alumni,nim',
            'nik' => 'required|string|size:16|regex:/^[0-9]+$/',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'program_studi' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer',
            'email' => ['required', 'string', 'email:rfc', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'max:255'],
            'no_hp' => 'required|string|regex:/^[0-9]+$/|max:20',
            'alamat' => 'required|string',

            // Step 2: Berkas Persyaratan (Pas Foto + Link Google Drive 5 Dokumen)
            'pas_foto' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'drive_link' => 'required|string|url|max:500',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar dalam sistem.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'tahun_masuk.max' => 'Tahun masuk tidak boleh lebih dari tahun ' . (date('Y') + 1) . '.',
            'tahun_masuk.min' => 'Tahun masuk tidak boleh kurang dari tahun 1900.',
            'tahun_lulus.max' => 'Tahun lulus tidak boleh lebih dari tahun ' . (date('Y') + 1) . '.',
            'tahun_lulus.min' => 'Tahun lulus tidak boleh kurang dari tahun 1900.',
            'tahun_lulus.gte' => 'Tahun lulus tidak boleh kurang dari tahun masuk.',
            'jurusan.required' => 'Mohon pilih Jurusan terlebih dahulu.',
            'program_studi.required' => 'Mohon pilih Program Studi yang sesuai dengan Jurusan Anda.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid. Harus mengandung "@" dan domain yang benar.',
            'email.regex' => 'Format email tidak valid. Harus menggunakan "@" dan nama domain yang benar (contoh: alumni@email.com).',

            'pas_foto.required' => 'Mohon unggah Pas Foto resmi.',
            'pas_foto.mimes' => 'Pas foto harus berformat JPG, JPEG, PNG, atau PDF.',
            'pas_foto.max' => 'Ukuran pas foto maksimal 5 MB.',

            'drive_link.required' => 'Mohon masukkan Link Google Drive Berkas Persyaratan.',
            'drive_link.url' => 'Format Link Google Drive tidak valid. Harus diawali dengan http:// atau https://.',
        ];
    }
}
