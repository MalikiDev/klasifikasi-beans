@extends('layouts.app')

@section('title', 'Dashboard — CoffeeAI')

@section('content')

<!-- ─────────────────────── WRAPPER ─────────────────────── -->
<div class="info-bg min-h-screen">
    
    <!-- ══════════ HERO / HEADER ══════════ -->
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Top Bar -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Dashboard Pengguna</p>
                    <h1 class="text-4xl sm:text-5xl font-semibold tracking-tighter text-gray-900 leading-[1.05]">
                        History Klasifikasi
                    </h1>
                </div>
                <a href="{{ route('coffee.create') }}" 
                   class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Klasifikasi Baru
                </a>
            </div>

            <!-- Stats & Legend Strip -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 pt-2">
                <!-- Legend Levels -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mr-2">Kelas Roasting:</span>
                    @foreach(\App\Helpers\RoastingHelper::getAllLevels() as $level)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 bg-white shadow-sm">
                            <span class="w-2 h-2 rounded-full {{ $level['color'] }} bg-current opacity-80"></span>
                            <span class="text-xs font-semibold text-gray-600">{{ $level['name'] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Add Button -->
                <a href="{{ route('coffee.create') }}" 
                   class="sm:hidden inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Data Baru
                </a>
            </div>

        </div>
    </div>

    <!-- ══════════ MAIN CONTENT ══════════ -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 anim d1">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold">Sukses!</p>
                    <p class="text-xs text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Livewire History Dashboard Component -->
        @livewire('history-dashboard')

    </div>
</div>

@endsection
