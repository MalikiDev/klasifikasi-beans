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
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                <span class="text-[11px] font-medium text-gray-500 tracking-widest uppercase">Folder Complete</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-14">

        @if(session('success'))
            <div class="mb-6 px-5 py-3.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 px-5 py-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-12">
            <p class="text-[11px] tracking-[0.16em] uppercase text-gray-400 mb-4">Hasil Klasifikasi Folder (ZIP)</p>
            <h1 class="font-semibold text-5xl text-gray-900 leading-tight mb-4">
                Folder <span class="text-gray-300">Results</span>
            </h1>
            <div class="flex items-center gap-3 flex-wrap">
                <p class="text-gray-400 text-base leading-relaxed">
                    {{ $stats['total'] }} gambar dari ZIP berhasil diklasifikasi dengan dual model AI.
                </p>
                @if($folderMeta['auto_labels'] ?? false)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Auto-labeled
                    </span>
                @endif
                @if($folderMeta['has_confusion_matrix'] ?? false)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                        Confusion Matrix Tersedia
                    </span>
                @endif
            </div>
        </div>

        <!-- Folder Info Banner -->
        <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl">
            <div class="flex items-start gap-4">
                <svg class="w-8 h-8 text-purple-600 flex-shrink-0 mt-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Informasi Folder</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">Batch ID</span>
                            <span class="font-mono text-xs text-gray-700">{{ Str::limit($batchId, 24) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">Struktur</span>
                            <span class="font-semibold text-gray-900">
                                {{ ($folderMeta['structure'] ?? 'flat') === 'class_based' ? 'Per Kelas' : 'Flat' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">Auto Label</span>
                            <span class="font-semibold {{ ($folderMeta['auto_labels'] ?? false) ? 'text-green-600' : 'text-gray-400' }}">
                                {{ ($folderMeta['auto_labels'] ?? false) ? 'Ya ✓' : 'Tidak' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">Confusion Matrix</span>
                            <span class="font-semibold {{ ($folderMeta['has_confusion_matrix'] ?? false) ? 'text-green-600' : 'text-gray-400' }}">
                                {{ ($folderMeta['has_confusion_matrix'] ?? false) ? 'Tersedia ✓' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Total Gambar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Model Agreement</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['models_agreement_rate'] }}%</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Avg Confidence</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ round(($stats['avg_confidence_small'] + $stats['avg_confidence_large']) / 2, 1) }}%
                </p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Avg Time/img</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ round(($stats['avg_processing_time_small'] + $stats['avg_processing_time_large']) / 2000, 3) }}s
                </p>
            </div>
        </div>

        <!-- Model Accuracy Comparison -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <p class="text-xs font-medium text-blue-400 uppercase tracking-wider mb-2">MobileNetV3 Small — Avg Confidence</p>
                <p class="text-3xl font-bold text-blue-700">{{ $stats['avg_confidence_small'] }}%</p>
                <p class="text-xs text-blue-400 mt-1">Avg time: {{ $stats['avg_processing_time_small'] }}ms</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <p class="text-xs font-medium text-green-400 uppercase tracking-wider mb-2">MobileNetV3 Large — Avg Confidence</p>
                <p class="text-3xl font-bold text-green-700">{{ $stats['avg_confidence_large'] }}%</p>
                <p class="text-xs text-green-400 mt-1">Avg time: {{ $stats['avg_processing_time_large'] }}ms</p>
            </div>
        </div>

        <!-- Classification Distribution -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 mb-10">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribusi Klasifikasi</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['Dark', 'Green', 'Light', 'Medium'] as $cls)
                    @php $count = $stats['classifications'][$cls] ?? 0; @endphp
                    <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-sm font-semibold text-gray-900 mb-1">{{ $cls }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Confusion Matrices -->
        @if($confusionMatrix)
        <div class="mb-10">
            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Confusion Matrices</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                @if($confusionMatrix->confusion_matrix_small_path)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Small</h4>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                            {{ $confusionMatrix->accuracy_small }}% Accuracy
                        </span>
                    </div>
                    <img src="{{ Storage::url($confusionMatrix->confusion_matrix_small_path) }}"
                         alt="Confusion Matrix Small"
                         class="w-full rounded-lg border border-gray-200 mb-4">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(($confusionMatrix->per_class_accuracy_small ?? []) as $class => $acc)
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ $class }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $acc }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($confusionMatrix->confusion_matrix_large_path)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Large</h4>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            {{ $confusionMatrix->accuracy_large }}% Accuracy
                        </span>
                    </div>
                    <img src="{{ Storage::url($confusionMatrix->confusion_matrix_large_path) }}"
                         alt="Confusion Matrix Large"
                         class="w-full rounded-lg border border-gray-200 mb-4">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(($confusionMatrix->per_class_accuracy_large ?? []) as $class => $acc)
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

        <!-- Detail Table -->
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Detail Hasil ({{ $folderItems->count() }} items)</h3>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filename</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Final</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Small</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Large</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Agree</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($folderItems as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $item->batch_sequence }}</td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-600 max-w-xs truncate">
                            {{ $item->source_filename ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $colors = ['Dark'=>'bg-gray-900 text-white','Green'=>'bg-green-100 text-green-800',
                                           'Light'=>'bg-yellow-100 text-yellow-800','Medium'=>'bg-orange-100 text-orange-800'];
                                $c = $colors[$item->final_classification] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $c }}">
                                {{ $item->final_classification }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-medium text-gray-900">{{ $item->classification_small }}</div>
                            <div class="text-xs text-gray-400">{{ $item->confidence_small }}%</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-medium text-gray-900">{{ $item->classification_large }}</div>
                            <div class="text-xs text-gray-400">{{ $item->confidence_large }}%</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->models_agree)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">✓ Yes</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">✗ No</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

    </div>
</div>
@endsection