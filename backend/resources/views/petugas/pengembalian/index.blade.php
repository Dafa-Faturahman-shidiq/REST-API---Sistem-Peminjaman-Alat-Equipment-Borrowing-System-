@extends('layouts.app')

@section('content')

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <h2 class="text-xl font-bold mb-4 text-slate-800">Pemantauan Pengembalian Alat</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 uppercase text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-3">Peminjam</th>
                        <th class="p-3">Tanggal Pinjam</th>
                        <th class="p-3">Detail Barang Disetujui</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $pinjam)
                    <tr class="border-b hover:bg-slate-50/50 transition">
                        <td class="p-3 font-bold text-slate-800">{{ $pinjam->peminjam->name ?? 'User' }}</td>
                        <td class="p-3 text-slate-600">{{ $pinjam->tgl_pinjam }}</td>
                        <td class="p-3">
                            <ul class="space-y-1">
                                @foreach($pinjam->detailPinjam as $detail)
                                    @if(is_null($detail->status) || $detail->status == 'disetujui' || $detail->status == 'pending')
                                        <li class="text-slate-700">• {{ $detail->alat->nama_alat ?? 'Alat' }} <span class="font-bold">({{ $detail->jumlah }} pcs)</span></li>
                                    @endif
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-3 text-center">
                            <button onclick="bukaModalPengembalian({{ json_encode($pinjam) }})" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-xl transition shadow-sm active:scale-95">
                                Proses Pengembalian
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400">Tidak ada alat yang sedang dipinjam.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PROSES PENGEMBALIAN PER-ITEM -->
    <div id="modalPengembalian" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-800">Proses Pengembalian Barang</h3>
                <button onclick="tutupModalPengembalian()" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">✕</button>
            </div>

            <form id="formProsesKembali" action="" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pengembalian</label>
                    <input type="date" name="tgl_kembali" value="{{ date('Y-m-d') }}" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/50">
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1" id="itemKembaliContainer">
                    <!-- Generator JS -->
                </div>

                <div class="pt-3 border-t border-slate-100 flex gap-2">
                    <button type="button" onclick="tutupModalPengembalian()" class="w-1/3 bg-slate-100 text-slate-600 font-bold py-2.5 rounded-xl text-xs hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-500/20 active:scale-95">Simpan & Selesaikan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function bukaModalPengembalian(pinjam) {
        const form = document.getElementById('formProsesKembali');
        form.action = `/petugas/pengembalian/${pinjam.id}/proses`;

        const container = document.getElementById('itemKembaliContainer');
        container.innerHTML = '';

        // Ambil item disetujui, pending, atau null (data lama)
        const itemsDisetujui = pinjam.detail_pinjam.filter(d => d.status === 'disetujui' || d.status === null || d.status === 'pending');

        itemsDisetujui.forEach((detail, index) => {
            const div = document.createElement('div');
            div.className = 'p-3.5 rounded-2xl border border-slate-200 bg-slate-50 space-y-2';

            const namaAlat = detail.alat ? detail.alat.nama_alat : 'Alat';

            div.innerHTML = `
                <input type="hidden" name="items[${index}][detail_id]" value="${detail.id}">
                <div class="flex justify-between items-center">
                    <h5 class="font-bold text-xs text-slate-800">${namaAlat} (${detail.jumlah} pcs)</h5>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500">Kondisi Barang</label>
                        <select name="items[${index}][kondisi_kembali]" onchange="cekDendaAuto(${index}, this.value)" class="w-full text-xs p-2 border border-slate-300 rounded-xl bg-white outline-none focus:ring-2 focus:ring-indigo-500/50">
                            <option value="baik">Baik (Tanpa Denda)</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500">Denda Barang Ini (Rp)</label>
                        <input type="number" id="inputDenda_${index}" name="items[${index}][denda]" value="0" min="0" placeholder="0" class="w-full text-xs p-2 border border-slate-300 rounded-xl bg-white outline-none focus:ring-2 focus:ring-indigo-500/50">
                    </div>
                </div>
            `;
            container.appendChild(div);
        });

        document.getElementById('modalPengembalian').classList.remove('hidden');
    }

    function cekDendaAuto(index, kondisi) {
        const inputDenda = document.getElementById(`inputDenda_${index}`);
        if (kondisi === 'baik') {
            inputDenda.value = 0;
        } else if (kondisi === 'rusak_ringan') {
            inputDenda.value = 15000;
        } else if (kondisi === 'rusak_berat' || kondisi === 'hilang') {
            inputDenda.value = 50000;
        }
    }

    function tutupModalPengembalian() {
        document.getElementById('modalPengembalian').classList.add('hidden');
    }
    </script>
@endsection