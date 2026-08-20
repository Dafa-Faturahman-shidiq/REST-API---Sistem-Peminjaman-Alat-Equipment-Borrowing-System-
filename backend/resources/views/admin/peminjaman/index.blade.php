@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Manajemen Transaksi Peminjaman')

@section('content')

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl text-sm shadow-sm flex items-center animate-fade-in">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi Error -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm shadow-sm flex items-center animate-fade-in">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Card Utama (Modern Style) -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transform transition-all duration-300">
        
        <!-- Header Card & Action Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Judul Tabel -->
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                    <!-- Ikon Clipboard/Transaksi -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Transaksi Peminjaman</h3>
            </div>
            
            <!-- Aksi Kanan (Search & Tambah) -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Form Pencarian -->
                <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam / status..." 
                           class="w-full sm:w-64 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm whitespace-nowrap">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.peminjaman.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 rounded-xl text-sm font-semibold transition flex items-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Peminjaman -->
                <a href="{{ route('admin.peminjaman.create') }}" class="group flex items-center justify-center gap-2 bg-slate-800 hover:bg-indigo-600 text-white text-sm font-semibold py-2 px-4 rounded-xl transition-all duration-300 active:scale-95 shadow-sm w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Peminjaman
                </a>
            </div>

        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Peminjam</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Alat yang Dipinjam</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Tgl Pinjam / Rencana Kembali</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50 text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-sm divide-y divide-slate-50">
                    
                    @forelse($peminjamans as $peminjaman)
                        <!-- Menggunakan align-top karena ada list barang agar tabel tetap rapi -->
                        <tr class="hover:bg-indigo-50/60 transition-colors duration-200 group align-top">
                            
                            <!-- Kolom Peminjam -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-slate-100 to-slate-200 border border-slate-300 flex items-center justify-center text-xs font-bold text-slate-600 group-hover:border-indigo-300 group-hover:text-indigo-600 transition-colors">
                                        {{ strtoupper(substr($peminjaman->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $peminjaman->user->name ?? 'User Dihapus' }}</span>
                                </div>
                            </td>

                            <!-- Kolom Daftar Alat -->
                            <td class="py-4 px-6">
                                <ul class="space-y-1.5 list-inside list-disc text-slate-500 marker:text-indigo-400">
                                    @foreach($peminjaman->detailPinjams as $detail)
                                        <li>
                                            <span class="font-medium text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-1 text-[10px] font-bold bg-slate-100 text-slate-500 rounded-md border border-slate-200 shadow-sm">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Kolom Tanggal -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-2">
                                    <!-- Tgl Pinjam -->
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Pinjam: <strong class="text-slate-700">{{ $peminjaman->tgl_pinjam }}</strong></span>
                                    </div>
                                    <!-- Rencana Kembali -->
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Rencana: <strong class="text-slate-700">{{ $peminjaman->tgl_kembali_plan }}</strong></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Status (Badge) -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border shadow-sm
                                    @if($peminjaman->status == 'diajukan') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($peminjaman->status == 'dipinjam') bg-blue-50 text-blue-700 border-blue-200
                                    @elseif($peminjaman->status == 'selesai') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @else bg-red-50 text-red-700 border-red-200
                                    @endif">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>

                            <!-- Kolom Aksi (Update Status Cepat & Hapus) -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-2">
                                    
                                    <!-- Form Ubah Status Cepat -->
                                    <form action="{{ route('admin.peminjaman.updateStatus', $peminjaman->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <div class="relative">
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-lg pl-2 pr-6 py-1.5 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer hover:bg-slate-100 transition-colors appearance-none text-slate-600 shadow-sm">
                                                <option value="diajukan" {{ $peminjaman->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                                <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                <option value="selesai"  {{ $peminjaman->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="telat"    {{ $peminjaman->status == 'telat' ? 'selected' : '' }}>Telat</option>
                                            </select>
                                            <!-- Custom Dropdown Arrow -->
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Tombol Hapus (Soft Style) -->
                                    <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-2 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg text-xs font-bold transition-colors border border-transparent hover:border-red-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
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
                                    <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p class="text-sm font-medium">Belum ada data transaksi peminjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            {{ $peminjamans->links() }}
        </div>

    </div>

@endsection