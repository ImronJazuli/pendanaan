<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKausaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $isInstansi = ($user->role === 'instansi' || $user->peran === 'institution_user');
        if (! $isInstansi) {
            return false;
        }

        $kausa = $this->route('kausa');
        if (! $kausa || ! $user->instansi) {
            return false;
        }

        // Hanya instansi pemilik yang boleh mengedit
        if ($kausa->instansi_id !== $user->instansi->id) {
            return false;
        }

        // Hanya boleh mengedit jika berstatus draf atau perlu_diperbaiki
        return in_array($kausa->status, ['draf', 'perlu_diperbaiki'], true);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('target_dana') && is_string($this->target_dana)) {
            $this->merge([
                'target_dana' => str_replace(['.', ','], ['', '.'], $this->target_dana),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'action' => ['nullable', 'in:draft,submit'],
            'judul' => ['required', 'string', 'max:255'],
            'kategori_kausa_id' => ['required', 'exists:kategori_kausa,id'],
            'lokasi' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string', 'max:1000'],
            'deskripsi' => ['required', 'string'],
            'target_dana' => ['required', 'numeric', 'min:1', 'max:9999999999999.99'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'catatan_perbaikan' => ['nullable', 'string', 'max:1000'],
            'dokumen' => ['nullable', 'array', 'max:10'],
            'dokumen.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'hapus_dokumen' => ['nullable', 'array'],
            'hapus_dokumen.*' => ['integer', 'exists:dokumen_kausa,id'],
        ];
    }
}
