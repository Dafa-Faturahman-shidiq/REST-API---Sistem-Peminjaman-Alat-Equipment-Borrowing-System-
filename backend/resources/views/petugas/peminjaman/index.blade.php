@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - Dashboard Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 flex items-center p-4 text-sm text-emerald-800 border border-emerald-200 rounded-xl bg-emerald-50 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center p-4 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Card Header & Search -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Menunggu Verifikasi Persetujuan</h3>
                <p class="text-sm text-slate-500 mt-1">Kelola permohonan peminjaman alat baru dari pengguna.</p>
            </div>
            
            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..." 
                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-sm">
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('petugas.peminjaman.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 text-sm font-semibold rounded-xl flex items-center transition-all border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200">
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest w-1/5">Peminjam</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">Tanggal Pinjam</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">Rencana Kembali</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest w-1/3">Detail Alat</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-sm divide-y divide-slate-100">
                    @forelse($peminjamans as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top group">
                            <!-- Peminjam -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">
                                    {{ $item->peminjam->name ?? 'User Dihapus' }}
                                </div>
                            </td>
                            
                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 font-medium text-slate-600">
                                {{ $item->tgl_pinjam }}
                            </td>
                            
                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 font-medium text-slate-600">
                                {{ $item->tgl_kembali_plan }}
                            </td>
                            
                            <!-- Detail Alat -->
                            <td class="py-4 px-6">
                                <ul class="space-y-2">
                                    @foreach($item->detailPinjam as $detail)
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold rounded-md">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center">
                                @if($item->status == 'diajukan')
                                    <div class="flex justify-center items-center gap-2">
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('petugas.peminjaman.setujui', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Setujui peminjaman alat ini?')" 
                                                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-sm shadow-emerald-500/30 active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Setujui
                                            </button>
                                        </form>
                                    
                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('petugas.peminjaman.tolak', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan peminjaman ini?')" 
                                                    class="inline-flex items-center gap-1.5 bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-sm shadow-rose-500/30 active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <!-- Badge Status (Selain Diajukan) -->
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-600">Tidak Ada Pengajuan</h4>
                                    <p class="text-sm text-slate-400 mt-1">Belum ada pengajuan peminjaman baru saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection