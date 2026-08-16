<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman - Panel Petugas</title>
    <!-- Memuat Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS untuk Animasi -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        /* Custom Scrollbar Emerald Theme */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #6ee7b7; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #34d399; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 selection:bg-emerald-500 selection:text-white">

    <!-- NAVBAR ATAS (Emerald Glassmorphism Theme) -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-emerald-100 h-16 flex items-center justify-between px-4 sm:px-8 transition-all duration-300">
        <!-- Brand Logo Petugas -->
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-emerald-500 to-teal-500 p-1.5 rounded-lg shadow-md shadow-emerald-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <a href="#" class="text-lg font-bold tracking-tight text-slate-800 hover:text-emerald-600 transition-colors">
                Panel Petugas Lab
            </a>
        </div>

        <!-- Menu Kanan Logout -->
        <div class="flex items-center gap-4">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="group flex items-center gap-2 px-4 py-2 bg-white border border-red-100 text-red-500 text-sm font-semibold rounded-xl hover:bg-red-50 hover:shadow-sm transition-all duration-300 active:scale-95">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
        
        <!-- Header Halaman -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pengajuan & Transaksi Peminjaman</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola persetujuan dan pengembalian alat laboratorium dengan teliti.</p>
        </div>

        <!-- Alert Success Session -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Alert Error Session -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel Transaksi Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-emerald-200/20 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Peminjam</th>
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Tgl Pinjam</th>
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Rencana Kembali</th>
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Status</th>
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50">Alat yang Dipinjam</th>
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-widest bg-slate-50/50 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 text-sm divide-y divide-slate-50">
                        
                        @forelse($peminjamans as $item)
                            <tr class="hover:bg-emerald-50/40 transition-colors duration-200">
                                
                                <!-- Kolom Peminjam -->
                                <td class="py-4 px-6 font-semibold text-slate-700 align-top">
                                    {{ $item->user->name ?? '-' }}
                                </td>

                                <!-- Kolom Tgl Pinjam -->
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $item->tgl_pinjam }}
                                    </div>
                                </td>

                                <!-- Kolom Tgl Kembali Plan -->
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $item->tgl_kembali_plan }}
                                    </div>
                                </td>

                                <!-- Kolom Status (Badge) -->
                                <td class="py-4 px-6 align-top">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border shadow-sm
                                        @if($item->status == 'diajukan') bg-amber-50 text-amber-700 border-amber-200
                                        @elseif($item->status == 'dipinjam') bg-blue-50 text-blue-700 border-blue-200
                                        @elseif($item->status == 'selesai') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @else bg-red-50 text-red-700 border-red-200
                                        @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <!-- Kolom Daftar Alat -->
                                <td class="py-4 px-6 align-top">
                                    <ul class="space-y-1 list-inside list-disc text-slate-600 marker:text-emerald-400">
                                        @foreach($item->detailPinjams as $detail)
                                            <li>
                                                <span class="font-medium text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat' }}</span> 
                                                <span class="text-xs text-slate-400">({{ $detail->jumlah }} pcs)</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Kolom Aksi Dinamis -->
                                <td class="py-4 px-6 text-center align-top">
                                    @if($item->status == 'diajukan')
                                        <!-- Tombol Setujui (Emerald) -->
                                        <form action="{{ route('petugas.peminjaman.setujui', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="group flex items-center justify-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-xs font-bold rounded-lg shadow-md shadow-emerald-500/30 transition-all duration-200 active:scale-95 whitespace-nowrap">
                                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Setujui
                                            </button>
                                        </form>

                                    @elseif($item->status == 'dipinjam')
                                        <!-- Form Proses Pengembalian (Amber/Warning) -->
                                        <form action="{{ route('petugas.pengembalian.proses', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <!-- Hidden Inputs sesuai sistem asli -->
                                            <input type="hidden" name="kondisi_kembali" value="Baik">
                                            <input type="hidden" name="denda" value="0">
                                            
                                            <button type="submit" onclick="return confirm('Proses pengembalian alat ini?')" 
                                                    class="group flex items-center justify-center gap-1.5 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-lg shadow-md shadow-amber-500/30 transition-all duration-200 active:scale-95 whitespace-nowrap">
                                                <svg class="w-4 h-4 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                Terima Kembali
                                            </button>
                                        </form>

                                    @else
                                        <!-- Status Selesai (Muted) -->
                                        <div class="flex items-center justify-center gap-1.5 text-slate-400 bg-slate-50 px-4 py-2 rounded-lg border border-slate-100 text-xs font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Selesai
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-16 h-16 mb-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <h4 class="text-lg font-semibold text-slate-600 mb-1">Tidak Ada Transaksi</h4>
                                        <p class="text-sm">Belum ada data peminjaman yang diajukan atau diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>