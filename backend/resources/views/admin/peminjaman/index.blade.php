@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Manajemen Transaksi Peminjaman')

@section('content')

    <!-- Card Utama (Light Minimalist Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card & Action Bar -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Judul Tabel -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Daftar Transaksi Peminjaman</h3>
                    <p class="text-xs text-slate-400 font-medium">Pantau pengajuan, persetujuan, dan jadwal peminjaman alat</p>
                </div>
            </div>
            
            <!-- Aksi Kanan (Search & Tambah) -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Form Pencarian -->
                <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / status..." 
                           class="w-full sm:w-64 px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-sm active:scale-95 whitespace-nowrap">
                        Cari
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.peminjaman.index') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs font-semibold transition flex items-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Peminjaman -->
                <a href="{{ route('admin.peminjaman.create') }}" class="group flex items-center justify-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white text-xs font-semibold py-2 px-4 rounded-xl transition-all duration-200 active:scale-95 shadow-sm w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Peminjaman</span>
                </a>
            </div>

        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Peminjam</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alat yang Dipinjam</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tgl Pinjam / Rencana Kembali</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100">
                    
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-indigo-50/40 transition-colors duration-150 group align-top">
                            
                            <!-- Kolom Peminjam -->
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 group-hover:border-indigo-200 transition-colors">
                                        {{ strtoupper(substr($peminjaman->peminjam->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $peminjaman->peminjam->name ?? 'User Dihapus' }}</span>
                                </div>
                            </td>

                            <!-- Kolom Daftar Alat -->
                            <td class="py-3.5 px-6">
                                <ul class="space-y-1 list-inside list-disc text-slate-500 marker:text-indigo-400">
                                    @foreach($peminjaman->detailPinjam as $detail)
                                        <li>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 ml-1 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-md border border-slate-200">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Kolom Tanggal -->
                            <td class="py-3.5 px-6">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Pinjam: <strong class="text-slate-800">{{ $peminjaman->tgl_pinjam }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Rencana: <strong class="text-slate-800">{{ $peminjaman->tgl_kembali_plan }}</strong></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Status -->
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border
                                    @if($peminjaman->status == 'diajukan') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($peminjaman->status == 'dipinjam') bg-blue-50 text-blue-700 border-blue-200
                                    @elseif($peminjaman->status == 'dikembalikan') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @else bg-red-50 text-red-700 border-red-200
                                    @endif">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="py-3.5 px-6">
                                <div class="flex flex-col gap-1.5">
                                    
                                    <!-- Form Ubah Status Cepat -->
                                    <form action="{{ route('admin.peminjaman.updateStatus', $peminjaman->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <div class="relative">
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="w-full text-xs font-semibold bg-white border border-slate-200 rounded-lg pl-2.5 pr-6 py-1.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none cursor-pointer hover:bg-slate-50 transition-all appearance-none text-slate-700 shadow-sm">
                                                <option value="diajukan" {{ $peminjaman->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                                <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                                <option value="telat" {{ $peminjaman->status == 'telat' ? 'selected' : '' }}>Telat</option>
                                            </select>
                                            
                                            <!-- Custom Dropdown Arrow -->
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center justify-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 rounded-lg text-xs font-bold transition-all active:scale-95">
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
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p class="text-xs font-semibold">Belum ada data transaksi peminjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-4 bg-slate-50/50 border-t border-slate-200/80">
            {{ $peminjamans->links() }}
        </div>

    </div>

@endsection