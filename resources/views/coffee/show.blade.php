@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('coffee.index') }}" class="text-blue-500 hover:text-blue-700">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Info -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $coffee->name }}</h1>
                @if($coffee->created_at)
                    <p class="text-sm text-gray-500">Ditambahkan: {{ $coffee->created_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
            @if($coffee->final_classification)
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Klasifikasi Final</p>
                    <span class="inline-block {{ \App\Helpers\RoastingHelper::getBadgeColor($coffee->final_classification) }} px-4 py-2 rounded-full font-bold text-lg">
                        {{ \App\Helpers\RoastingHelper::getIcon($coffee->final_classification) }} {{ $coffee->final_classification }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image Column -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-4">
                @if($coffee->image_path)
                    <img src="{{ asset('storage/' . $coffee->image_path) }}" alt="{{ $coffee->name }}" class="w-full h-auto">
                @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Informasi Tambahan</h3>
                    
                    @if($coffee->description)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600">{{ $coffee->description }}</p>
                        </div>
                    @endif

                    @if($coffee->variety || $coffee->origin)
                        <div class="space-y-2 text-sm">
                            @if($coffee->variety)
                                <div>
                                    <span class="font-semibold text-gray-700">Varietas:</span>
                                    <span class="text-gray-600">{{ $coffee->variety }}</span>
                                </div>
                            @endif
                            @if($coffee->origin)
                                <div>
                                    <span class="font-semibold text-gray-700">Asal:</span>
                                    <span class="text-gray-600">{{ $coffee->origin }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t flex gap-2">
                        <a href="{{ route('coffee.edit', $coffee) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded text-center text-sm">
                            Edit
                        </a>
                        <form action="{{ route('coffee.destroy', $coffee) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Model Agreement Status -->
            @if($coffee->classification_small && $coffee->classification_large)
                <div class="bg-gradient-to-r {{ $coffee->models_agree ? 'from-green-50 to-emerald-50 border-green-500' : 'from-yellow-50 to-orange-50 border-yellow-500' }} border-l-4 p-6 rounded-r-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold {{ $coffee->models_agree ? 'text-green-800' : 'text-yellow-800' }}">
                            {{ $coffee->models_agree ? '✓ Kedua Model Setuju' : '⚠ Model Berbeda Pendapat' }}
                        </h3>
                        @if($coffee->confidence_difference)
                            <span class="text-sm {{ $coffee->models_agree ? 'text-green-700' : 'text-yellow-700' }}">
                                Selisih Confidence: {{ number_format($coffee->confidence_difference, 2) }}%
                            </span>
                        @endif
                    </div>
                    
                    @if($coffee->comparison_analysis && isset($coffee->comparison_analysis['recommendation']))
                        <p class="text-sm {{ $coffee->models_agree ? 'text-green-700' : 'text-yellow-700' }}">
                            <strong>Rekomendasi:</strong> {{ $coffee->comparison_analysis['recommendation'] }}
                        </p>
                    @endif
                </div>
            @endif

            <!-- MobileNetV3 Small Results -->
            @if($coffee->classification_small)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z"/>
                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                            </svg>
                            MobileNetV3-Small
                        </h3>
                        @if($coffee->processing_time_small)
                            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded">
                                ⚡ {{ $coffee->processing_time_small }}ms
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-medium">Klasifikasi:</span>
                            <span class="inline-block {{ \App\Helpers\RoastingHelper::getBadgeColor($coffee->classification_small) }} px-4 py-2 rounded-full font-semibold">
                                {{ \App\Helpers\RoastingHelper::getIcon($coffee->classification_small) }} {{ $coffee->classification_small }}
                            </span>
                        </div>

                        @if($coffee->confidence_small)
                            @php
                                $confidenceInfo = \App\Helpers\RoastingHelper::getConfidenceLevel($coffee->confidence_small);
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-700 font-medium">Confidence:</span>
                                    <span class="font-bold {{ $confidenceInfo['color'] }}">
                                        {{ number_format($coffee->confidence_small, 2) }}% ({{ $confidenceInfo['level'] }})
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $coffee->confidence_small }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if($coffee->predictions_small)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">Detail Prediksi:</p>
                                <div class="space-y-2">
                                    @foreach($coffee->predictions_small as $pred)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $pred['class'] }}</span>
                                            <div class="flex items-center">
                                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $pred['confidence'] }}%"></div>
                                                </div>
                                                <span class="text-gray-700 font-medium w-16 text-right">{{ number_format($pred['confidence'], 2) }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- MobileNetV3 Large Results -->
            @if($coffee->classification_large)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            MobileNetV3-Large
                        </h3>
                        @if($coffee->processing_time_large)
                            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded">
                                ⚡ {{ $coffee->processing_time_large }}ms
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-medium">Klasifikasi:</span>
                            <span class="inline-block {{ \App\Helpers\RoastingHelper::getBadgeColor($coffee->classification_large) }} px-4 py-2 rounded-full font-semibold">
                                {{ \App\Helpers\RoastingHelper::getIcon($coffee->classification_large) }} {{ $coffee->classification_large }}
                            </span>
                        </div>

                        @if($coffee->confidence_large)
                            @php
                                $confidenceInfo = \App\Helpers\RoastingHelper::getConfidenceLevel($coffee->confidence_large);
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-700 font-medium">Confidence:</span>
                                    <span class="font-bold {{ $confidenceInfo['color'] }}">
                                        {{ number_format($coffee->confidence_large, 2) }}% ({{ $confidenceInfo['level'] }})
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-purple-600 h-3 rounded-full transition-all" style="width: {{ $coffee->confidence_large }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if($coffee->predictions_large)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">Detail Prediksi:</p>
                                <div class="space-y-2">
                                    @foreach($coffee->predictions_large as $pred)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $pred['class'] }}</span>
                                            <div class="flex items-center">
                                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-purple-400 h-2 rounded-full" style="width: {{ $pred['confidence'] }}%"></div>
                                                </div>
                                                <span class="text-gray-700 font-medium w-16 text-right">{{ number_format($pred['confidence'], 2) }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Comparison Analysis -->
            @if($coffee->comparison_analysis)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Analisis Perbandingan Model
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        @if(isset($coffee->comparison_analysis['faster_model']))
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Model Tercepat</p>
                                <p class="text-lg font-bold text-blue-700">
                                    {{ $coffee->comparison_analysis['faster_model'] === 'small' ? 'Small' : 'Large' }}
                                </p>
                                @if(isset($coffee->comparison_analysis['speed_improvement_percent']))
                                    <p class="text-xs text-gray-600">{{ number_format($coffee->comparison_analysis['speed_improvement_percent'], 1) }}% lebih cepat</p>
                                @endif
                            </div>
                        @endif

                        @if(isset($coffee->comparison_analysis['more_confident_model']))
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Model Paling Yakin</p>
                                <p class="text-lg font-bold text-purple-700">
                                    {{ $coffee->comparison_analysis['more_confident_model'] === 'small' ? 'Small' : 'Large' }}
                                </p>
                                @if(isset($coffee->comparison_analysis['confidence_improvement']))
                                    <p class="text-xs text-gray-600">+{{ number_format($coffee->comparison_analysis['confidence_improvement'], 2) }}% confidence</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Reclassify Button -->
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <form action="{{ route('coffee.reclassify', $coffee) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        Klasifikasi Ulang dengan Kedua Model
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
