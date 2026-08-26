<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Peminjaman Alat</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased selection:bg-purple-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-slate-800 tracking-tight">Pinjam<span class="text-purple-600">Alat.</span></span>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <!-- Jika sudah login -->
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-purple-600 transition-colors">Ke Dashboard</a>
                        @else
                            <!-- Jika belum login -->
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-purple-600 transition-colors hidden sm:block">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-500/30 transition-all duration-200 active:scale-95">Daftar Akun</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-800 tracking-tight mb-8">
                Pinjam Alat Praktik <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-fuchsia-500">Lebih Cepat & Mudah</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg sm:text-xl text-slate-500 mx-auto mb-10">
                Platform manajemen inventaris dan peminjaman alat laboratorium terintegrasi. Pantau ketersediaan, ajukan peminjaman, dan kelola pengembalian dalam satu genggaman.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#katalog" class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-slate-700 bg-white border border-slate-200 hover:border-purple-500 hover:text-purple-600 rounded-xl shadow-sm transition-all duration-200">
                    Lihat Katalog Alat
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-lg shadow-purple-500/30 transition-all duration-200 active:scale-95 flex items-center justify-center gap-2">
                    Mulai Meminjam
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-20 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                
                <!-- Feature 1 -->
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 text-indigo-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Katalog Real-time</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Cek ketersediaan alat beserta spesifikasi dan kondisinya secara langsung tanpa perlu bertanya ke petugas.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Proses Cepat</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Ajukan peminjaman secara digital (paperless). Sistem akan otomatis memberitahu petugas untuk segera disetujui.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mb-6 text-rose-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Sistem Denda Otomatis</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Sistem akan secara otomatis menghitung durasi keterlambatan dan denda kerusakan secara transparan.</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center">
        <p class="text-sm text-slate-500 font-medium">
            &copy; {{ date('Y') }} Sistem Peminjaman Alat. All rights reserved.
        </p>
    </footer>

</body>
</html>