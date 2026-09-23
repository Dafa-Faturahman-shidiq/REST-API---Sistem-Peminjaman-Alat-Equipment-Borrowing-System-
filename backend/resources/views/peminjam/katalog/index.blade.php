@extends('layouts.peminjam')

@section('title', 'Katalog Alat Tersedia - SIMPEL')

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

    <!-- Header Section & Filter Bar -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Daftar Peralatan Tersedia</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih peralatan yang ingin dipinjam untuk kegiatan praktikum / pembelajaran.</p>
            </div>

            <!-- Search & Filter Form -->
            <form action="{{ route('peminjam.katalog.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat..." 
                       class="px-4 py-2 text-xs border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/50 w-full sm:w-56">
                
                <select name="kategori_id" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-slate-300 rounded-xl bg-white outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">Cari</button>
            </form>
        </div>
    </div>

    <!-- Grid Kartu Alat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($alats as $alat)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <!-- Visual / Gambar Alat -->
                    <div class="h-40 bg-slate-100 relative flex items-center justify-center overflow-hidden">
                        @if($alat->gambar)
                        <img src="{{ asset('storage/' . (str_contains($alat->gambar, 'alats/') ? $alat->gambar : 'alats/' . $alat->gambar)) }}" 
                            alt="{{ $alat->nama_alat }}" 
                         class="w-full h-full object-cover">
                        @else
                            <div class="text-slate-300 text-center">
                                <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-bold mt-1 block">Tidak Ada Gambar</span>
                            </div>
                        @endif

                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-white/90 backdrop-blur-sm text-emerald-700 border border-emerald-200">
                            Stok: {{ $alat->stok }}
                        </span>
                    </div>

                    <!-- Info Alat -->
                    <div class="p-5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h3 class="font-bold text-slate-800 text-sm mt-2 line-clamp-1">{{ $alat->nama_alat }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">{{ $alat->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                </div>

                <!-- Tombol Pinjam -->
                <div class="p-5 pt-0">
                    <button onclick="bukaModalPinjam({{ $alat->id }}, '{{ addslashes($alat->nama_alat) }}', {{ $alat->stok }})" 
                            class="w-full bg-slate-900 hover:bg-indigo-600 text-white py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Pinjam Alat
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 text-slate-400 text-xs">
                Tidak ada peralatan yang sesuai dengan pencarian.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $alats->links() }}
    </div>

    <!-- MODAL FORM PINJAM -->
    <div id="modalPinjam" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-800">Form Ajukan Peminjaman</h3>
                <button onclick="tutupModalPinjam()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Alat Dipilih:</label>
                    <input type="text" id="modal_nama_alat" readonly class="w-full text-xs font-bold text-slate-800 bg-slate-100 border border-slate-200 rounded-xl px-3 py-2.5">
                    <input type="hidden" name="items[0][alat_id]" id="modal_alat_id">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jumlah Unit (Maks: <span id="modal_stok_max">0</span>):</label>
                    <input type="number" name="items[0][jumlah]" value="1" min="1" id="modal_jumlah" required class="w-full text-xs font-bold border border-slate-300 rounded-xl px-3 py-2.5">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Rencana Tanggal Pengembalian:</label>
                    <input type="date" name="tgl_kembali_plan" min="{{ date('Y-m-d') }}" required class="w-full text-xs font-bold border border-slate-300 rounded-xl px-3 py-2.5">
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="tutupModalPinjam()" class="w-1/2 bg-slate-100 text-slate-600 py-2.5 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold transition">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModalPinjam(id, nama, stok) {
            document.getElementById('modal_alat_id').value = id;
            document.getElementById('modal_nama_alat').value = nama;
            document.getElementById('modal_stok_max').innerText = stok;
            document.getElementById('modal_jumlah').max = stok;
            document.getElementById('modalPinjam').classList.remove('hidden');
        }

        function tutupModalPinjam() {
            document.getElementById('modalPinjam').classList.add('hidden');
        }
    </script>
@endsection