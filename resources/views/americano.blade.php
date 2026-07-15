<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cubiq Padel - Match {{ $tournament->name ?? 'Padel Match' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		/* Mengatur ukuran lebar scrollbar secara global */
		::-webkit-scrollbar { 
			width: 8px; 
		}
		/* Mengatur latar belakang jalur scrollbar agar ada kontras */
		::-webkit-scrollbar-track {
			background: #f1f5f9; /* warna slate-100 halus */
			border-radius: 4px;
		}
		/* Mengatur warna batang scrollbar agar terlihat jelas (Warna Emerald/Hijau) */
	::-webkit-scrollbar-thumb { 
		background: #94a3b8; /* warna slate-400 (abu-abu tegas) */
		border-radius: 4px; 
	}
		/* Efek saat scrollbar diarahkan kursor/hover */
	::-webkit-scrollbar-thumb:hover {
		background: #64748b; /* warna slate-500 */
	}
		
		/* Memastikan tidak ada bagian yang bocor ke kanan di tingkat dokumen */
		html, body { overflow-x: hidden; position: relative; width: 100%; }
	</style>

</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen pb-20 lg:pb-10">
	
	<header class="bg-white border-b border-slate-200 sticky top-0 z-50 px-4 lg:px-8 py-3.5 shadow-sm w-full">
		<div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
			
			<a href="/" 
			   class="w-9 h-9 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-emerald-600 hover:bg-slate-50 hover:border-emerald-200 shadow-sm transition-all flex-shrink-0"
			   title="Kembali ke Utama">
				<i class="fa-solid fa-house text-sm"></i>
			</a>

			<div class="flex flex-col min-w-0 flex-1">
				<h1 class="text-xl font-extrabold tracking-tight text-slate-900 flex items-center gap-1.5 truncate">
					<i class="fa-solid fa-table-tennis-paddle-ball text-emerald-500 text-base flex-shrink-0"></i>
					<span class="truncate">{{ $tournament->name ?? 'Padel Bagus' }}</span>
				</h1>
				<div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-0.5">
					<div class="flex items-center gap-1.5">
						<span class="text-[10px] font-semibold text-slate-400 tracking-wider uppercase">Room Code:</span>
						<span class="text-[11px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-1.5 py-0.2 rounded shadow-sm uppercase tracking-wider">
							{{ $code }}
						</span>
					</div>
					
					<span class="hidden sm:inline text-slate-300 text-xs">|</span>

					<div class="flex items-center gap-1 text-slate-500">
						<i class="fa-regular fa-calendar text-[10px]"></i>
						<span class="text-[11px] font-medium">
							{{ isset($tournament->created_at) ? date('d M Y', strtotime($tournament->created_at)) : date('d M Y') }}
						</span>
					</div>
				</div>
			</div>
			
			<div class="flex items-center gap-2 flex-shrink-0">
				<span class="text-xs font-bold px-3 py-1.5 rounded-full border shadow-sm {{ ($view ?? 'setup') === 'setup' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
					<i class="fa-solid {{ ($view ?? 'setup') === 'setup' ? 'fa-sliders' : 'fa-circle-dot animate-pulse' }} mr-1"></i>
					{{ ($view ?? 'setup') === 'setup' ? 'Setup' : 'Live' }}
				</span>

				<form method="POST" action="{{ route('logout') }}" class="inline m-0 p-0 flex items-center">
					@csrf
					<button type="submit" 
							class="w-8 h-8 border border-rose-200 rounded-lg flex items-center justify-center text-rose-500 hover:text-white hover:bg-rose-500 shadow-sm transition-all"
							title="Keluar / Logout">
						<i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
					</button>
				</form>
			</div>
		</div>
	</header>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4 w-full box-border">
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    <main class="max-w-7xl mx-auto px-4 lg:px-8 mt-4 w-full box-border">
        
        <div class="w-full @if(($view ?? 'setup') !== 'setup') grid grid-cols-1 lg:grid-cols-2 gap-6 items-start @endif">
            
            @if(($view ?? 'setup') === 'setup')
                <div id="view-setup" class="w-full max-w-4xl mx-auto px-1 sm:px-0 box-border">
                    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-200 space-y-6 w-full box-border">
                        <div>
                            <h2 class="text-xl lg:text-2xl font-bold text-slate-900">Daftar Pemain</h2>
                            <p class="text-xs lg:text-sm text-slate-500 mt-0.5">Tentukan kuota dan masukkan nama pemain pertandingan.</p>
                        </div>
                        
                        <form action="{{ route('tournament.start', ['code' => $code]) }}" method="POST" id="main-form" class="space-y-5 w-full block">
                            @csrf
                            
                            <div>
                                <label for="max-players" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Jumlah Pemain
                                </label>
                                <select id="max-players" name="max_players" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm lg:text-base bg-white font-medium text-slate-700 shadow-sm box-border">
                                    <option value="4">4 Pemain</option>
                                    <option value="8">8 Pemain</option>
                                    <option value="12">12 Pemain</option>
                                    <option value="16">16 Pemain</option>
                                    <option value="20">20 Pemain</option>
                                    <option value="24">24 Pemain</option>
                                    <option value="28">28 Pemain</option>
                                    <option value="32">32 Pemain</option>
                                </select>
                            </div>

                            <div>
                                <label for="player-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Nama Pemain
                                </label>
                                <div class="flex gap-3 w-full items-center">
                                    <input type="text" id="player-input" placeholder="Ketik nama pemain..." class="flex-1 w-full min-w-0 px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm lg:text-base bg-slate-50/50 box-border">
                                    <button type="button" onclick="addPlayer()" class="bg-emerald-500 hover:bg-emerald-600 text-white w-12 h-11 sm:w-14 sm:h-12 flex items-center justify-center rounded-xl text-lg font-medium transition-colors shadow-sm flex-shrink-0">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="hidden-inputs-container"></div>

							<div>
								<label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
									Pemain Terdaftar
								</label>
								<div class="w-full border border-slate-150 rounded-xl divide-y divide-slate-100 min-h-0 max-h-72 overflow-y-auto shadow-inner bg-slate-50/30 box-border" id="player-list-container">
									<div class="text-sm lg:text-base text-slate-400 text-center py-5 w-full px-4" id="empty-player-text">
										<i class="fa-regular fa-user block text-lg mb-1 text-slate-300"></i>
										Belum ada pemain yang terdaftar.
									</div>
								</div>
							</div>
                            
                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-xl font-semibold shadow-md flex justify-center items-center gap-2 text-sm lg:text-base transition-all box-border">
                                <i class="fa-solid fa-play text-xs"></i> Mulai Generate Match
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if(($view ?? 'setup') !== 'setup')
            <div id="view-matches" class="w-full space-y-4 flex flex-col justify-start">
                
                <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-200 w-full h-[74px]">
                    <a href="{{ route('tournament.index', ['code' => $code, 'view' => 'matches', 'round' => max(1, ($currentRound ?? 1) - 1)]) }}" class="text-slate-600 w-10 h-10 border border-slate-200 flex items-center justify-center hover:bg-slate-50 rounded-xl transition-all shadow-sm"><i class="fa-solid fa-chevron-left text-sm"></i></a>
                    <div class="text-center">
                        <span class="font-extrabold text-base text-slate-800">Ronde {{ $currentRound ?? 1 }} <span class="text-slate-400 font-normal">dari</span> {{ $totalRounds ?? 1 }}</span>
                    </div>
                    <a href="{{ route('tournament.index', ['code' => $code, 'view' => 'matches', 'round' => min(($totalRounds ?? 1), ($currentRound ?? 1) + 1)]) }}" class="text-slate-600 w-10 h-10 border border-slate-200 flex items-center justify-center hover:bg-slate-50 rounded-xl transition-all shadow-sm"><i class="fa-solid fa-chevron-right text-sm"></i></a>
                </div>

                <div class="space-y-4 w-full flex-1">
                    @forelse($matches ?? [] as $match)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-4 hover:shadow-md transition-all w-full box-border">
                        <form action="{{ route('match.update-score', ['code' => $code, 'matchId' => $match->id ?? 0, 'round' => $currentRound ?? 1]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-600 flex items-center gap-1.5"><i class="fa-solid fa-map-pin text-emerald-500"></i> Lapangan {{ $match->court }}</span>
                                @if($match->is_finished)
                                    <span class="text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-md flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                @else
                                    <span class="text-amber-500 bg-amber-50 px-2 py-0.5 rounded-md flex items-center gap-1"><i class="fa-solid fa-clock"></i> Live</span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-7 items-center gap-2 pt-2">
                                <div class="col-span-3 text-right space-y-0.5">
                                    <div class="font-bold text-sm text-slate-800 truncate">{{ $match->playerA1->name ?? 'Pemain' }}</div>
                                    <div class="font-bold text-sm text-slate-800 truncate">{{ $match->playerA2->name ?? 'Pemain' }}</div>
                                </div>
                                <div class="col-span-1 text-center text-xs font-black text-slate-300">VS</div>
                                <div class="col-span-3 text-left space-y-0.5">
                                    <div class="font-bold text-sm text-slate-800 truncate">{{ $match->playerB1->name ?? 'Pemain' }}</div>
                                    <div class="font-bold text-sm text-slate-800 truncate">{{ $match->playerB2->name ?? 'Pemain' }}</div>
                                </div>

                                <div class="col-span-3 mt-2">
                                    <input type="number" name="score_a" min="0" placeholder="0" value="{{ $match->score_a }}" class="w-full text-center font-black bg-slate-50 border border-slate-200 rounded-xl py-2.5 text-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" required>
                                </div>
                                <div class="col-span-1"></div>
                                <div class="col-span-3 mt-2">
                                    <input type="number" name="score_b" min="0" placeholder="0" value="{{ $match->score_b }}" class="w-full text-center font-black bg-slate-50 border border-slate-200 rounded-xl py-2.5 text-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full mt-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-3 rounded-xl shadow-sm transition-all uppercase tracking-wider">
                                Simpan Skor Pertandingan
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-center text-sm text-slate-400 bg-white py-12 rounded-2xl border border-dashed w-full">Belum ada jadwal pertandingan.</p>
                    @endforelse
                </div>
            </div>

            <div id="view-leaderboard" class="w-full flex flex-col justify-start">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full flex flex-col h-full">
                    <div class="p-4 border-b border-slate-100 bg-emerald-600 text-white flex justify-between items-center h-[74px]">
                        <h2 class="text-base font-bold flex items-center gap-2">
                            <i class="fa-solid fa-ranking-star"></i> Klasemen Pertandingan
                        </h2>
                        <span class="text-xs bg-emerald-700 px-2 py-0.5 rounded-md animate-pulse">Live Mode</span>
                    </div>
                    
                    <div class="divide-y divide-slate-100 flex-1 overflow-y-auto">
						@forelse($players ?? [] as $idx => $player)
						<div class="flex items-center justify-between px-5 py-3.5 {{ $idx === 0 ? 'bg-amber-50/60' : '' }} hover:bg-slate-50 transition-colors">
							<div class="flex items-center gap-3 min-w-0 flex-1">
								@if($idx === 0)
									<span class="w-6 text-center font-bold text-amber-500 text-base flex-shrink-0"><i class="fa-solid fa-trophy animate-bounce"></i></span>
								@elseif($idx === 1)
									<span class="w-6 text-center font-bold text-slate-400 text-sm flex-shrink-0">2</span>
								@elseif($idx === 2)
									<span class="w-6 text-center font-bold text-amber-700 text-sm flex-shrink-0">3</span>
								@else
									<span class="w-6 text-center font-semibold text-slate-500 text-sm flex-shrink-0">{{ $idx + 1 }}</span>
								@endif
								
								<span class="font-bold text-sm text-slate-800 truncate">{{ $player->name }}</span>
							</div>

							<div class="flex items-center gap-3 flex-shrink-0 ml-2">
								<div class="text-right min-w-[45px]">
									<span class="text-base font-black text-slate-900">{{ $player->points }}</span>
									<span class="text-xs text-slate-400 font-bold uppercase ml-0.5">Pts</span>
								</div>

								<span class="inline-flex items-center bg-blue-50 text-blue-700 border border-blue-100 px-1.5 py-0.5 rounded text-[10px] font-bold flex-shrink-0">
									<i class="fa-solid fa-circle-check text-[9px] mr-1"></i> {{ $player->wins_count ?? 0 }} W
								</span>
							</div>
						</div>
						@empty
						<p class="text-sm text-slate-400 text-center py-6">Belum ada data klasemen.</p>
						@endforelse
					</div>
                </div>
            </div>
            @endif

        </div> 
    </main>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg px-4 py-2 z-50 lg:hidden">
        <div class="max-w-md mx-auto flex justify-around">
            <a href="{{ route('tournament.index', ['code' => $code, 'view' => 'setup']) }}" class="flex flex-col items-center gap-1 p-2 {{ ($view ?? 'setup') === 'setup' ? 'text-emerald-500 font-medium' : 'text-slate-400' }}">
                <i class="fa-solid fa-user-plus text-lg"></i>
                <span class="text-[10px]">Setup</span>
            </a>
            <a href="{{ route('tournament.index', ['code' => $code, 'view' => 'matches', 'round' => $currentRound ?? 1]) }}" class="flex flex-col items-center gap-1 p-2 {{ ($view ?? 'setup') === 'matches' ? 'text-emerald-500 font-medium' : 'text-slate-400' }}">
                <i class="fa-solid fa-calendar-days text-lg"></i>
                <span class="text-[10px]">Matches</span>
            </a>
            <a href="{{ route('tournament.index', ['code' => $code, 'view' => 'leaderboard']) }}" class="flex flex-col items-center gap-1 p-2 {{ ($view ?? 'setup') === 'leaderboard' ? 'text-emerald-500 font-medium' : 'text-slate-400' }}">
                <i class="fa-solid fa-ranking-star text-lg"></i>
                <span class="text-[10px]">Leaderboard</span>
            </a>
        </div>
    </nav>

    <script>
        let localPlayers = [];

        function addPlayer() {
            const input = document.getElementById('player-input');
            const maxPlayersSelect = document.getElementById('max-players');
            const name = input.value.trim();
            
            const maxPlayers = parseInt(maxPlayersSelect.value, 10);
            if (!name) return;

            if (localPlayers.length >= maxPlayers) {
                alert(`Tidak dapat menambah pemain! Batas maksimal yang dipilih adalah ${maxPlayers} pemain.`);
                return;
            }

            localPlayers.push(name);
            input.value = '';
            renderList();
            
            input.focus();
        }

        function removePlayer(idx) {
            localPlayers.splice(idx, 1);
            renderList();
        }

        function renderList() {
			const container = document.getElementById('player-list-container');
			const hiddenContainer = document.getElementById('hidden-inputs-container');

			if (localPlayers.length === 0) {
				container.innerHTML = `
					<div class="text-sm lg:text-base text-slate-400 text-center py-5 w-full px-4" id="empty-player-text">
						<i class="fa-regular fa-user block text-lg mb-1 text-slate-300"></i>
						Belum ada pemain yang terdaftar.
					</div>
				`;
				hiddenContainer.innerHTML = '';
				return;
			}

			container.innerHTML = localPlayers.map((player, idx) => `
				<div class="flex justify-between items-center px-4 py-3.5 text-base bg-white w-full box-border">
					<span class="font-semibold text-slate-700 truncate mr-2">${idx + 1}. ${player}</span>
					<button type="button" onclick="removePlayer(${idx})" class="text-rose-500 p-2 hover:bg-rose-50 rounded-xl transition-colors flex-shrink-0"><i class="fa-regular fa-trash-can text-sm lg:text-base"></i></button>
				</div>
			`).join('');

			hiddenContainer.innerHTML = localPlayers.map(player => `
				<input type="hidden" name="players[]" value="${player.replace(/"/g, '&quot;')}">
			`).join('');
		}

        document.getElementById('player-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addPlayer();
            }
        });

		document.getElementById('main-form').addEventListener('submit', function(e) {
			const maxPlayers = parseInt(document.getElementById('max-players').value, 10);
			if (localPlayers.length !== maxPlayers) {
				e.preventDefault();
				alert(`Minimal harus ada ${maxPlayers} pemain!`);
			}
		});
    </script>
</body>
</html>