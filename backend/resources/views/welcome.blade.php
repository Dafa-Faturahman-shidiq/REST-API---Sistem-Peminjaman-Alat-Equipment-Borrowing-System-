<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEL - Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="max-w-6xl w-full mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-extrabold text-lg text-white shadow-lg shadow-indigo-500/30">
                S
            </div>
            <span class="font-extrabold text-xl tracking-tight text-white">SIMPEL</span>
        </div>

        <div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition">Dashboard Admin &rarr;</a>
                @elseif(auth()->user()->role === 'petugas')
                    <a href="{{ route('petugas.peminjaman.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition">Dashboard Petugas &rarr;</a>
                @else
                    <a href="{{ route('peminjam.katalog.index') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition">Buka Katalog &rarr;</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition">Masuk / Login</a>
            @endauth
        </div>
    </header>

    <!-- Hero Content -->
    <main class="max-w-4xl w-full mx-auto px-6 text-center py-16">
        <span class="inline-block px-3.5 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold mb-6">
            Sistem Informasi Peminjaman Alat
        </span>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight text-white">
            Pinjam Peralatan Praktikum Lebih <span class="text-indigo-400">Mudah & Cepat</span>
        </h1>

        <p class="mt-4 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto font-normal">
            Cek ketersediaan stok alat secara realtime, lakukan pengajuan peminjaman online, dan pantau status pengembalian Anda.
        </p>

        <div class="mt-8 flex items-center justify-center gap-4">
            @auth
                <a href="{{ route('peminjam.katalog.index') }}" class="px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition">
                    Lihat Katalog Alat
                </a>
                <a href="{{ route('peminjam.riwayat.index') }}" class="px-8 py-3.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm border border-slate-700 transition">
                    Riwayat Pinjaman
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition">
                    Mulai Pinjam Alat
                </a>
            @endauth
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-500 border-t border-slate-800/80">
        SIMPEL &copy; {{ date('Y') }} - Peminjaman Alat Sekolah
    </footer>

</body>
</html>