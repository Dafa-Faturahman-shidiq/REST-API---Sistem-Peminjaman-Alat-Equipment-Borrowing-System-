<?php

namespace App\Http\Requests\Alat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'integer', Rule::exists('kategori', 'id')],
            'nama_alat' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'status_kondisi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    //Opsional: Kustomisasi pesan error dalam bahasa Indonesia jika validasi gagal
    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori alat harus diisi.',
            'kategori_id.integer' => 'Kategori alat harus berupa angka.',
            'kategori_id.exists' => 'Kategori alat yang dipilih tidak valid.',

            'nama_alat.required' => 'Nama alat harus diisi.',
            'nama_alat.string' => 'Nama alat harus berupa teks.',
            'nama_alat.max' => 'Nama alat tidak boleh lebih dari 255 karakter.',

            'stok.required' => 'Stok alat harus diisi.',
            'stok.integer' => 'Stok alat harus berupa angka.',
            'stok.min' => 'Stok alat tidak boleh kurang dari 0.',

            'status_kondisi.required' => 'Status kondisi alat harus diisi.',
            'status_kondisi.string' => 'Status kondisi alat harus berupa teks.',
            'status_kondisi.max' => 'Status kondisi alat tidak boleh lebih dari 255 karakter.',

            'deskripsi.string' => 'Deskripsi alat harus berupa teks.',

            'gambar.image' => 'Gambar harus berupa file gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau svg.',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ];
    }

}
