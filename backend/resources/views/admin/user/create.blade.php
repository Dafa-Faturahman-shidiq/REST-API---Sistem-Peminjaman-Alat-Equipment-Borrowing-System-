@extends('layouts.app')

@section('title', 'Tambah User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

    <!-- Card Container Form (Light Minimalist Style) -->
    <div class="max-w-xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Card -->
        <div class="p-5 border-b border-slate-200/80 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Form Tambah Pengguna Baru</h3>
                <p class="text-xs text-slate-400 font-medium">Buat akun baru untuk peminjam, petugas, atau administrator</p>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Input Nama Lengkap -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                       placeholder="Masukkan nama lengkap...">
                @error('name') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Email -->
            <div class="space-y-1.5">
                <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                       placeholder="nama@example.com">
                @error('email') 
                    <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Grid Kolom 2 untuk Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Input Password -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                           placeholder="••••••••">
                    @error('password') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Konfirmasi Password -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                           placeholder="••••••••">
                    @error('password_confirmation') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Grid Kolom 2 untuk Role dan No. HP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Select Role / Hak Akses -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">Role / Hak Akses</label>
                    <select name="role" required 
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all cursor-pointer">
                        <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input No. HP -->
                <div class="space-y-1.5">
                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider">
                        No. HP <span class="text-slate-400 font-normal uppercase">(Opsional)</span>
                    </label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all"
                           placeholder="08xxxxxxxxxx">
                    @error('no_hp') 
                        <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200/80">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="flex items-center gap-1.5 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Data</span>
                </button>
            </div>
        </form>

    </div>

@endsection