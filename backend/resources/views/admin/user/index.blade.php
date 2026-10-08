@extends('layouts.app')

@section('title', 'Kelola User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

    <!-- Card Utama (Light Minimalist Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card & Action Bar -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Judul Tabel -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Daftar Pengguna Sistem</h3>
                    <p class="text-xs text-slate-400 font-medium">Kelola akun administrator, petugas, dan peminjam</p>
                </div>
            </div>
            
            <!-- Aksi Kanan (Search & Tambah) -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Form Pencarian -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna..." 
                           class="w-full sm:w-64 px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all">
                    
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-sm active:scale-95 whitespace-nowrap">
                        Cari
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs font-semibold transition flex items-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah User -->
                <a href="{{ route('admin.users.create') }}" class="group flex items-center justify-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white text-xs font-semibold py-2 px-4 rounded-xl transition-all duration-200 active:scale-95 shadow-sm w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Pengguna</span>
                </a>
            </div>

        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80">
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Role</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. HP</th>
                        <th class="py-3.5 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 text-xs divide-y divide-slate-100">
                    
                    @forelse($users as $user)
                        <tr class="hover:bg-indigo-50/40 transition-colors duration-150 group">
                            
                            <!-- Kolom Nama & Avatar -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 group-hover:border-indigo-200 transition-colors">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $user->name }}</span>
                                </div>
                            </td>

                            <!-- Kolom Email -->
                            <td class="py-3.5 px-6 text-slate-500 font-medium">
                                {{ $user->email }}
                            </td>

                            <!-- Kolom Role -->
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border
                                    @if($user->role == 'admin') bg-purple-50 text-purple-700 border-purple-200
                                    @elseif($user->role == 'petugas') bg-blue-50 text-blue-700 border-blue-200
                                    @else bg-emerald-50 text-emerald-700 border-emerald-200
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <!-- Kolom No HP -->
                            <td class="py-3.5 px-6 text-slate-500 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span>{{ $user->no_hp ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="py-3.5 px-6 align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 rounded-lg text-xs font-bold transition-all active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 rounded-lg text-xs font-bold transition-all active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Data Kosong -->
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <p class="text-xs font-semibold">Belum ada data pengguna yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-4 bg-slate-50/50 border-t border-slate-200/80">
            {{ $users->links() }}
        </div>

    </div>

@endsection