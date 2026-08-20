@extends('layouts.app')

@section('title', 'Tambah Alat - Panel Admin')
@section('header-title', 'Tambah Alat Baru')

@section('content')

    <!-- Card Container Form Modern -->
    <!-- Menggunakan max-w-3xl agar lebih lega untuk form dua kolom -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">
        
        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <!-- Ikon Alat / Box -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Form Tambah Alat Laboratorium</h3>
        </div>

        <!-- Form Body -->
        <!-- Jangan lupa enctype multipart/form-data untuk upload gambar -->
        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <!-- Input Nama Alat -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                       placeholder="Contoh: Multimeter Digital">
                @error('nama_alat') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Select Kategori -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Kategori Alat</label>
                <select name="kategori_id" required 
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Grid Kolom 2 untuk Stok dan Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input Stok -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Stok Tersedia</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                    @error('stok') 
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Kondisi -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', 'Baik') }}" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                           placeholder="Contoh: Baik / Rusak Ringan">
                    @error('status_kondisi') 
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Input Deskripsi -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Deskripsi <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                          placeholder="Keterangan tambahan tentang alat (spesifikasi, merk, dll)...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Upload Gambar (Custom Styled) -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Gambar Alat <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <div class="relative">
                    <input type="file" name="gambar" accept="image/*"
                           class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl bg-slate-50/50 file:cursor-pointer
                                  file:mr-4 file:py-3 file:px-4 file:border-0 file:text-sm file:font-semibold 
                                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, atau JPEG. Ukuran maksimal 2MB.</p>
                @error('gambar') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.alat.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 active:scale-95">
                    Simpan Data Alat
                </button>
            </div>
        </form>

    </div>

@endsection