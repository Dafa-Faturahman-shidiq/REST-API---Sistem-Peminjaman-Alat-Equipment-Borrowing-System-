@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[75vh] px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        
        <!-- Header / Logo Tema BRI -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-900 text-white mb-4 shadow-lg shadow-blue-900/20 font-black text-2xl tracking-wider">
                BRI
            </div>
            <h2 class="text-2xl font-extrabold text-blue-900 tracking-tight">
                DAFTAR AKUN BARU
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Lengkapi data diri untuk pendaftaran sistem
            </p>
        </div>

        <!-- Notifikasi Error -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm flex items-start space-x-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <span class="font-semibold block">Gagal Registrasi</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Form Register -->
        <form class="mt-6 space-y-4" action="{{ route('register.proses') }}" method="POST">
            @csrf
            
            {{-- form nama lengkap --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input id="name" name="name" type="text" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="Nama Lengkap" value="{{ old('name') }}">
            </div>

            {{-- form email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                <input id="email" name="email" type="email" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="nama@email.com" value="{{ old('email') }}">
            </div>

            {{-- form password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input id="password" name="password" type="password" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="••••••••">
            </div>

            {{-- form password confirmation --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Password Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="••••••••">
            </div>

            {{-- form no_hp --}}
            <div>
                <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>
                <input id="no_hp" name="no_hp" type="text" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="081234567890" value="{{ old('no_hp') }}">
            </div>

            {{-- form alamat --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                <input id="alamat" name="alamat" type="text" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                    placeholder="Alamat Lengkap" value="{{ old('alamat') }}">
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-3.5 px-4 text-sm font-bold rounded-xl text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 transition shadow-lg shadow-blue-800/25">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Link ke Login -->
        <div class="text-center pt-2">
            <p class="text-sm text-gray-600">Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-blue-800 hover:underline">Masuk di sini</a>
            </p>
        </div>

    </div>
</div>
@endsection