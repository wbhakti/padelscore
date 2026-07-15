<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cubiq Padel - Create Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-center items-center px-4">

    <div class="max-w-md w-full bg-white p-6 rounded-2xl shadow-sm border border-slate-200 text-center space-y-6">
        <div class="relative pt-4">
            <form method="POST" action="{{ route('logout') }}" class="absolute top-0 right-0 m-0 p-0">
                @csrf
                <button type="submit" 
                        class="w-8 h-8 border border-rose-100 rounded-xl flex items-center justify-center text-rose-500 hover:text-white hover:bg-rose-500 shadow-sm transition-all"
                        title="Keluar / Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>

            <div class="space-y-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mb-2">
                    <i class="fa-solid fa-table-tennis-paddle-ball text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    CUBIQ PADEL
                </h1>
                <p class="text-sm text-slate-500">
                    Buat ruang match baru untuk melanjutkan kompetisi padel.
                </p>
            </div>
        </div>

        <hr class="border-slate-100">

        <form action="{{ route('tournament.store-room') }}" method="POST" class="space-y-4 text-left">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    Nama Match
                </label>
                <input type="text" name="name" id="name" placeholder="Contoh: Weekend Padel Mania" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm bg-slate-50/50" required>
            </div>

            <button type="submit" 
                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-xl font-semibold shadow-md flex justify-center items-center gap-2 text-sm transition-all">
                <i class="fa-solid fa-square-plus"></i> Buat Room Match
            </button>
            
            <a href="{{ route('tournament.history') }}" 
                class="w-full bg-white border-2 border-emerald-500 text-emerald-700 hover:bg-emerald-50 py-3.5 rounded-xl font-bold shadow-sm flex justify-center items-center gap-2 text-sm transition-all">
                <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i> Lihat Riwayat Match
            </a>
        </form>

        <div class="text-[11px] text-slate-400">
            <i class="fa-solid fa-circle-info"></i> Setelah room dibuat, Anda akan mendapatkan kode Match.
        </div>
    </div>

</body>
</html>