@extends('layouts.app')

@section('title', 'Edit Kategori - Panel Admin')
@section('header-title', 'Edit Kategori Alat')

@section('content')

    <!-- Card Container Form Modern -->
    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">
        
        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <!-- Ikon Edit / Pencil -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Form Edit Kategori Alat</h3>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            <!-- Method spoofing untuk operasi Update -->
            @method('PUT')

            <!-- Input Nama Kategori -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Nama Kategori</label>
                <!-- Value diisi dengan old() atau fallback ke data lama dari database -->
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                
                <!-- Pesan Error Validasi -->
                @error('nama_kategori') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.kategori.index') }}" 
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