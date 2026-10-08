@extends('layouts.app')

@section('title', 'Edit Alat - Panel Admin')
@section('header-title', 'Edit Data Alat')

@section('content')

    <!-- Card Container Form (Light Minimalist Style) -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Form Edit Data Alat</h3>
                <p class="text-xs text-slate-400 font-medium">Perbarui rincian informasi dan status alat laboratorium</p>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Input Nama Alat -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required
                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                @error('nama_alat') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Select Kategori -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Kategori Alat</label>
                <select name="kategori_id" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    <input type="number" name="stok" value="{{ old('stok', $alat->stok) }}" min="0" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    @error('stok') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Kondisi -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', $alat->status_kondisi) }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    @error('status_kondisi') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Input Deskripsi -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                @error('deskripsi') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Upload Gambar & Preview Gambar Lama -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Gambar Alat <span class="text-slate-400 font-normal normal-case">(Biarkan kosong jika tidak ingin mengubah)</span>
                </label>
                
                <!-- Preview Gambar Lama -->
                @if($alat->gambar)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl w-fit mb-2">
                        <img src="{{ asset('storage/alats/' . $alat->gambar) }}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-200 shadow-sm">
                        <div>
                            <p class="text-xs font-bold text-slate-700">Gambar Saat Ini</p>
                            <p class="text-[10px] text-slate-400 font-medium">File akan diganti jika Anda mengunggah gambar baru.</p>
                        </div>
                    </div>
                @endif

                <div class="relative">
                    <input type="file" name="gambar" accept="image/*"
                           class="block w-full text-xs text-slate-500 border border-slate-200 rounded-xl bg-white file:cursor-pointer
                                  file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-xs file:font-semibold 
                                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
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
                        class="flex items-center gap-1.5 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Perbarui Data</span>
                </button>
            </div>
        </form>

    </div>

@endsection