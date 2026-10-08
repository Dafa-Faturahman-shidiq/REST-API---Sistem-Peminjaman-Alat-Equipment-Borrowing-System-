@extends('layouts.app')

@section('title', 'Dashboard Admin - Simpel')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')

    <!-- ALERT SELAMAT DATANG (Light Minimalist Style) -->
    <div class="mb-6 bg-emerald-50/60 border border-emerald-200/80 p-5 rounded-2xl shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-xl flex items-center justify-center text-emerald-700 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-slate-900 text-base font-bold tracking-tight">
                    Selamat datang, <span class="text-emerald-700">{{ auth()->user()?->name ?? 'Pengguna' }}</span>!
                </h2>
                <p class="text-slate-500 text-xs font-medium mt-0.5 flex items-center gap-2">
                    Anda login sebagai hak akses: 
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300/60 uppercase tracking-wider">
                        {{ auth()->user()?->role ?? 'N/A' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- GRID STATISTIK RINGKASAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Card 1: Total Semua Alat -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-indigo-200 hover:bg-indigo-50/20 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Macam Alat</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $total_alat ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 2: Alat Tersedia -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-emerald-200 hover:bg-emerald-50/20 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stok Tersedia</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stok_tersedia ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 3: Alat Sedang Dipinjam -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-purple-200 hover:bg-purple-50/20 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 border border-purple-100 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $sedang_dipinjam ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 4: Menunggu Persetujuan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-amber-200 hover:bg-amber-50/20 transition-all duration-200 relative">
            @if(($menunggu_persetujuan ?? 0) > 0)
                <span class="absolute top-4 right-4 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
            @endif

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 border border-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Approval</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $menunggu_persetujuan ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL LOG AKTIVITAS (Light Minimalist Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Log Aktivitas Terbaru</h3>
                <p class="text-xs text-slate-400 font-medium">Riwayat riil aksi pengguna dan sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-1/4">Waktu</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-1/3">User</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100">
                    @forelse($logs ?? [] as $log)
                        <tr class="hover:bg-indigo-50/40 transition-colors duration-150 group">
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-slate-500 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </td>
                            
                            <td class="py-3.5 px-6 font-semibold text-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 group-hover:border-indigo-200 transition-colors">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span>{{ $log->user->name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            
                            <td class="py-3.5 px-6 text-slate-600 font-medium">
                                {{ $log->aktivitas }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="text-xs font-semibold">Belum ada log aktivitas yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection