@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('coffee.index') }}" class="text-blue-500 hover:text-blue-700">← Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tingkat Roasting Biji Kopi</h1>
        <p class="text-gray-600 mb-8">Panduan lengkap tentang 4 tingkat roasting biji kopi</p>

        <div class="space-y-6">
            @foreach(\App\Helpers\RoastingHelper::getAllLevels() as $level)
                <div class="border-l-4 {{ str_replace(['bg-', 'text-'], ['border-', 'bg-'], explode(' ', $level['color'])[0]) }} bg-gray-50 p-6 rounded-r-lg">
                    <div class="flex items-center mb-3">
                        <span class="text-4xl mr-3">{{ $level['icon'] }}</span>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $level['name']}}</h2>
                            <span class="inline-block {{ $level['color'] }} text-sm px-3 py-1 rounded-full font-semibold mt-1">
                                {{ $level['name'] }} Roast
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $level['description'] }}</p>
                    
                    @if($level['name'] === 'Green')
                        <div class="mt-4 bg-white p-4 rounded border border-green-200">
                            <h4 class="font-semibold text-green-800 mb-2">Karakteristik:</h4>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Warna hijau keabu-abuan</li>
                                <li>Tekstur keras dan padat</li>
                                <li>Tidak memiliki aroma kopi</li>
                                <li>Tidak bisa langsung diseduh</li>
                            </ul>
                        </div>
                    @elseif($level['name'] === 'Light')
                        <div class="mt-4 bg-white p-4 rounded border border-yellow-200">
                            <h4 class="font-semibold text-yellow-800 mb-2">Karakteristik:</h4>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Warna cokelat muda</li>
                                <li>Rasa asam yang menonjol</li>
                                <li>Aroma floral dan fruity</li>
                                <li>Body ringan</li>
                                <li>Kafein tinggi</li>
                            </ul>
                        </div>
                    @elseif($level['name'] === 'Medium')
                        <div class="mt-4 bg-white p-4 rounded border border-orange-200">
                            <h4 class="font-semibold text-orange-800 mb-2">Karakteristik:</h4>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Warna cokelat sedang</li>
                                <li>Keseimbangan antara asam dan pahit</li>
                                <li>Aroma karamel dan nutty</li>
                                <li>Body sedang</li>
                                <li>Paling populer di Indonesia</li>
                            </ul>
                        </div>
                    @else
                        <div class="mt-4 bg-white p-4 rounded border border-amber-800">
                            <h4 class="font-semibold text-amber-900 mb-2">Karakteristik:</h4>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Warna cokelat gelap hingga hitam</li>
                                <li>Rasa pahit yang kuat</li>
                                <li>Aroma smoky dan bold</li>
                                <li>Body penuh dan kental</li>
                                <li>Kafein lebih rendah</li>
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-3">💡 Tentang Sistem Klasifikasi</h3>
            <p class="text-gray-700 mb-3">
                Sistem ini menggunakan teknologi Machine Learning untuk mengklasifikasikan tingkat roasting biji kopi secara otomatis berdasarkan gambar.
            </p>
            <div class="bg-white rounded p-4 border border-blue-100">
                <h4 class="font-semibold text-blue-800 mb-2">Cara Kerja:</h4>
                <ol class="list-decimal list-inside text-gray-700 space-y-2">
                    <li>Upload gambar biji kopi melalui form</li>
                    <li>Gambar dikirim ke Flask API untuk dianalisis</li>
                    <li>Model AI mengklasifikasikan tingkat roasting</li>
                    <li>Hasil ditampilkan dengan confidence score</li>
                </ol>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('coffee.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                Coba Klasifikasi Sekarang →
            </a>
        </div>
    </div>
</div>
@endsection
