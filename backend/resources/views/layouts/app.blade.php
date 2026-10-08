<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Animasi Transisi Halus */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Custom Scrollbar Minimalis */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body class="bg-white font-sans antialiased text-slate-800 selection:bg-indigo-500 selection:text-white">

    <div class="flex h-screen overflow-hidden bg-white">

        <!-- SIDEBAR LIGHT MINIMALIST -->
        <aside class="w-64 bg-white text-slate-700 flex flex-col hidden md:flex border-r border-slate-200 z-20">
            
            <!-- Brand Area -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-200/80">
                <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0 shadow-sm transition-transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-bold tracking-tight text-slate-900 block leading-none">SIMPEL</span>
                    <span class="text-[11px] text-slate-400 font-medium">Peminjaman Alat</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider my-2">Menu Utama</p>
                
                {{-- MENU KHUSUS ADMIN --}}
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-blue-50/70 hover:text-blue-600 hover:border-blue-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.alat.index') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.alat.*') ? 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-emerald-50/70 hover:text-emerald-600 hover:border-emerald-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span>Kelola Alat</span>
                </a>

                <a href="{{ route('admin.kategori.index') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.kategori.*') ? 'bg-violet-50 text-violet-700 border-violet-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-violet-50/70 hover:text-violet-600 hover:border-violet-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Kelola Kategori</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-700 border-purple-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-purple-50/70 hover:text-purple-600 hover:border-purple-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Kelola User</span>
                </a>

                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.peminjaman.*') ? 'bg-amber-50 text-amber-700 border-amber-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-amber-50/70 hover:text-amber-600 hover:border-amber-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Kelola Peminjaman</span>
                </a>

                <a href="{{ route('admin.pengembalian.index') }}" 
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('admin.pengembalian.*') ? 'bg-teal-50 text-teal-700 border-teal-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-teal-50/70 hover:text-teal-600 hover:border-teal-200' }}">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>Kelola Pengembalian</span>
                </a>
                @endif

                {{-- MENU KHUSUS PETUGAS --}}
                @if(auth()->user()->role === 'petugas')
                <a href="{{ route('petugas.peminjaman.index') }}"
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('petugas.peminjaman*') ? 'bg-indigo-50 text-indigo-700 border-indigo-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-indigo-50/70 hover:text-indigo-600 hover:border-indigo-200' }}">
                    <span>Persetujuan Peminjaman</span>
                </a>

                <a href="{{ route('petugas.pengembalian.index') }}"
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('petugas.pengembalian*') ? 'bg-indigo-50 text-indigo-700 border-indigo-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-indigo-50/70 hover:text-indigo-600 hover:border-indigo-200' }}">
                    <span>Pemantauan Pengembalian</span>
                </a>

                <a href="{{ route('petugas.laporan.index') }}"
                   class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold border transition-all duration-200 hover:translate-x-1 {{ request()->routeIs('petugas.laporan*') ? 'bg-indigo-50 text-indigo-700 border-indigo-200 shadow-sm' : 'text-slate-600 border-transparent hover:bg-indigo-50/70 hover:text-indigo-600 hover:border-indigo-200' }}">
                    <span>Cetak Laporan</span>
                </a>
                @endif
            </nav>

            <!-- User Profile Card -->
            <div class="p-3 m-3 mt-auto bg-slate-50 border border-slate-200 rounded-xl transition-all duration-200 hover:border-slate-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 border border-indigo-200 flex items-center justify-center text-xs text-indigo-700 font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[10px] text-slate-400 font-medium leading-tight">Logged in as</span>
                        <span class="text-xs text-slate-800 font-bold truncate">{{ auth()->user()?->name ?? 'Administrator' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 relative">

            <!-- NAVBAR ATAS -->
            <header class="sticky top-0 bg-white border-b border-slate-200/80 h-16 flex items-center justify-between px-8 z-10 backdrop-blur-md">
                <div class="text-lg font-bold text-slate-900 tracking-tight">
                    @yield('header-title', 'Dashboard')
                </div>
                
                <div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 bg-white border border-red-200 hover:bg-red-50 hover:border-red-300 text-red-600 text-xs font-semibold px-3.5 py-2 rounded-xl transition-all duration-200 hover:shadow-sm active:scale-95">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- KONTEN UTAMA HALAMAN -->
            <main class="flex-1 p-8 animate-fade-in">
                @include('partials.alerts')
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>