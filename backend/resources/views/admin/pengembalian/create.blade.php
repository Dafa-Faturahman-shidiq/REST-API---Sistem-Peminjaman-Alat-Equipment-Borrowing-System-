@extends('layouts.app')

@section('title', 'Proses Pengembalian - Panel Admin')
@section('header-title', 'Form Pengembalian Alat')

@section('content')

    <!-- Card Container Form (Light Minimalist Style) -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Proses Pengembalian Alat</h3>
                <p class="text-xs text-slate-400 font-medium">Peminjam: <span class="font-bold text-slate-700">{{ $peminjaman->peminjam->name ?? 'User' }}</span></p>
            </div>
        </div>

        <!-- Detail Ringkasan Barang yang Dipinjam -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/30">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">Daftar Alat yang Dikembalikan:</h4>
            <ul class="space-y-2 bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm">
                @foreach($peminjaman->detailPinjam as $detail)
                    <li class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800">• {{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                        <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-md text-[11px] font-bold">
                            {{ $detail->jumlah }} pcs
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Form Input Pengembalian -->
        <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Grid Kolom 2 untuk Tanggal dan Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Tanggal Pengembalian Aktual -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Tanggal Kembali Aktual</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" value="{{ old('tgl_kembali', date('Y-m-d')) }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                    @error('tgl_kembali') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Kondisi Saat Kembali -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Kondisi Barang Kembali</label>
                    <select name="kondisi_kembali" id="kondisi_kembali" required 
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                        <option value="baik">Baik / Lengkap</option>
                        <option value="rusak_ringan">Rusak Ringan (Denda: Rp 20.000)</option>
                        <option value="rusak_berat">Rusak Berat (Denda: Rp 50.000)</option>
                        <option value="hilang">Hilang (Denda: Rp 100.000)</option>
                    </select>
                    @error('kondisi_kembali') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Catatan / Deskripsi Kondisi -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Catatan / Deskripsi Kerusakan <span class="text-slate-400 font-normal uppercase">(Opsional)</span>
                </label>
                <textarea name="deskripsi" rows="2"
                          class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                          placeholder="Jelaskan detail kondisi barang jika ada kerusakan atau kehilangan..."></textarea>
                @error('deskripsi') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Estimasi Denda (Otomatis & Readonly) -->
            <div class="space-y-3 p-4 bg-slate-50 border border-slate-200/80 rounded-xl">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Total Denda <span class="text-slate-400 font-normal uppercase">(Terhitung Otomatis)</span>
                </label>
                
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 font-bold text-xs">Rp</span>
                    <input type="text" name="denda" id="denda_input" value="0" readonly
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-bold text-xs outline-none cursor-not-allowed">
                </div>

                <!-- Kotak Rincian Denda -->
                <div id="rincian_denda_box" class="hidden mt-3 space-y-2 pt-3 border-t border-slate-200/80">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Rincian Denda:</p>
                    
                    <!-- Rincian Keterlambatan -->
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-medium">Keterlambatan (<span id="hari_telat" class="font-bold text-slate-700">0</span> hari x Rp 2.000)</span>
                        <span class="font-bold text-slate-800" id="nominal_telat">Rp 0</span>
                    </div>

                    <!-- Rincian Kondisi -->
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-medium">Kondisi Barang (<span id="label_kondisi" class="italic font-bold text-slate-700">Baik</span>)</span>
                        <span class="font-bold text-slate-800" id="nominal_kondisi">Rp 0</span>
                    </div>

                    <!-- Total Akhir -->
                    <div class="flex justify-between items-center text-xs font-bold pt-2 mt-2 border-t border-slate-200 border-dashed">
                        <span class="text-slate-800">Total Denda Harus Dibayar</span>
                        <span class="text-red-600 text-sm font-extrabold" id="nominal_total">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200/80">
                <a href="{{ route('admin.pengembalian.index') }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Selesaikan Pengembalian</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Script JavaScript untuk Perhitungan Denda Real-Time -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tglRencanaStr = "{{ $peminjaman->tgl_kembali_plan }}";
            
            const tglRencana = new Date(tglRencanaStr);
            tglRencana.setHours(0, 0, 0, 0);

            const inputTglKembali = document.getElementById('tgl_kembali');
            const selectKondisi = document.getElementById('kondisi_kembali');
            const inputDenda = document.getElementById('denda_input');
            
            const rincianBox = document.getElementById('rincian_denda_box');
            const elHariTelat = document.getElementById('hari_telat');
            const elNominalTelat = document.getElementById('nominal_telat');
            const elLabelKondisi = document.getElementById('label_kondisi');
            const elNominalKondisi = document.getElementById('nominal_kondisi');
            const elNominalTotal = document.getElementById('nominal_total');

            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(angka);
            };

            function hitungTotalDenda() {
                let dendaTelat = 0;
                let dendaKondisi = 0;
                let diffDays = 0;

                const tglAktual = new Date(inputTglKembali.value);
                tglAktual.setHours(0, 0, 0, 0);

                if (tglAktual > tglRencana) {
                    const diffTime = Math.abs(tglAktual - tglRencana);
                    diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    dendaTelat = diffDays * 2000;
                }

                const kondisi = selectKondisi.value;
                let labelKondisiText = "Baik / Lengkap";

                if (kondisi === 'rusak_ringan') {
                    dendaKondisi = 20000;
                    labelKondisiText = "Rusak Ringan";
                } else if (kondisi === 'rusak_berat') {
                    dendaKondisi = 50000;
                    labelKondisiText = "Rusak Berat";
                } else if (kondisi === 'hilang') {
                    dendaKondisi = 100000;
                    labelKondisiText = "Hilang";
                }

                const totalDenda = dendaTelat + dendaKondisi;

                inputDenda.value = totalDenda;
                
                elHariTelat.textContent = diffDays;
                elNominalTelat.textContent = formatRupiah(dendaTelat);
                elLabelKondisi.textContent = labelKondisiText;
                elNominalKondisi.textContent = formatRupiah(dendaKondisi);
                elNominalTotal.textContent = formatRupiah(totalDenda);

                if (totalDenda > 0) {
                    rincianBox.classList.remove('hidden');
                } else {
                    rincianBox.classList.add('hidden');
                }
            }

            inputTglKembali.addEventListener('change', hitungTotalDenda);
            selectKondisi.addEventListener('change', hitungTotalDenda);
            
            hitungTotalDenda();
        });
    </script>

@endsection