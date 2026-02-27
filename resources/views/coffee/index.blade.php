@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Sistem Klasifikasi Roasting Biji Kopi</h1>
            <p class="text-gray-600 mt-1">Klasifikasi otomatis: Green, Light, Medium, Dark</p>
        </div>
        <a href="{{ route('coffee.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Tambah Data Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Roasting Level Legend -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="font-semibold text-gray-800 mb-3">Tingkat Roasting:</h3>
        <div class="flex flex-wrap gap-3">
            @foreach(\App\Helpers\RoastingHelper::getAllLevels() as $level)
                <div class="flex items-center">
                    <span class="text-2xl mr-2">{{ $level['icon'] }}</span>
                    <span class="inline-block {{ $level['color'] }} px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $level['name'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($coffeeBeans as $bean)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($bean->image_path)
                    <img src="{{ asset('storage/' . $bean->image_path) }}" alt="{{ $bean->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $bean->name }}</h3>
                    
                    @if($bean->final_classification)
                        <div class="mb-2">
                            <span class="inline-block {{ \App\Helpers\RoastingHelper::getBadgeColor($bean->final_classification) }} text-xs px-2 py-1 rounded font-semibold">
                                {{ \App\Helpers\RoastingHelper::getIcon($bean->final_classification) }} {{ $bean->final_classification }}
                            </span>
                            @if($bean->models_agree !== null)
                                <span class="inline-block {{ $bean->models_agree ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs px-2 py-1 rounded">
                                    {{ $bean->models_agree ? '✓ Setuju' : '⚠ Beda' }}
                                </span>
                            @endif
                        </div>
                        
                        @if($bean->confidence_small && $bean->confidence_large)
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Small:</span>
                                    <span class="font-semibold">{{ number_format($bean->confidence_small, 1) }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Large:</span>
                                    <span class="font-semibold">{{ number_format($bean->confidence_large, 1) }}%</span>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($bean->variety)
                        <p class="text-sm text-gray-600 mb-1"><strong>Varietas:</strong> {{ $bean->variety }}</p>
                    @endif
                    
                    @if($bean->origin)
                        <p class="text-sm text-gray-600 mb-3"><strong>Asal:</strong> {{ $bean->origin }}</p>
                    @endif

                    <div class="flex gap-2">
                        <a href="{{ route('coffee.show', $bean) }}" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm">
                            Detail
                        </a>
                        <a href="{{ route('coffee.edit', $bean) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded text-sm">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">☕</div>
                <p class="text-gray-500 text-lg mb-2">Belum ada data biji kopi.</p>
                <p class="text-gray-400 text-sm mb-4">Mulai dengan menambahkan data pertama Anda!</p>
                <a href="{{ route('coffee.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Data Pertama
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $coffeeBeans->links() }}
    </div>
</div>
@endsection
