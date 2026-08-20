@extends('layouts.app')

@section('title', 'Edit Alat - Panel Admin')
@section('header-title', 'Edit Data Alat')

@section('content')

    <!-- Card Container Form Modern -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">
        
        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <!-- Ikon Edit / Pencil -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Form Edit Data Alat</h3>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Input Nama Alat -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                @error('nama_alat') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Select Kategori -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Kategori Alat</label>
                <select name="kategori_id" required 
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    <input type="number" name="stok" value="{{ old('stok', $alat->stok) }}" min="0" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                    @error('stok') 
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Kondisi -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', $alat->status_kondisi) }}" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                    @error('status_kondisi') 
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Input Deskripsi -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                @error('deskripsi') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Upload Gambar & Preview Gambar Lama -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">
                    Gambar Alat <span class="text-slate-400 font-normal">(Biarkan kosong jika tidak ingin mengubah gambar)</span>
                </label>
                
                <!-- Preview Gambar Lama -->
                @if($alat->gambar)
                    <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl w-fit mb-3">
                        <img src="{{ asset($alat->gambar) }}" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                        <div>
                            <p class="text-xs font-semibold text-slate-700">Gambar Saat Ini</p>
                            <p class="text-[10px] text-slate-400">File akan diganti jika Anda mengunggah gambar baru.</p>
                        </div>
                    </div>
                @endif

                <div class="relative">
                    <input type="file" name="gambar" accept="image/*"
                           class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl bg-slate-50/50 file:cursor-pointer
                                  file:mr-4 file:py-3 file:px-4 file:border-0 file:text-sm file:font-semibold 
                                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
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
                        class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Perbarui Data
                </button>
            </div>
        </form>

    </div>

@endsection