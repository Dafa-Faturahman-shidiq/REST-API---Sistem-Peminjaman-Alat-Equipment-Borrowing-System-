@extends('layouts.app')

@section('title', 'Laporan Peminjaman - Dashboard Petugas')
@section('header-title', 'Laporan Peminjaman & Pengembalian Alat')

@section('content')

    <!-- Bagian Filter Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 tracking-tight">Filter Laporan</h3>
        </div>

        <form action="{{ route('petugas.laporan.index') }}" method="GET" class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Status Peminjaman:</label>
                <select name="status" class="w-full text-sm border border-slate-300 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 font-medium text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </div>

            <!-- Dari Tanggal -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Dari Tanggal (Pinjam):</label>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                       class="w-full text-sm border border-slate-300 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 font-medium text-slate-700">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Sampai Tanggal (Pinjam):</label>
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                       class="w-full text-sm border border-slate-300 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 font-medium text-slate-700">
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                    Filter
                </button>
                <a href="{{ route('petugas.laporan.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 text-sm font-semibold rounded-xl flex items-center justify-center transition-all border border-slate-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Hasil & Tombol Cetak -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Hasil Rekap Laporan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan data transaksi berdasarkan filter yang dipilih.</p>
            </div>
            
            <a href="{{ route('petugas.laporan.cetak', request()->all()) }}" target="_blank" 
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Print Laporan</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-widest">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Peminjam</th>
                        <th class="py-4 px-6">Tgl Pinjam</th>
                        <th class="py-4 px-6">Rencana Kembali</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Detail Alat</th>
                        <th class="py-4 px-6">Denda</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-sm divide-y divide-slate-100">
                    @forelse($laporans as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top">
                            <!-- No -->
                            <td class="py-4 px-6 font-medium text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            <!-- Peminjam -->
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $item->user->name ?? '-' }}
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 font-medium text-slate-600 whitespace-nowrap">
                                {{ $item->tgl_pinjam }}
                            </td>

                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 font-medium text-slate-600 whitespace-nowrap">
                                {{ $item->tgl_kembali_plan }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $item->status == 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $item->status == 'dipinjam' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    {{ $item->status == 'telat' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                    {{ $item->status == 'diajukan' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <!-- Detail Alat -->
                            <td class="py-4 px-6">
                                <ul class="space-y-1">
                                    @foreach($item->detailPinjams as $detail)
                                        <li class="flex items-center gap-2 text-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? '-' }}</span>
                                            <span class="text-slate-400">({{ $detail->jumlah }} pcs)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Denda -->
                            <td class="py-4 px-6 font-bold text-slate-700 whitespace-nowrap">
                                Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-600">Tidak Ada Data Laporan</h4>
                                    <p class="text-sm text-slate-400 mt-1">Tidak ada data laporan yang sesuai dengan filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection