<!-- FLASH ALERT NOTIFICATION -->
@if(session('success'))
    <div class="mb-5 p-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between shadow-sm transition-all" id="alert-success">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        <button onclick="document.getElementById('alert-success').remove()" class="text-emerald-500 hover:text-emerald-800 font-bold ml-4">✕</button>
    </div>
@endif

@if(session('error'))
    <div class="mb-5 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-2xl flex items-center justify-between shadow-sm transition-all" id="alert-error">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
        <button onclick="document.getElementById('alert-error').remove()" class="text-red-500 hover:text-red-800 font-bold ml-4">✕</button>
    </div>
@endif

@if($errors->any())
    <div class="mb-5 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-2xl shadow-sm" id="alert-validation">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2 font-bold text-red-900">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Terjadi kesalahan validasi input:</span>
            </div>
            <button onclick="document.getElementById('alert-validation').remove()" class="text-red-500 hover:text-red-800 font-bold">✕</button>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 text-red-700 pl-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif