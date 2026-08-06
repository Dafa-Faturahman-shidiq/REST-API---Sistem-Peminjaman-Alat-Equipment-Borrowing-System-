@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[75vh] px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        
        <!-- Header / Logo Tema BRI -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-900 text-white mb-4 shadow-lg shadow-blue-900/20 font-black text-2xl tracking-wider">
                BRI
            </div>
            <h2 class="text-2xl font-extrabold text-blue-900 tracking-tight">
                PORTAL PEMINJAMAN ALAT
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Silakan masuk menggunakan akun terverifikasi
            </p>
        </div>

        <!-- Notifikasi Error -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm flex items-start space-x-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <span class="font-semibold block">Gagal Masuk</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Form Login -->
        <form class="mt-6 space-y-5" action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                        placeholder="nama@email.com" value="{{ old('email') }}">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition text-sm" 
                        placeholder="••••••••">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-3.5 px-4 text-sm font-bold rounded-xl text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 transition shadow-lg shadow-blue-800/25">
                    Masuk ke Sistem
                </button>
            </div>
        </form>

        <div class="text-center pt-2">
            <p class="text-sm text-gray-600">belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-blue-800 hover:underline">Daftar di sini</a>
            </p>
        </div>

    </div>
</div>
@endsection