<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-2xl shadow-md border border-slate-200 space-y-6">
        <div class="text-center space-y-1">
            <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto text-xl shadow-sm">
                <i class="fa-solid fa-table-tennis-paddle-ball"></i>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-slate-900 mt-3">Selamat Datang</h2>
            <p class="text-xs sm:text-sm text-slate-500">Silakan login untuk mengelola room pertandingan.</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i> 
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" 
                           class="w-full pl-9 pr-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm bg-slate-50/50" required shadow-sm>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="••••••••" 
                           class="w-full pl-9 pr-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm bg-slate-50/50" required shadow-sm>
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-xl font-semibold shadow-md flex justify-center items-center gap-2 text-sm transition-all mt-6">
                <i class="fa-solid fa-right-to-bracket text-xs"></i> Masuk Aplikasi
            </button>
        </form>
    </div>

</body>
</html>