@extends('layouts.peminjam')

@section('title', 'Riwayat Peminjaman Saya - SIMPEL')

@section('content')
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Tabel Riwayat -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Riwayat Peminjaman Saya</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar seluruh transaksi peminjaman peralatan yang telah diajukan.</p>
            </div>

            <!-- Filter Status -->
            <form action="{{ route('peminjam.riwayat.index') }}" method="GET">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs font-bold border border-slate-300 rounded-xl bg-white outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <th class="py-4 px-6">Alat yang Dipinjam</th>
                        <th class="py-4 px-6">Tgl Pinjam</th>
                        <th class="py-4 px-6">Rencana Kembali</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Denda / Catatan</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100 font-medium">
                    @forelse($peminjamans as $item)
                        <tr class="hover:bg-slate-50/80 transition align-top">
                            <!-- Detail Alat -->
                            <td class="py-4 px-6">
                                <ul class="space-y-1">
                                    @foreach($item->detailPinjam as $detail)
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            <span class="font-bold text-slate-800">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold rounded-md">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 whitespace-nowrap">{{ $item->tgl_pinjam }}</td>

                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 whitespace-nowrap">{{ $item->tgl_kembali_plan }}</td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold border shadow-sm inline-block
                                    @if($item->status == 'diajukan') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($item->status == 'dipinjam') bg-blue-50 text-blue-700 border-blue-200
                                    @elseif($item->status == 'dikembalikan') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @else bg-red-50 text-red-700 border-red-200
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <!-- Denda & Catatan -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($item->pengembalian)
                                    <div class="font-bold text-slate-800">
                                        Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 capitalize">
                                        Kondisi: {{ $item->pengembalian->kondisi_kembali }}
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Belum ada riwayat peminjaman alat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            {{ $peminjamans->links() }}
        </div>
    </div>
@endsection