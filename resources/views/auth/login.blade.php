@extends('layouts.app')

@section('title', 'Login - PAO Monitoring')

@section('content')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="flex items-center justify-center min-h-[85vh] p-4 bg-slate-50">
    <div class="w-full max-w-[420px] bg-white border border-slate-100 rounded-[2rem] p-10 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
        
        <div class="mb-10 text-center">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg shadow-blue-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sign In</h1>
            <p class="text-slate-400 mt-2 font-medium">PT. Pilar Artha Oetama</p>
        </div>

        @if(session()->has('loginError'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                {{ session('loginError') }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-6" x-data="{ show: false }">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
                <input type="email" name="email" placeholder="contoh@example.com" 
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all duration-300 placeholder:text-slate-400" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                <div class="relative group">
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" 
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all duration-300 placeholder:text-slate-400" required>
                    
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-blue-600 transition-colors">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.076m3.108-3.323A9.959 9.959 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.259 0-2.434-.232-3.515-.656m-3.076-3.108L3 3l18 18"></path></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-[0_10px_20px_rgba(37,99,235,0.2)] hover:shadow-none active:scale-[0.98]">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-10 pt-6 border-t border-slate-50 text-center">
            <p class="text-sm text-slate-400 italic">Dikelola oleh <span class="font-bold text-slate-600">PT.Pilar Artha Oetama</span></p>
        </div>
    </div>
</div>
@endsection