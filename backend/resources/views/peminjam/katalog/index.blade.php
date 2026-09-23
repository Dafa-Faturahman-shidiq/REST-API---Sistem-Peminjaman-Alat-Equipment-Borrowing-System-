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
                <p class="text-xs text-slate-500 mt-0.5">Pilih peralatan yang ingin dipinjam, tambahkan ke keranjang, dan ajukan sekaligus.</p>
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
            @php
                $imgPath = asset('storage/' . (str_contains($alat->gambar ?? '', 'alats/') ? $alat->gambar : 'alats/' .$alat->gambar));
                $hasImage = !empty($alat->gambar);
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <!-- Visual / Gambar Alat -->
                    <div class="h-44 bg-slate-100 relative flex items-center justify-center overflow-hidden">
                        @if($hasImage)
                            <img src="{{ $imgPath }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-slate-300 text-center">
                                <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-bold mt-1 block">Tidak Ada Gambar</span>
                            </div>
                        @endif

                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-white/90 backdrop-blur-sm text-emerald-700 border border-emerald-200 shadow-sm">
                            Stok: {{ $alat->stok }}
                        </span>
                    </div>

                    <!-- Info Alat -->
                    <div class="p-5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h3 class="font-bold text-slate-800 text-sm mt-2 line-clamp-1">{{ $alat->nama_alat }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">{{ $alat->deskripsi ?? 'Tidak ada deskripsi alat.' }}</p>
                    </div>
                </div>

                <!-- Tombol Aksi: Detail & Keranjang -->
                <div class="p-5 pt-0 flex gap-2">
                    <button onclick="bukaModalDetail({{ json_encode([
                        'id' => $alat->id,
                        'nama' => $alat->nama_alat,
                        'kategori' => $alat->kategori->nama_kategori ?? 'Umum',
                        'stok' => $alat->stok,
                        'deskripsi' => $alat->deskripsi ?? 'Tidak ada deskripsi rinci untuk alat ini.',
                        'gambar' => $hasImage ? $imgPath : null
                    ]) }})" 
                    class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Detail
                    </button>

                    <button onclick="tambahKeKeranjang({{ $alat->id }}, '{{ addslashes($alat->nama_alat) }}', {{ $alat->stok }}, '{{$alat->kategori->nama_kategori ?? 'Umum' }}')" 
                            class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 active:scale-95 shadow-sm shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Pinjam
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

    <!-- FLOATING BAR KERANJANG PEMINJAMAN -->
    <div id="floatingCart" class="fixed bottom-6 right-6 left-6 sm:left-auto sm:w-96 z-40 hidden animate-bounce-short">
        <div class="bg-slate-900 text-white p-4 rounded-2xl shadow-2xl border border-slate-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative bg-indigo-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path></svg>
                    <span id="cartBadgeCount" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[10px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-slate-900">0</span>
                </div>
                <div>
                    <h4 class="font-bold text-xs"><span id="cartTotalItems">0</span> Alat Ditempatkan</h4>
                    <p class="text-[10px] text-slate-400">Siap untuk diajukan sekaligus</p>
                </div>
            </div>
            <button onclick="bukaModalKeranjang()" class="bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-md">
                Keranjang &rarr;
            </button>
        </div>
    </div>

    <!-- MODAL 1: DETAIL ALAT -->
    <div id="modalDetail" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all">
            <div id="detailGambarContainer" class="h-56 bg-slate-100 relative flex items-center justify-center overflow-hidden">
                <img id="detailGambar" src="" alt="" class="w-full h-full object-cover">
                <div id="detailGambarPlaceholder" class="hidden text-slate-300 text-center">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-xs font-bold mt-1 block">Tidak Ada Gambar</span>
                </div>
                <button onclick="tutupModalDetail()" class="absolute top-3 right-3 bg-slate-900/60 hover:bg-slate-900 text-white rounded-full p-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span id="detailKategori" class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100"></span>
                    <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-md">
                        Stok: <span id="detailStok"></span> unit
                    </span>
                </div>

                <h3 id="detailNama" class="text-lg font-bold text-slate-800"></h3>
                <p id="detailDeskripsi" class="text-xs text-slate-500 mt-2 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100 max-h-32 overflow-y-auto"></p>

                <div class="mt-6 flex gap-3">
                    <button onclick="tutupModalDetail()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition">Tutup</button>
                    <button id="btnDetailTambah" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md shadow-indigo-500/20">
                        + Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: KERANJANG PEMINJAMAN MULTI-ITEM -->
    <div id="modalKeranjang" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-800">Keranjang Peminjaman</h3>
                        <p class="text-[11px] text-slate-400">Atur jumlah barang & tentukan tanggal kembali</p>
                    </div>
                </div>
                <button onclick="tutupModalKeranjang()" class="text-slate-400 hover:text-slate-600 text-lg p-1">✕</button>
            </div>

            <form id="formPinjamMulti" action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                
                <div id="containerKeranjangItems" class="space-y-3 max-h-60 overflow-y-auto pr-1">
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Rencana Tanggal Pengembalian:</label>
                    <input type="date" name="tgl_kembali_plan" min="{{ date('Y-m-d') }}" required 
                           class="w-full text-xs font-bold border border-slate-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-500/50 outline-none">
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="tutupModalKeranjang()" class="w-1/3 bg-slate-100 text-slate-600 py-2.5 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold transition shadow-md shadow-indigo-500/20 active:scale-95">
                        Kirim Pengajuan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC KERANJANG & MODALS -->
    <script>
        let keranjang = [];

        function tambahKeKeranjang(id, nama, maxStok, kategori) {
            let existing = keranjang.find(item => item.id === id);

            if (existing) {
                if (existing.jumlah < maxStok) {
                    existing.jumlah++;
                } else {
                    alert(`Jumlah peminjaman '${nama}' sudah mencapai batas sisa stok (${maxStok}).`);
                    return;
                }
            } else {
                keranjang.push({
                    id: id,
                    nama: nama,
                    maxStok: maxStok,
                    kategori: kategori,
                    jumlah: 1
                });
            }

            updateUIKeranjang();
        }

        function updateUIKeranjang() {
            const floatingCart = document.getElementById('floatingCart');
            const cartTotalItems = document.getElementById('cartTotalItems');
            const cartBadgeCount = document.getElementById('cartBadgeCount');

            let totalCount = keranjang.reduce((acc, item) => acc + item.jumlah, 0);

            if (keranjang.length > 0) {
                floatingCart.classList.remove('hidden');
                cartTotalItems.innerText = keranjang.length;
                cartBadgeCount.innerText = totalCount;
            } else {
                floatingCart.classList.add('hidden');
            }
        }

        function bukaModalDetail(alat) {
            document.getElementById('detailNama').innerText = alat.nama;
            document.getElementById('detailKategori').innerText = alat.kategori;
            document.getElementById('detailStok').innerText = alat.stok;
            document.getElementById('detailDeskripsi').innerText = alat.deskripsi;

            const imgEl = document.getElementById('detailGambar');
            const placeholderEl = document.getElementById('detailGambarPlaceholder');

            if (alat.gambar) {
                imgEl.src = alat.gambar;
                imgEl.classList.remove('hidden');
                placeholderEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                placeholderEl.classList.remove('hidden');
            }

            const btnTambah = document.getElementById('btnDetailTambah');
            btnTambah.onclick = function() {
                tambahKeKeranjang(alat.id, alat.nama, alat.stok, alat.kategori);
                tutupModalDetail();
            };

            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function tutupModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        function bukaModalKeranjang() {
            renderKeranjangItems();
            document.getElementById('modalKeranjang').classList.remove('hidden');
        }

        function tutupModalKeranjang() {
            document.getElementById('modalKeranjang').classList.add('hidden');
        }

        function renderKeranjangItems() {
            const container = document.getElementById('containerKeranjangItems');
            container.innerHTML = '';

            if (keranjang.length === 0) {
                container.innerHTML = `<div class="text-center py-6 text-slate-400 text-xs">Keranjang masih kosong.</div>`;
                return;
            }

            keranjang.forEach((item, index) => {
                const itemEl = document.createElement('div');
                itemEl.className = 'flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200/80';
                itemEl.innerHTML = `
                    <div class="flex-1 pr-2">
                        <h5 class="font-bold text-xs text-slate-800 line-clamp-1">${item.nama}</h5>
                        <span class="text-[10px] text-slate-400">Stok maks: ${item.maxStok}</span>
                        <input type="hidden" name="items[${index}][alat_id]" value="${item.id}">
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center border border-slate-300 bg-white rounded-xl overflow-hidden">
                            <button type="button" onclick="ubahJumlahItem(${index}, -1)" class="px-2.5 py-1 text-slate-600 font-bold hover:bg-slate-100">-</button>
                            <input type="number" name="items[${index}][jumlah]" value="${item.jumlah}" min="1" max="${item.maxStok}" readonly
                                   class="w-10 text-center text-xs font-bold outline-none bg-transparent">
                            <button type="button" onclick="ubahJumlahItem(${index}, 1)" class="px-2.5 py-1 text-slate-600 font-bold hover:bg-slate-100">+</button>
                        </div>

                        <button type="button" onclick="hapusItemKeranjang(${index})" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                `;
                container.appendChild(itemEl);
            });
        }

        function ubahJumlahItem(index, change) {
            let item = keranjang[index];
            let newJumlah = item.jumlah + change;

            if (newJumlah >= 1 && newJumlah <= item.maxStok) {
                item.jumlah = newJumlah;
                renderKeranjangItems();
                updateUIKeranjang();
            } else if (newJumlah > item.maxStok) {
                alert(`Maksimal peminjaman adalah ${item.maxStok} unit.`);
            }
        }

        function hapusItemKeranjang(index) {
            keranjang.splice(index, 1);
            renderKeranjangItems();
            updateUIKeranjang();

            if (keranjang.length === 0) {
                tutupModalKeranjang();
            }
        }
    </script>
@endsection