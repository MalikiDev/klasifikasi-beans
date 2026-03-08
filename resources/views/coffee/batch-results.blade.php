@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="border-b border-gray-100 bg-white sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('coffee.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-900 transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Kembali ke History
            </a>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                <span class="text-[11px] font-medium text-gray-500 tracking-widest uppercase">Batch Complete</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-14">

        <!-- Page Header -->
        <div class="mb-12">
            <p class="text-[11px] tracking-[0.16em] uppercase text-gray-400 mb-4">Hasil Klasifikasi Batch</p>
            <h1 class="font-semibold text-5xl text-gray-900 leading-tight mb-4">
                Batch <span class="text-gray-300">Results</span>
            </h1>
            <p class="text-gray-400 text-base max-w-2xl leading-relaxed">
                {{ $stats['total'] }} gambar berhasil diklasifikasi dengan dual model AI. 
                    Berikut adalah ringkasan dan detail hasil klasifikasi.
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <!-- Total Images -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Gambar</span>
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>

            <!-- Agreement Rate -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Model Agreement</span>
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['models_agreement_rate'] }}%</p>
            </div>

            <!-- Avg Confidence -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Avg Confidence</span>
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ round(($stats['avg_confidence_small'] + $stats['avg_confidence_large']) / 2, 1) }}%</p>
            </div>

            <!-- Processing Time -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Avg Time</span>
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ round(($stats['avg_processing_time_small'] + $stats['avg_processing_time_large']) / 2, 2) }}s</p>
            </div>
        </div>

        <!-- Classification Distribution -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 mb-10">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribusi Klasifikasi</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($stats['classifications'] as $class => $count)
                    @php
                        $allLevels = \App\Helpers\RoastingHelper::getAllLevels();
                        $level = $allLevels[$class] ?? [
                            'name' => $class,
                            'icon' => \App\Helpers\RoastingHelper::getIcon($class),
                            'color' => \App\Helpers\RoastingHelper::getBadgeColor($class)
                        ];
                        $percentage = round(($count / $stats['total']) * 100, 1);
                    @endphp
                    <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl mb-2">{!! $level['icon'] !!}</div>
                        <p class="text-sm font-semibold text-gray-900 mb-1">{{ $level['name'] }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                        <p class="text-xs text-gray-400">{{ $percentage }}%</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Batch Items Grid -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Hasil ({{ $batchItems->count() }} items)</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($batchItems as $item)
                <a href="{{ route('coffee.show', $item->id) }}" 
                   class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all">
                    <!-- Image -->
                    <div class="aspect-square bg-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $item->image_path) }}" 
                             alt="{{ $item->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    
                    <!-- Content -->
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-medium text-gray-400">#{{ $item->batch_sequence }}/{{ $item->batch_total }}</span>
                            @if($item->models_agree)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Agree
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Differ
                                </span>
                            @endif
                        </div>
                        
                        <h4 class="text-base font-semibold text-gray-900 mb-2">{{ $item->final_classification }}</h4>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Small: {{ $item->confidence_small }}%</span>
                            <span>Large: {{ $item->confidence_large }}%</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="mt-10 flex items-center justify-center gap-4">
            <a href="{{ route('coffee.index') }}" 
               class="px-6 py-3 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">
                Lihat Semua History
            </a>
            <a href="{{ route('coffee.create') }}" 
               class="px-6 py-3 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                Klasifikasi Lagi
            </a>
        </div>

        <!-- Confusion Matrices Section -->
        @if($confusionMatrix)
        <div class="mt-16">
            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Confusion Matrices</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Small Model -->
                @if($confusionMatrix->confusion_matrix_small_path)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Small</h4>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                            {{ $confusionMatrix->accuracy_small }}% Accuracy
                        </span>
                    </div>
                    
                    <img src="{{ $confusionMatrix->getConfusionMatrixSmallUrl() }}" 
                         alt="Confusion Matrix Small"
                         class="w-full rounded-lg border border-gray-200 mb-4">
                    
                    <!-- Per-class accuracy -->
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($confusionMatrix->per_class_accuracy_small as $class => $acc)
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ $class }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $acc }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Large Model -->
                @if($confusionMatrix->confusion_matrix_large_path)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Large</h4>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            {{ $confusionMatrix->accuracy_large }}% Accuracy
                        </span>
                    </div>
                    
                    <img src="{{ $confusionMatrix->getConfusionMatrixLargeUrl() }}" 
                         alt="Confusion Matrix Large"
                         class="w-full rounded-lg border border-gray-200 mb-4">
                    
                    <!-- Per-class accuracy -->
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($confusionMatrix->per_class_accuracy_large as $class => $acc)
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ $class }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $acc }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
