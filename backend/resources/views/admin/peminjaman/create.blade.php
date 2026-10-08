@extends('layouts.app')

@section('title', 'Tambah Peminjaman - Panel Admin')
@section('header-title', 'Form Tambah Transaksi Peminjaman')

@section('content')

    <!-- Card Container Form (Light Minimalist Style) -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Buat Pengajuan Peminjaman Baru</h3>
                <p class="text-xs text-slate-400 font-medium">Input data peminjam, estimasi tanggal, dan item alat yang dipinjam</p>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Pilih User -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Pilih Peminjam (User)</label>
                <select name="user_id" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Grid Kolom 2 untuk Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Tgl Pinjam -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                </div>

                <!-- Rencana Kembali -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Rencana Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali_plan" value="{{ old('tgl_kembali_plan', date('Y-m-d', strtotime('+3 days'))) }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                </div>
            </div>

            <hr class="border-slate-200/80 my-2">

            <!-- Bagian Daftar Alat yang Dipinjam (Dinamis) -->
            <div class="space-y-3">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Daftar Alat yang Dipinjam</label>
                
                <!-- Container Baris Alat -->
                <div id="alat-container" class="space-y-2.5">
                    <div class="alat-row flex items-center gap-2.5">
                        <!-- Select Alat -->
                        <div class="flex-1">
                            <select name="alat_id[]" required 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}">{{ $alat->nama_alat }} (Stok: {{ $alat->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Input Jumlah -->
                        <div class="w-24">
                            <input type="number" name="jumlah[]" value="1" min="1" placeholder="Jml" required
                                   class="w-full px-3 py-2.5 text-center bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                        </div>
                        
                        <!-- Tombol Hapus Baris -->
                        <button type="button" onclick="removeRow(this)" 
                                class="p-2.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 rounded-xl transition-all active:scale-95" title="Hapus Baris">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah Baris Alat Baru -->
                <button type="button" onclick="addRow()" 
                        class="mt-2 w-full flex items-center justify-center gap-2 py-2.5 border border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/40 hover:bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-xl transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Alat Lain</span>
                </button>
            </div>

            <!-- Tombol Aksi Simpan / Batal -->
            <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200/80">
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="flex items-center gap-1.5 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Peminjaman</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Script Tambah/Hapus Baris Alat Dinamis -->
    <script>
        function addRow() {
            const container = document.getElementById('alat-container');
            const firstRow = container.querySelector('.alat-row');
            
            // Clone baris pertama
            const newRow = firstRow.cloneNode(true);
            
            // Reset nilai elemen pada baris baru
            newRow.querySelector('select').value = '';
            newRow.querySelector('input').value = '1';
            
            // Tambahkan baris baru ke container
            container.appendChild(newRow);
        }

        function removeRow(button) {
            const rows = document.querySelectorAll('.alat-row');
            
            // Pastikan minimal ada 1 baris alat yang tersisa
            if (rows.length > 1) {
                button.closest('.alat-row').remove();
            } else {
                alert('Minimal harus ada 1 alat yang dipilih.');
            }
        }
    </script>

@endsection