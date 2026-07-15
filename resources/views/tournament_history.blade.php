<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cubiq Padel - History Match</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        /* Pengaman global anti kebocoran geser kanan pada mobile */
        html, body { overflow-x: hidden; position: relative; width: 100%; box-border: border-box; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen py-6 px-4 md:py-10 pb-24 lg:pb-12">

	<div class="max-w-3xl mx-auto mb-4 w-full box-border">
		@if(session('error'))
			<div class="bg-rose-50 border border-rose-200 text-rose-700 p-3.5 rounded-xl text-xs lg:text-sm flex items-center gap-2">
				<i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
			</div>
		@endif
		@if(session('success'))
			<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-3.5 rounded-xl text-xs lg:text-sm flex items-center gap-2">
				<i class="fa-solid fa-circle-check"></i> {{ session('success') }}
			</div>
		@endif
	</div>
	
    <div class="max-w-3xl mx-auto space-y-6 w-full box-border">
        
        <div class="flex items-center justify-between border-b border-slate-200 pb-4 w-full gap-2">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                
                <a href="/" 
                   class="hidden lg:flex w-11 h-11 border border-slate-200 rounded-xl items-center justify-center text-slate-500 hover:text-emerald-600 hover:bg-white hover:border-emerald-200 shadow-sm transition-all flex-shrink-0"
                   title="Kembali ke Utama">
                    <i class="fa-solid fa-house text-base"></i>
                </a>

                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fa-solid fa-history text-lg lg:text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg lg:text-2xl font-bold text-slate-900 truncate">Riwayat Room</h1>
                    <p class="text-[11px] lg:text-sm text-slate-500 truncate">Daftar match yang telah dibuat</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('tournament.create-room') }}" class="bg-emerald-50 border-2 border-emerald-500 text-emerald-700 hover:bg-emerald-100 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-plus text-emerald-600"></i> Room Baru
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" 
                            class="w-10 h-10 sm:w-11 sm:h-11 border border-rose-200 bg-white rounded-xl flex items-center justify-center text-rose-500 hover:text-white hover:bg-rose-500 shadow-sm transition-all"
                            title="Keluar / Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full relative shadow-sm rounded-xl box-border">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <input type="text" id="search-room" placeholder="Cari nama match atau kode room..." 
                   class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all box-border" autocomplete="off">
        </div>

        <div id="room-list-container" class="space-y-3 w-full max-h-[550px] overflow-y-auto pr-1 box-border">
            @forelse($tournaments as $t)
            <div class="tournament-item bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center hover:border-emerald-300 hover:shadow-md transition-all gap-4 w-full box-border"
                 data-name="{{ strtolower($t->name) }}" data-code="{{ strtolower($t->code) }}">
                
                <div class="space-y-1.5 min-w-0 flex-1">
                    <h2 class="font-bold text-sm sm:text-base text-slate-800 truncate search-target-name">
                        {{ $t->name }}
                    </h2>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] sm:text-[11px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded uppercase tracking-wider search-target-code flex-shrink-0">
                            {{ $t->code }}
                        </span>
                        <span class="text-[11px] text-slate-400 flex items-center gap-1 flex-shrink-0">
                            <i class="fa-regular fa-calendar text-[11px]"></i>
                            {{ date('d M Y, H:i', strtotime($t->created_at)) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
					<form action="{{ route('tournament.destroy', ['code' => $t->code]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus match &quot;{{ $t->name }}&quot;? Semua data skor dan pemain akan hilang permanen.');" class="block m-0">
						@csrf
						@method('DELETE')
						<button type="submit" class="bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center rounded-xl transition-all shadow-sm" title="Hapus Match">
							<i class="fa-regular fa-trash-can text-sm sm:text-base"></i>
						</button>
					</form>

					<a href="{{ route('tournament.index', ['code' => $t->code, 'view' => 'leaderboard']) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white h-10 sm:h-11 px-4 sm:px-5 rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 uppercase tracking-wider">
						<span>Buka</span>
						<i class="fa-solid fa-folder-open text-[11px] sm:text-xs"></i>
					</a>
				</div>
            </div>
            @empty
            <div id="empty-state" class="bg-white p-12 rounded-2xl shadow-sm border border-slate-200 text-center space-y-3 w-full box-border">
                <p class="text-sm lg:text-base text-slate-400">Belum ada match yang pernah dibuat.</p>
            </div>
            @endforelse

            <div id="search-empty-state" class="hidden bg-white p-12 rounded-2xl shadow-sm border border-slate-200 text-center space-y-2 w-full box-border">
                <p class="text-sm lg:text-base text-slate-400">Match yang Anda cari tidak ditemukan.</p>
            </div>
        </div>

    </div>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg px-4 py-2 z-50 lg:hidden w-full box-border">
        <div class="max-w-md mx-auto flex justify-around">
            <a href="{{ route('tournament.create-room') }}" class="flex flex-col items-center gap-1 p-2 text-slate-400 hover:text-emerald-500">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px]">Home</span>
            </a>
            <a href="{{ route('tournament.create-room') }}" class="flex flex-col items-center gap-1 p-2 text-slate-400 hover:text-emerald-500">
                <i class="fa-solid fa-square-plus text-lg"></i>
                <span class="text-[10px]">Buat Room</span>
            </a>
            <a href="{{ route('tournament.history') }}" class="flex flex-col items-center gap-1 p-2 text-emerald-500 font-medium">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="text-[10px]">Riwayat</span>
            </a>
        </div>
    </nav>

    <script>
        document.getElementById('search-room').addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase().trim();
            const items = document.querySelectorAll('.tournament-item');
            const searchEmptyState = document.getElementById('search-empty-state');
            let hasResults = false;

            items.forEach(function(item) {
                const name = item.getAttribute('data-name');
                const code = item.getAttribute('data-code');

                if (name.includes(keyword) || code.includes(keyword)) {
                    item.style.setProperty('display', 'flex', 'important');
                    hasResults = true;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            if (items.length > 0) {
                if (!hasResults) {
                    searchEmptyState.classList.remove('hidden');
                } else {
                    searchEmptyState.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>