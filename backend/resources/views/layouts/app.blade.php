<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <!-- Memuat Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS untuk Animasi Transisi -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        /* Custom Scrollbar untuk tampilan lebih bersih */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 selection:bg-indigo-500 selection:text-white">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <!-- Menggunakan Gradien, Shadow, dan durasi transisi -->
        <aside class="w-72 bg-gradient-to-b from-slate-900 via-indigo-950 to-slate-900 text-white flex flex-col hidden md:flex shadow-2xl transition-all duration-300 z-20">
            
            <!-- Logo / Brand Area -->
            <div class="p-6 flex items-center gap-3 border-b border-white/10">
                <div class="bg-gradient-to-tr from-blue-500 to-indigo-500 p-2.5 rounded-xl shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-xl font-extrabold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-blue-200 to-indigo-100">
                    SIMPEL<span class="font-light text-slate-300 text-sm block -mt-1 tracking-normal">Peminjaman Alat</span>
                </span>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 mt-4">Menu Utama</p>
                
                <!-- Link Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Link Kelola Alat -->
                <a href="{{ route('admin.alat.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.alat.*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="font-medium">Kelola Alat</span>
                </a>

                <!-- Link Kelola Kategori -->
                <a href="{{ route('admin.kategori.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.kategori.*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="font-medium">Kelola Kategori</span>
                </a>

                <!-- Link Kelola User -->
                <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Kelola User</span>
                </a>

                <!-- Link Kelola Peminjaman -->
                <a href="{{ route('admin.peminjaman.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.peminjaman.*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="font-medium">Kelola Peminjaman</span>
                </a>

                <!-- Link Kelola Pengembalian -->
                <a href="{{ route('admin.pengembalian.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('admin.pengembalian.*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm' : '' }}">
                    <svg class="w-5 h-5 transition-colors group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="font-medium">Kelola Pengembalian</span>
                </a>
            </nav>

            <!-- User Profile Card -->
            <div class="p-4 m-4 mt-auto bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md hover:bg-white/10 transition-colors cursor-default">
                <div class="flex items-center gap-3">
                    <!-- Avatar Initial -->
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold shadow-inner">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs text-slate-400">Logged in as</span>
                        <span class="text-sm text-white font-semibold truncate">{{ auth()->user()?->name ?? 'Administrator' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50 relative">

            <!-- NAVBAR ATAS (Glassmorphism Effect) -->
            <header class="sticky top-0 bg-white/80 backdrop-blur-lg border-b border-slate-200 h-20 flex items-center justify-between px-8 z-10 transition-all duration-300">
                <div class="text-2xl font-bold text-slate-800 tracking-tight">
                    @yield('header-title', 'Dashboard')
                </div>
                
                <div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="group flex items-center gap-2 bg-white border border-red-100 hover:bg-red-50 text-red-600 text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all duration-300 hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- KONTEN UTAMA HALAMAN -->
            <!-- Animasi masuk diterapkan di main ini -->
            <main class="flex-1 p-8 animate-fade-in">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>