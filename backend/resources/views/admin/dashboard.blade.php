@extends('layouts.app')

@section('title', 'Dashboard Admin - Simpel')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')

    <!-- ALERT SELAMAT DATANG (Modern & Animated) -->
    <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
        <!-- Dekorasi Background -->
        <div class="absolute -right-10 -top-10 opacity-10 transform group-hover:scale-110 transition-transform duration-500">
            <svg class="w-40 h-40 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        </div>

        <div class="relative z-10 flex items-center gap-5">
            <!-- Icon -->
            <div class="bg-gradient-to-br from-emerald-400 to-teal-600 text-white p-3.5 rounded-xl shadow-lg shadow-emerald-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <!-- Teks Welcome -->
            <div>
                <h2 class="text-emerald-900 text-xl tracking-tight">
                    Selamat datang, <strong class="font-extrabold">{{ auth()->user()?->name ?? 'Pengguna' }}</strong>!
                </h2>
                <div class="text-emerald-700/90 text-sm mt-1 flex items-center gap-2">
                    Anda login sebagai hak akses: 
                    <!-- Badge Role -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-200/70 text-emerald-900 uppercase tracking-wider shadow-sm border border-emerald-300/50">
                        {{ auth()->user()?->role ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- GRID STATISTIK RINGKASAN (NEW) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Semua Alat -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Total Macam Alat</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $total_alat ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 2: Alat Tersedia -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-emerald-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Total Stok Tersedia</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stok_tersedia ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 3: Alat Sedang Dipinjam -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-purple-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Sedang Dipinjam</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $sedang_dipinjam ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Card 4: Menunggu Persetujuan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-amber-100/50 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <!-- Indikator Dot Pulse jika ada request pending -->
            @if(($menunggu_persetujuan ?? 0) > 0)
                <span class="absolute top-4 right-4 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
            @endif

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Menunggu Approval</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $menunggu_persetujuan ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL LOG AKTIVITAS (Modern Card Style) -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50">
        
        <!-- Header Tabel -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Log Aktivitas Terbaru</h3>
        </div>

        <!-- Wrapper Tabel untuk Responsif -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50 w-1/4">Waktu</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50 w-1/3">User</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-sm divide-y divide-slate-50">
                    
                    @forelse($logs ?? [] as $log)
                        <tr class="hover:bg-indigo-50/60 transition-colors duration-200 group">
                            <!-- Kolom Waktu -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-slate-500 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium">{{ $log->created_at }}</span>
                                </div>
                            </td>
                            
                            <!-- Kolom User -->
                            <td class="py-4 px-6 font-medium text-slate-700">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar Inisial Bulat -->
                                    <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span>{{ $log->user->name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            
                            <!-- Kolom Aktivitas -->
                            <td class="py-4 px-6 text-slate-600">
                                {{ $log->aktivitas }}
                            </td>
                        </tr>
                    @empty
                        <!-- Data Kosong -->
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="text-sm font-medium">Belum ada log aktivitas yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

@endsection