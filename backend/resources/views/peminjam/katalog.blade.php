<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat - Panel Peminjam</title>
    <!-- Memuat Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS untuk Animasi -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 selection:bg-indigo-500 selection:text-white">

    <!-- NAVBAR ATAS (Modern Glassmorphism) -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-8 transition-all duration-300">
        <!-- Brand Logo -->
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-blue-500 to-indigo-500 p-1.5 rounded-lg shadow-md shadow-indigo-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <a href="#" class="text-lg font-bold tracking-tight text-slate-800 hover:text-indigo-600 transition-colors">
                Panel Peminjam
            </a>
        </div>

        <!-- Menu Kanan -->
        <div class="flex items-center gap-4">
            <a href="{{ route('peminjam.riwayat') }}" class="group flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-all duration-300">
                <svg class="w-4 h-4 transition-transform group-hover:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Riwayat Pinjam</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="group flex items-center gap-2 px-4 py-2 bg-white border border-red-100 text-red-500 text-sm font-semibold rounded-xl hover:bg-red-50 hover:shadow-sm transition-all duration-300 active:scale-95">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
        
        <!-- Header Halaman -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Katalog Alat Tersedia</h1>
            <p class="text-slate-500 text-sm mt-1">Pilih alat yang ingin Anda pinjam dan tentukan tanggal pengembaliannya.</p>
        </div>

        <!-- Alert Success Session -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Alert Error Session -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM PEMINJAMAN -->
        <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST">
            @csrf
            
            <!-- Card Rencana Pengembalian -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6 relative overflow-hidden group">
                <!-- Dekorasi -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10 max-w-md">
                    <label class="flex items-center gap-2 text-slate-700 text-sm font-bold mb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Rencana Tanggal Kembali
                    </label>
                    <input type="date" name="tgl_kembali_plan" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all cursor-pointer">
                </div>
            </div>

            <!-- Card Tabel Daftar Alat -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-200 overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest w-16 text-center">Pilih</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest">Nama Alat</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest">Kategori</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest text-center w-32">Stok</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest text-center w-40">Jml Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 text-sm divide-y divide-slate-100">
                            
                            @forelse($alats ?? [] as $index => $alat)
                                <tr class="hover:bg-indigo-50/30 transition-colors duration-200 group">
                                    <!-- Checkbox -->
                                    <td class="py-3 px-6 text-center align-middle">
                                        <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}" 
                                               class="w-5 h-5 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer accent-indigo-600 transition-all">
                                    </td>
                                    
                                    <!-- Nama Alat -->
                                    <td class="py-3 px-6 font-semibold text-slate-700 align-middle">
                                        {{ $alat->nama_alat }}
                                    </td>
                                    
                                    <!-- Kategori -->
                                    <td class="py-3 px-6 align-middle">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            {{ $alat->kategori->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Stok -->
                                    <td class="py-3 px-6 text-center align-middle">
                                        <span class="font-bold {{ $alat->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ $alat->stok }}
                                        </span>
                                    </td>
                                    
                                    <!-- Jumlah Pinjam Input -->
                                    <td class="py-3 px-6 text-center align-middle">
                                        <input type="number" name="jumlah[]" value="1" min="1" max="{{ $alat->stok }}" 
                                               class="w-20 px-3 py-1.5 text-center bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm">
                                    </td>
                                </tr>
                            @empty
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <h4 class="text-lg font-semibold text-slate-600 mb-1">Katalog Kosong</h4>
                                            <p class="text-sm">Tidak ada alat yang tersedia untuk dipinjam saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Submit Floating / Bawah -->
            <div class="flex justify-end">
                <button type="submit" class="group flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <span>Ajukan Peminjaman</span>
                </button>
            </div>
            
        </form>
    </main>

</body>
</html>