@extends('layouts.app')

@section('content')
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Daftar Pengajuan Peminjaman Alat</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola permohonan peminjaman alat dari peminjam.</p>
            </div>

            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..." 
                       class="px-4 py-2 text-xs border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/50 w-full sm:w-64">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold">
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Tanggal Pinjam</th>
                        <th class="py-3 px-4">Rencana Kembali</th>
                        <th class="py-3 px-4">Detail Alat</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjamans as $pinjam)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $pinjam->peminjam->name ?? 'User' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $pinjam->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $pinjam->tgl_kembali_plan ?? $pinjam->tgl_kembali }}
                            </td>
                            <td class="py-3.5 px-4">
                                <ul class="space-y-1">
                                    @foreach($pinjam->detailPinjam as $detail)
                                        <li class="flex items-center gap-2">
                                            <span class="text-slate-700 font-medium">• {{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                                            <span class="bg-slate-100 text-slate-600 font-extrabold px-2 py-0.5 rounded-md text-[10px]">
                                                {{ $detail->jumlah }} pcs
                                            </span>

                                            @if(($detail->alat->stok ?? 0) < $detail->jumlah)
                                                <span class="text-rose-600 font-bold text-[10px] bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                                    Stok Kurang (Sisa: {{ $detail->alat->stok ?? 0 }})
                                                </span>
                                            @else
                                                <span class="text-emerald-600 font-bold text-[10px] bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    Stok Cukup (Sisa: {{ $detail->alat->stok }})
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="bukaModalPersetujuan({{ json_encode($pinjam) }})" 
                                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-sm flex items-center gap-1 active:scale-95">
                                        ✓ Verifikasi & Setujui
                                    </button>

                                    <form action="{{ route('petugas.peminjaman.tolak', $pinjam->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak seluruh pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-sm active:scale-95">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada pengajuan peminjaman baru yang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PERSETUJUAN PARSIAL -->
    <div id="modalPersetujuan" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-base text-slate-800">Verifikasi Persetujuan Alat</h3>
                    <p class="text-[11px] text-slate-400">Tentukan persetujuan per item barang yang diajukan</p>
                </div>
                <button onclick="tutupModalPersetujuan()" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">✕</button>
            </div>

            <form id="formApprovePartial" action="" method="POST" class="mt-4 space-y-4">
                @csrf
                <div id="itemContainer" class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    <!-- Ditarik via JavaScript -->
                </div>

                <div class="pt-3 border-t border-slate-100 flex gap-2">
                    <button type="button" onclick="tutupModalPersetujuan()" class="w-1/3 bg-slate-100 text-slate-600 font-bold py-2.5 rounded-xl text-xs hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="w-2/3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-md shadow-emerald-500/20 active:scale-95">
                        Simpan Keputusan Persetujuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC MODAL -->
    <script>
        function bukaModalPersetujuan(pinjam) {
            const form = document.getElementById('formApprovePartial');
            form.action = `/petugas/peminjaman/${pinjam.id}/setujui`;

            const container = document.getElementById('itemContainer');
            container.innerHTML = '';

            pinjam.detail_pinjam.forEach((detail, index) => {
                const stokAwal = detail.alat ? detail.alat.stok : 0;
                const namaAlat = detail.alat ? detail.alat.nama_alat : 'Alat';
                const stokCukup = stokAwal >= detail.jumlah;

                const div = document.createElement('div');
                div.className = 'p-3.5 rounded-2xl border border-slate-200 bg-slate-50/80 space-y-2';

                div.innerHTML = `
                    <input type="hidden" name="items[${index}][detail_id]" value="${detail.id}">
                    <div class="flex justify-between items-center">
                        <div>
                            <h5 class="font-bold text-xs text-slate-800">${namaAlat} (${detail.jumlah} pcs)</h5>
                            <span class="text-[10px] ${stokCukup ? 'text-emerald-600' : 'text-rose-600'} font-bold">
                                Sisa Stok di DB: ${stokAwal} unit
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-bold text-slate-700 flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="items[${index}][status]" value="disetujui" ${stokCukup ? 'checked' : ''} onchange="toggleAlasan(${index}, false)" class="text-emerald-600 focus:ring-emerald-500"> Setujui
                            </label>
                            <label class="text-xs font-bold text-slate-700 flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="items[${index}][status]" value="ditolak" ${!stokCukup ? 'checked' : ''} onchange="toggleAlasan(${index}, true)" class="text-rose-600 focus:ring-rose-500"> Tolak
                            </label>
                        </div>
                    </div>
                    <div id="alasanContainer_${index}" class="${stokCukup ? 'hidden' : ''}">
                        <input type="text" name="items[${index}][alasan_penolakan]" 
                               value="${!stokCukup ? 'Stok alat habis dipinjam pengguna lain' : ''}" 
                               placeholder="Alasan penolakan alat ini..." 
                               class="w-full text-xs p-2.5 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-rose-500/50 bg-white">
                    </div>
                `;
                container.appendChild(div);
            });

            document.getElementById('modalPersetujuan').classList.remove('hidden');
        }

        function toggleAlasan(index, show) {
            const el = document.getElementById(`alasanContainer_${index}`);
            if (show) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        function tutupModalPersetujuan() {
            document.getElementById('modalPersetujuan').classList.add('hidden');
        }
    </script>
@endsection