@extends('layouts.app')

@section('title', 'Tambah User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

    <!-- Card Container Form Modern -->
    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-fade-in">
        
        <!-- Header Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Form Tambah Pengguna Baru</h3>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Input Nama Lengkap -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                       placeholder="Masukkan nama lengkap...">
                @error('name') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Email -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                       placeholder="nama@example.com">
                @error('email') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Password -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                       placeholder="••••••••">
                @error('password') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Select Role / Hak Akses -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">Role / Hak Akses</label>
                <select name="role" required 
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm cursor-pointer">
                    <option value="peminjam">Peminjam</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input No. HP -->
            <div class="space-y-2">
                <label class="block text-slate-700 text-sm font-semibold">No. HP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 text-sm outline-none transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 shadow-sm"
                       placeholder="08xxxxxxxxxx">
                @error('no_hp') 
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 active:scale-95">
                    Simpan Data
                </button>
            </div>
        </form>

    </div>

@endsection