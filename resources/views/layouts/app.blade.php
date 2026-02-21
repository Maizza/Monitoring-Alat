<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PAO System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

    <div class="flex min-h-screen">
        @auth
        <aside class="w-72 bg-white border-r border-slate-200 flex flex-col fixed h-full z-50">
            <div class="p-8 flex items-center gap-3">
                <svg class="w-8 h-8 text-[#38bdf8]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8zm-6 7.2c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C7.666 17.818 9.027 19 12.001 19c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C10.337 13.382 8.976 12 6.001 12z"></path>
                </svg>
                <h2 class="text-xl font-bold tracking-tight text-slate-800">PT.<span class="text-blue-600">PAO</span></h2>
            </div>
            
            <nav class="flex-1 px-6 space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-4 mb-4 mt-2">Main Menu</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('user.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->is('users*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-semibold text-sm">Manajemen User</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-4 mt-8 mb-4">Operations</p>
                
                <a href="{{ route('alat.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->is('alat*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="font-semibold text-sm">Manajemen Alat</span>
                </a>

                <a href="{{ route('repair.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->is('repair*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="font-semibold text-sm">Repair</span>
                </a>

                <a href="{{ route('maintenance.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->is('report*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-semibold text-sm">Data Maintenance</span>
                </a>
            </nav>

            <div class="p-6">
                <div class="bg-slate-900 rounded-3xl p-5 shadow-xl shadow-slate-200">
                    <div class="flex items-center gap-3 mb-4 text-white">
                        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center font-bold text-xs italic">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] text-slate-400 italic uppercase">Administrator</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold rounded-xl transition-all">
                            LOGOUT
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 ml-72 flex flex-col min-h-screen">
            <header class="h-16 border-b border-slate-100 bg-white/50 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-10">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PT. Pilar Artha Oetama</div>
                <div class="flex items-center gap-4">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">System Online</span>
                </div>
            </header>

            <main class="flex-1 p-10">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </main>

            <footer class="p-8 text-center text-slate-400 text-[10px] font-medium tracking-widest border-t border-slate-100">
                &copy; {{ date('Y') }} CRAFTED BY <span class="text-slate-900 font-bold italic underline decoration-blue-500">PT.PAO</span>
            </footer>
        </div>
        @else
            <div class="w-full">
                @yield('content')
            </div>
        @endauth
    </div>

</body>
</html>