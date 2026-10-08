@extends('layouts.app')

@section('title', 'Tambah Alat - Panel Admin')
@section('header-title', 'Tambah Alat Baru')

@section('content')

    <!-- Card Container Form (Light Minimalist Style) -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Form Tambah Alat Laboratorium</h3>
                <p class="text-xs text-slate-400 font-medium">Isi rincian informasi alat baru secara lengkap</p>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Input Nama Alat -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required
                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                       placeholder="Contoh: Multimeter Digital">
                @error('nama_alat') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Select Kategori -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Kategori Alat</label>
                <select name="kategori_id" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Grid Kolom 2 untuk Stok dan Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Input Stok -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Stok Tersedia</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    @error('stok') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Kondisi -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', 'Baik') }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                           placeholder="Contoh: Baik / Rusak Ringan">
                    @error('status_kondisi') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Input Deskripsi -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Deskripsi <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
                </label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                          placeholder="Keterangan tambahan tentang alat (spesifikasi, merk, dll)...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Upload Gambar -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Gambar Alat <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
                </label>
                <div class="relative">
                    <input type="file" name="gambar" accept="image/*"
                           class="block w-full text-xs text-slate-500 border border-slate-200 rounded-xl bg-white file:cursor-pointer
                                  file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-xs file:font-semibold 
                                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Format: JPG, PNG, atau JPEG. Ukuran maksimal 2MB.</p>
                @error('gambar') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200/80">
                <a href="{{ route('admin.alat.index') }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                    Simpan Data Alat
                </button>
            </div>
        </form>

    </div>

@endsection