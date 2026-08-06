<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Peminjaman Alat</title>
    
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    <!-- Navbar Tailwind -->
    <nav class="bg-gray-900 text-white shadow-md mb-6">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <span class="font-bold text-lg">UI Peminjaman Alat</span>
            @if(session()->has('api_token'))
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition">Logout</button>
                </form>
            @endif
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container mx-auto px-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>