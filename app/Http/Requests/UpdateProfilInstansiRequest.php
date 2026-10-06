<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilInstansiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        return in_array($user->role ?? '', ['instansi', 'institution_user'], true)
            || in_array($user->peran ?? '', ['instansi', 'institution_user'], true);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->filled('nama_lembaga') && ! $this->filled('nama')) {
            $merge['nama'] = $this->input('nama_lembaga');
        } elseif ($this->filled('nama') && ! $this->filled('nama_lembaga')) {
            $merge['nama_lembaga'] = $this->input('nama');
        }

        if ($this->filled('jenis_badan_hukum') && ! $this->filled('jenis')) {
            $merge['jenis'] = $this->input('jenis_badan_hukum');
        } elseif ($this->filled('jenis') && ! $this->filled('jenis_badan_hukum')) {
            $merge['jenis_badan_hukum'] = $this->input('jenis');
        }

        if ($this->filled('no_sk_kemenkumham') && ! $this->filled('nomor_registrasi')) {
            $merge['nomor_registrasi'] = $this->input('no_sk_kemenkumham');
        } elseif ($this->filled('nomor_registrasi') && ! $this->filled('no_sk_kemenkumham')) {
            $merge['no_sk_kemenkumham'] = $this->input('nomor_registrasi');
        }

        if ($this->filled('alamat_kantor') && ! $this->filled('alamat')) {
            $merge['alamat'] = $this->input('alamat_kantor');
        } elseif ($this->filled('alamat') && ! $this->filled('alamat_kantor')) {
            $merge['alamat_kantor'] = $this->input('alamat');
        }

        if ($this->filled('wa_pj') && ! $this->filled('nomor_telepon')) {
            $merge['nomor_telepon'] = $this->input('wa_pj');
        } elseif ($this->filled('nomor_telepon') && ! $this->filled('wa_pj')) {
            $merge['wa_pj'] = $this->input('nomor_telepon');
        }

        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nama_lembaga' => ['sometimes', 'nullable', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:50'],
            'jenis_badan_hukum' => ['sometimes', 'nullable', 'string', 'max:50'],
            'nomor_registrasi' => ['nullable', 'string', 'max:100'],
            'no_sk_kemenkumham' => ['nullable', 'string', 'max:100'],
            'no_dinsos' => ['nullable', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:1000'],
            'alamat_kantor' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'nomor_telepon' => ['required', 'string', 'max:30'],
            'wa_pj' => ['sometimes', 'nullable', 'string', 'max:30'],
            'nama_pj' => ['nullable', 'string', 'max:255'],
            'nik_pj' => ['nullable', 'string', 'max:16'],
            'npwp_lembaga' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lembaga wajib diisi.',
            'nama.max' => 'Nama lembaga maksimal 255 karakter.',
            'nama_lembaga.required' => 'Nama lembaga wajib diisi.',
            'jenis.required' => 'Bentuk entitas / jenis badan hukum wajib dipilih.',
            'alamat.required' => 'Alamat lembaga wajib diisi.',
            'alamat_kantor.required' => 'Alamat lembaga wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon / WhatsApp resmi wajib diisi.',
            'wa_pj.required' => 'Nomor telepon / WhatsApp resmi wajib diisi.',
            'nik_pj.max' => 'NIK penanggung jawab maksimal 16 digit.',
            'npwp_lembaga.max' => 'NPWP lembaga maksimal 30 karakter.',
        ];
    }
}
