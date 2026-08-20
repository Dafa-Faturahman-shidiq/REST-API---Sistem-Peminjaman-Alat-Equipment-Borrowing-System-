@extends('layouts.app')

@section('title', 'Tambah Peminjaman - Panel Admin')
@section('header-title', 'Form Tambah Transaksi Peminjaman')

@section('content')

    <!-- Card Container Form Modern -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">
        
        <!-- Notifikasi Error Utama -->
        @if(session('error'))
            <div class="m-6 mb-0 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <!-- Ikon Dokumen/Form -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Buat Pengajuan Peminjaman Baru</h3>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Pilih User -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Pilih Peminjam (User)</label>
                <select name="user_id" required 
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Grid Kolom 2 untuk Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tgl Pinjam -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                </div>

                <!-- Rencana Kembali -->
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-semibold">Rencana Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali_plan" value="{{ old('tgl_kembali_plan', date('Y-m-d', strtotime('+3 days'))) }}" required
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Bagian Daftar Alat yang Dipinjam (Dinamis) -->
            <div class="space-y-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Daftar Alat yang Dipinjam</label>
                
                <!-- Container Baris Alat -->
                <div id="alat-container" class="space-y-3">
                    <div class="alat-row flex items-center gap-3">
                        <!-- Select Alat -->
                        <div class="flex-1">
                            <select name="alat_id[]" required 
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}">{{ $alat->nama_alat }} (Stok: {{ $alat->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Input Jumlah -->
                        <div class="w-24">
                            <input type="number" name="jumlah[]" value="1" min="1" placeholder="Jml" required
                                   class="w-full px-3 py-2.5 text-center bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm">
                        </div>
                        
                        <!-- Tombol Hapus Baris -->
                        <button type="button" onclick="removeRow(this)" 
                                class="p-2.5 bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 rounded-xl transition-colors border border-transparent hover:border-red-200" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah Baris Alat Baru -->
                <button type="button" onclick="addRow()" 
                        class="mt-3 w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/50 hover:bg-indigo-50 text-indigo-600 text-sm font-semibold rounded-xl transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Alat Lain
                </button>
            </div>

            <!-- Tombol Aksi Simpan / Batal -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Peminjaman
                </button>
            </div>
        </form>

    </div>

    <!-- Script Sederhana untuk Tambah/Hapus Baris Alat -->
    <script>
        function addRow() {
            const container = document.getElementById('alat-container');
            const firstRow = container.querySelector('.alat-row');
            
            // Clone baris pertama
            const newRow = firstRow.cloneNode(true);
            
            // Reset nilai pada baris baru
            newRow.querySelector('select').value = '';
            newRow.querySelector('input').value = '1';
            
            // Tambahkan baris baru ke dalam container
            container.appendChild(newRow);
        }

        function removeRow(button) {
            const rows = document.querySelectorAll('.alat-row');
            
            // Cek jika jumlah baris lebih dari 1
            if (rows.length > 1) {
                button.closest('.alat-row').remove();
            } else {
                alert('Minimal harus ada 1 alat yang dipilih.');
            }
        }
    </script>

@endsection