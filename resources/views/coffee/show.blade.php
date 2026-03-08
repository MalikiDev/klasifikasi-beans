@extends('layouts.app')

@section('title', $coffee->name . ' — BeanRoast ML')

@section('content')

@php
$classStyle = [
    'Dark'   => ['badge' => 'bg-gray-900 text-white',        'bar' => 'bg-gray-800',   'accent' => 'border-gray-300',  'soft' => 'bg-gray-50'],
    'Green'  => ['badge' => 'bg-green-100 text-green-800',   'bar' => 'bg-green-400',  'accent' => 'border-green-200', 'soft' => 'bg-green-50'],
    'Light'  => ['badge' => 'bg-yellow-100 text-yellow-800', 'bar' => 'bg-yellow-300', 'accent' => 'border-yellow-200','soft' => 'bg-yellow-50'],
    'Medium' => ['badge' => 'bg-orange-100 text-orange-800', 'bar' => 'bg-orange-400', 'accent' => 'border-orange-200','soft' => 'bg-orange-50'],
];
$cls     = $coffee->final_classification ?? 'Dark';
$style   = $classStyle[$cls] ?? $classStyle['Dark'];

// Round time helper: ms → "Xs" if ≥ 1000, else "Xms"
$fmtTime = fn($ms) => $ms >= 1000 ? round($ms / 1000) . 's' : round($ms) . 'ms';
@endphp

