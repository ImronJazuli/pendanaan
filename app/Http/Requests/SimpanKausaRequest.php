<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanKausaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->peran === 'institution_user';
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
            'judul' => ['required', 'string', 'max:255'],
            'kategori_kausa_id' => ['nullable', 'exists:kategori_kausa,id'],
            'lokasi' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string', 'max:1000'],
            'deskripsi' => ['required', 'string'],
            'target_dana' => ['required', 'numeric', 'min:1', 'max:9999999999999.99'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'dokumen' => ['nullable', 'array', 'max:10'],
            'dokumen.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
