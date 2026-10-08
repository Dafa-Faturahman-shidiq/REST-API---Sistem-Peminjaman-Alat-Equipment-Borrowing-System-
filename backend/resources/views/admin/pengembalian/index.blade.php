@extends('layouts.app')

@section('title', 'Kelola Pengembalian - Panel Admin')
@section('header-title', 'Manajemen Pengembalian Alat')

@section('content')

    <!-- SECTION 1: DAFTAR BARANG YANG BELUM KEMBALI (SEDANG DIPINJAM) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Peminjaman Aktif (Menunggu Dikembalikan)</h3>
                <p class="text-xs text-slate-400 font-medium">Daftar alat yang saat ini masih dibawa oleh peminjam</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Peminjam</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alat yang Dipinjam</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rencana Kembali</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100 align-top">
                    @forelse($peminjamanDipinjam as $item)
                        <tr class="hover:bg-amber-50/30 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-800">
                                {{ $item->peminjam->name ?? 'User Dihapus' }}
                            </td>
                            <td class="py-3.5 px-6">
                                <ul class="space-y-1 list-inside list-disc text-slate-500 marker:text-amber-500">
                                    @foreach($item->detailPinjam as $detail)
                                        <li>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 ml-1 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-md border border-slate-200">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-slate-700">
                                {{ $item->tgl_kembali_plan }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <a href="{{ route('admin.peminjaman.kembali', $item->id) }}" 
                                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Proses Pengembalian</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center">
                                <p class="text-xs font-semibold text-slate-400">Tidak ada peminjaman aktif saat ini (Semua alat sudah dikembalikan).</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: RIWAYAT / TABEL PENGEMBALIAN -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Riwayat Pengembalian Selesai</h3>
                    <p class="text-xs text-slate-400 font-medium">Catatan pengembalian alat beserta kondisi dan denda</p>
                </div>
            </div>
            
            <!-- Form Search Riwayat -->
            <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..." 
                       class="w-full sm:w-64 px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-sm active:scale-95 whitespace-nowrap">
                    Cari
                </button>
                
                @if(request('search'))
                    <a href="{{ route('admin.pengembalian.index') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs font-semibold transition flex items-center whitespace-nowrap">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Peminjam</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tgl Kembali</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kondisi</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Denda</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100">
                    @forelse($pengembalians as $item)
                        <tr class="hover:bg-indigo-50/40 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-800">
                                {{ $item->peminjaman->peminjam->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 font-medium">
                                {{ $item->tgl_kembali }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-[11px] font-bold border border-slate-200">
                                    {{ $item->kondisi_kembali }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 font-extrabold text-slate-900">
                                Rp {{ number_format($item->denda, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <form action="{{ route('admin.pengembalian.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pengembalian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 rounded-lg text-xs font-bold transition-all active:scale-95">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <p class="text-xs font-semibold text-slate-400">Belum ada riwayat pengembalian tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50/50 border-t border-slate-200/80">
            {{ $pengembalians->links() }}
        </div>

    </div>

@endsection