<div class="min-h-screen bg-gray-50">

    {{-- ══ STICKY TOP BAR ══ --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('coffee.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Kembali ke History
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('coffee.edit', $coffee) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                <form action="{{ route('coffee.destroy', $coffee) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 text-xs font-semibold text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">

        {{-- Flash --}}
        @if(session('success'))
        <div class="mb-8 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- ══ PAGE HEADER ══ --}}
        <div class="mb-10">
            <p class="text-[11px] tracking-[0.16em] uppercase text-gray-400 mb-3">Detail Klasifikasi</p>
            <div class="flex items-start justify-between gap-6 flex-wrap">
                <div>
                    <h1 class="text-4xl font-semibold text-gray-900 tracking-tight leading-tight mb-2">
                        {{ $coffee->name }}
                    </h1>
                    <p class="text-sm text-gray-400">
                        {{ $coffee->created_at?->format('d M Y, H:i') }}
                        · {{ $coffee->created_at?->diffForHumans() }}
                    </p>
                </div>
                @if($coffee->final_classification)
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full font-bold text-sm {{ $style['badge'] }}">
                        {{ $coffee->final_classification }}
                    </span>
                    <span class="text-sm font-medium {{ $coffee->models_agree ? 'text-green-600' : 'text-amber-500' }}">
                        {{ $coffee->models_agree ? '✓ Models Agree' : '⚠ Models Differ' }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- ══ MAIN GRID ══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ── LEFT: Image + meta ── --}}
            <div class="lg:col-span-1 space-y-5">

                {{-- Image --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    @if($coffee->image_path)
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/'.$coffee->image_path) }}"
                                 alt="{{ $coffee->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-square bg-gray-100 flex flex-col items-center justify-center gap-2 text-gray-300">
                            <svg class="w-14 h-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span class="text-xs font-medium">No Image</span>
                        </div>
                    @endif

                    {{-- Meta below image --}}
                    @if($coffee->description || $coffee->variety || $coffee->origin)
                    <div class="p-5 border-t border-gray-100 space-y-3">
                        @if($coffee->description)
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $coffee->description }}</p>
                        @endif
                        @if($coffee->variety || $coffee->origin)
                        <div class="space-y-1.5 text-xs">
                            @if($coffee->variety)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 uppercase tracking-wide font-semibold text-[10px]">Varietas</span>
                                <span class="text-gray-700 font-medium">{{ $coffee->variety }}</span>
                            </div>
                            @endif
                            @if($coffee->origin)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 uppercase tracking-wide font-semibold text-[10px]">Asal</span>
                                <span class="text-gray-700 font-medium">{{ $coffee->origin }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Reclassify --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Aksi</p>
                    <form action="{{ route('coffee.reclassify', $coffee) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                            Klasifikasi Ulang
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── RIGHT: Results ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Agreement Banner --}}
                @if($coffee->classification_small && $coffee->classification_large)
                <div class="rounded-2xl border p-5 {{ $coffee->models_agree ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200' }}">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $coffee->models_agree ? 'bg-green-100' : 'bg-amber-100' }}">
                                @if($coffee->models_agree)
                                    <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold {{ $coffee->models_agree ? 'text-green-800' : 'text-amber-800' }}">
                                    {{ $coffee->models_agree ? 'Kedua Model Setuju' : 'Model Berbeda Pendapat' }}
                                </p>
                                @if($coffee->comparison_analysis['recommendation'] ?? null)
                                <p class="text-xs {{ $coffee->models_agree ? 'text-green-600' : 'text-amber-600' }} mt-0.5">
                                    {{ $coffee->comparison_analysis['recommendation'] }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @if($coffee->confidence_difference)
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $coffee->models_agree ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            Selisih {{ number_format($coffee->confidence_difference, 1) }}%
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Model cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- ── Small Model ── --}}
                    @if($coffee->classification_small)
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold mb-0.5">MobileNetV3</p>
                                <p class="text-base font-bold text-gray-900">Small</p>
                            </div>
                            @if($coffee->processing_time_small)
                            <span class="flex items-center gap-1 px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-600">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $fmtTime($coffee->processing_time_small) }}
                            </span>
                            @endif
                        </div>

                        {{-- Classification badge --}}
                        <div class="mb-5">
                            <span class="inline-block px-3 py-1.5 rounded-full text-sm font-bold {{ $classStyle[$coffee->classification_small]['badge'] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $coffee->classification_small }}
                            </span>
                        </div>

                        {{-- Confidence bar --}}
                        @if($coffee->confidence_small)
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-medium">Confidence</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($coffee->confidence_small, 1) }}%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-700 rounded-full transition-all" style="width:{{ $coffee->confidence_small }}%"></div>
                            </div>
                        </div>
                        @endif

                        {{-- Predictions breakdown --}}
                        @if($coffee->predictions_small)
                        <div class="space-y-2">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold">Semua Kelas</p>
                            @foreach($coffee->predictions_small as $pred)
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-500 w-14 truncate">{{ $pred['class'] }}</span>
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all
                                                {{ ($classStyle[$pred['class']]['bar'] ?? 'bg-gray-400') }}"
                                         style="width:{{ $pred['confidence'] }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-600 w-10 text-right">{{ number_format($pred['confidence'], 1) }}%</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- ── Large Model ── --}}
                    @if($coffee->classification_large)
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold mb-0.5">MobileNetV3</p>
                                <p class="text-base font-bold text-gray-900">Large</p>
                            </div>
                            @if($coffee->processing_time_large)
                            <span class="flex items-center gap-1 px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-600">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $fmtTime($coffee->processing_time_large) }}
                            </span>
                            @endif
                        </div>

                        <div class="mb-5">
                            <span class="inline-block px-3 py-1.5 rounded-full text-sm font-bold {{ $classStyle[$coffee->classification_large]['badge'] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $coffee->classification_large }}
                            </span>
                        </div>

                        @if($coffee->confidence_large)
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-medium">Confidence</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($coffee->confidence_large, 1) }}%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-900 rounded-full transition-all" style="width:{{ $coffee->confidence_large }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($coffee->predictions_large)
                        <div class="space-y-2">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold">Semua Kelas</p>
                            @foreach($coffee->predictions_large as $pred)
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-500 w-14 truncate">{{ $pred['class'] }}</span>
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all
                                                {{ ($classStyle[$pred['class']]['bar'] ?? 'bg-gray-400') }}"
                                         style="width:{{ $pred['confidence'] }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-600 w-10 text-right">{{ number_format($pred['confidence'], 1) }}%</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- ── Comparison Analysis ── --}}
                @if($coffee->comparison_analysis && (isset($coffee->comparison_analysis['faster_model']) || isset($coffee->comparison_analysis['more_confident_model'])))
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold mb-5">Analisis Perbandingan</p>
                    <div class="grid grid-cols-2 gap-4">
                        @if(isset($coffee->comparison_analysis['faster_model']))
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold mb-1">Model Tercepat</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $coffee->comparison_analysis['faster_model'] === 'small' ? 'Small' : 'Large' }}
                            </p>
                            @if(isset($coffee->comparison_analysis['speed_improvement_percent']))
                            <p class="text-[10px] text-gray-400 mt-1">{{ number_format($coffee->comparison_analysis['speed_improvement_percent'], 0) }}% lebih cepat</p>
                            @endif
                        </div>
                        @endif
                        @if(isset($coffee->comparison_analysis['more_confident_model']))
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold mb-1">Model Paling Yakin</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $coffee->comparison_analysis['more_confident_model'] === 'small' ? 'Small' : 'Large' }}
                            </p>
                            @if(isset($coffee->comparison_analysis['confidence_improvement']))
                            <p class="text-[10px] text-gray-400 mt-1">+{{ number_format($coffee->comparison_analysis['confidence_improvement'], 1) }}% confidence</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection