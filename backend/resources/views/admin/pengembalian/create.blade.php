@extends('layouts.app')

@section('title', 'Proses Pengembalian - Panel Admin')
@section('header-title', 'Form Pengembalian Alat')

@section('content')

    <!-- Card Container Form Modern -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">

        @if(session('error'))
            <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif
        
        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600 shadow-inner">
                <!-- Ikon Ceklis/Pengembalian -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Proses Pengembalian Alat</h3>
                <p class="text-xs text-slate-500">Peminjam: <span class="font-semibold text-slate-700">{{ $peminjaman->user->name ?? 'User' }}</span></p>
            </div>
        </div>

        <!-- Detail Ringkasan Barang yang Dipinjam -->
        <div class="p-8 pb-4 border-b border-slate-100 bg-slate-50/30">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Daftar Alat yang Dikembalikan:</h4>
            <ul class="space-y-2 bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm">
                @foreach($peminjaman->detailPinjams as $detail)
                    <li class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-slate-700">• {{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold">
                            {{ $detail->jumlah }} pcs
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Form Input Pengembalian -->
        <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Grid Kolom 2 untuk Tanggal dan Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Pengembalian Aktual -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Tanggal Kembali Aktual</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" value="{{ old('tgl_kembali', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                    @error('tgl_kembali') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Kondisi Saat Kembali (ENUM) -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Kondisi Barang Kembali</label>
                    <select name="kondisi_kembali" id="kondisi_kembali" required 
                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                        <option value="baik">Baik / Lengkap</option>
                        <option value="rusak_ringan">Rusak Ringan (Denda: Rp 20.000)</option>
                        <option value="rusak_berat">Rusak Berat (Denda: Rp 50.000)</option>
                        <option value="hilang">Hilang (Denda: Rp 100.000)</option>
                    </select>
                    @error('kondisi_kembali') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Catatan / Deskripsi Kondisi -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Catatan / Deskripsi Kerusakan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea name="deskripsi" rows="2"
                          class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                          placeholder="Jelaskan detail kondisi barang jika ada kerusakan atau kehilangan..."></textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Input Estimasi Denda (Otomatis & Readonly) -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Estimasi Total Denda <span class="text-slate-400 font-normal">(Terhitung Otomatis)</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold text-sm">Rp</span>
                    <input type="text" name="denda" id="denda_input" value="0" readonly
                           class="w-full pl-12 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm outline-none cursor-not-allowed">
                </div>
                <p class="text-xs text-slate-500 mt-1">Denda Keterlambatan: Rp 2.000/hari + Denda Kondisi Barang.</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.pengembalian.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Selesaikan Pengembalian
                </button>
            </div>
        </form>

    </div>

    </div>

<!-- Script JavaScript untuk Perhitungan Denda Real-Time -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tglRencana = new Date("{{ $peminjaman->tgl_kembali_plan }}");
            const inputTglKembali = document.getElementById('tgl_kembali');
            const selectKondisi = document.getElementById('kondisi_kembali');
            const inputDenda = document.getElementById('denda_input');

            function hitungTotalDenda() {
                let dendaTelat = 0;
                let dendaKondisi = 0;

                // 1. Hitung Denda Keterlambatan
                const tglAktual = new Date(inputTglKembali.value);
                if (tglAktual > tglRencana) {
                    const diffTime = Math.abs(tglAktual - tglRencana);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    dendaTelat = diffDays * 2000;
                }

                // 2. Hitung Denda Kondisi Barang
                const kondisi = selectKondisi.value;
                if (kondisi === 'rusak_ringan') dendaKondisi = 20000;
                else if (kondisi === 'rusak_berat') dendaKondisi = 50000;
                else if (kondisi === 'hilang') dendaKondisi = 100000;

                // 3. Tampilkan Akumulasi
                inputDenda.value = dendaTelat + dendaKondisi;
            }

            // Jalankan perhitungan setiap kali tanggal atau kondisi diubah
            inputTglKembali.addEventListener('change', hitungTotalDenda);
            selectKondisi.addEventListener('change', hitungTotalDenda);
            
            // Jalankan sekali saat halaman dimuat
            hitungTotalDenda();
        });
    </script>

@endsection