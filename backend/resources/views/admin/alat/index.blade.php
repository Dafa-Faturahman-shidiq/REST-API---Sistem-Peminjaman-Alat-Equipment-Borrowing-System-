@extends('layouts.app')

@section('title', 'Kelola Alat - Panel Admin')
@section('header-title', 'Manajemen Data Alat')

@section('content')

    <!-- Card Utama (Light Minimalist Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card & Action Bar -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Judul Tabel -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Daftar Alat Laboratorium</h3>
                    <p class="text-xs text-slate-400 font-medium">Kelola seluruh ketersediaan dan kondisi alat</p>
                </div>
            </div>
            
            <!-- Aksi Kanan (Search & Tambah) -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Form Pencarian -->
                <form action="{{ route('admin.alat.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat, kategori..." 
                           class="w-full sm:w-64 px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-sm active:scale-95 whitespace-nowrap">
                        Cari
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.alat.index') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs font-semibold transition flex items-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Alat -->
                <a href="{{ route('admin.alat.create') }}" class="group flex items-center justify-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white text-xs font-semibold py-2 px-4 rounded-xl transition-all duration-200 active:scale-95 shadow-sm w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Alat</span>
                </a>
            </div>

        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-24 text-center">Gambar</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Alat</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Stok</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kondisi</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100">
                    
                    @forelse($alats as $alat)
                        <tr class="hover:bg-indigo-50/40 transition-colors duration-150 group">
                            
                            <!-- Kolom Gambar -->
                            <td class="py-3 px-6 text-center align-middle">
                                @if($alat->gambar)
                                    <img src="{{ asset('storage/alats/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" 
                                         class="w-11 h-11 object-cover rounded-xl border border-slate-200 shadow-sm mx-auto group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-slate-100 text-slate-400 text-[10px] font-medium border border-slate-200 mx-auto">
                                        No Image
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Nama Alat -->
                            <td class="py-3 px-6 font-bold text-slate-800 align-middle">
                                {{ $alat->nama_alat }}
                            </td>

                            <!-- Kolom Kategori -->
                            <td class="py-3 px-6 align-middle">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $alat->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>

                            <!-- Kolom Stok -->
                            <td class="py-3 px-6 text-center align-middle font-extrabold text-slate-900 text-sm">
                                {{ $alat->stok }}
                            </td>

                            <!-- Kolom Kondisi -->
                            <td class="py-3 px-6 align-middle">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border
                                    @if(strtolower($alat->status_kondisi) == 'baik') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @else bg-amber-50 text-amber-700 border-amber-200
                                    @endif">
                                    {{ ucfirst($alat->status_kondisi) }}
                                </span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="py-3 px-6 align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.alat.edit', $alat->id) }}" 
                                       class="flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 rounded-lg text-xs font-bold transition-all active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alat ini?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 rounded-lg text-xs font-bold transition-all active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <!-- Data Kosong -->
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <p class="text-xs font-semibold">Belum ada data alat yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-4 bg-slate-50/50 border-t border-slate-200/80">
            {{ $alats->links() }}
        </div>

    </div>

@endsection