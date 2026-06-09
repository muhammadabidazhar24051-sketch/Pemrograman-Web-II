<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\KodeBukuFormat;

class StoreBukuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Tugas 1: Custom Validation Rule
            'kode_buku' => ['required', 'string', 'max:20', 'unique:buku,kode_buku', new KodeBukuFormat],
            'judul' => 'required|string|max:200',
            'kategori' => 'required|in:Programming,Database,Web Design,Networking,Data Science',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'harga' => 'required|numeric|min:0',

            // Tugas 1: Conditional Validation Rule
            'stok' => [
                'required', 'integer', 'min:0',
                $this->tahun_terbit < 2000 ? 'max:5' : ''
            ],

            'deskripsi' => 'nullable|string',

            // tUGAS 1: Conditional Validation
            'bahasa' => [
                'required', 'string', 'max:20',
                $this->kategori === 'Programming' ? 'in:Inggris' : ''
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'kode_buku.max' => 'Kode buku maksimal 20 karakter.',
            'judul.required' => 'Judul buku wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'pengarang.required' => 'Nama pengarang wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
            'harga.required' => 'Harga buku wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.max' => 'Untuk buku terbitan sebelum tahun 2000, stok maksimal adalah 5.',
            'bahasa.required' => 'Bahasa wajib diisi.',
            'bahasa.in' => 'Jika kategori adalah Programming, bahasa harus Inggris.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_buku' => 'kode buku',
            'judul' => 'judul buku',
            'kategori' => 'kategori',
            'pengarang' => 'nama pengarang',
            'penerbit' => 'nama penerbit',
            'tahun_terbit' => 'tahun terbit',
            'isbn' => 'ISBN',
            'harga' => 'harga',
            'stok' => 'stok',
            'bahasa' => 'bahasa',
        ];
    }
}