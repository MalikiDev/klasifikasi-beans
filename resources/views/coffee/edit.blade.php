@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('coffee.show', $coffeeBean) }}" class="text-blue-500 hover:text-blue-700">← Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Data Biji Kopi</h1>
        <p class="text-gray-600 mb-6">Update informasi atau ganti gambar untuk klasifikasi ulang</p>

        <form action="{{ route('coffee.update', $coffeeBean) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nama Biji Kopi
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $coffeeBean->name) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                    placeholder="Kosongkan untuk menggunakan nama otomatis">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan nama otomatis dari hasil klasifikasi</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="variety">
                    Varietas
                </label>
                <input type="text" name="variety" id="variety" value="{{ old('variety', $coffeeBean->variety) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="origin">
                    Asal/Origin
                </label>
                <input type="text" name="origin" id="origin" value="{{ old('origin', $coffeeBean->origin) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Deskripsi
                </label>
                <textarea name="description" id="description" rows="4" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    placeholder="Kosongkan untuk menggunakan deskripsi otomatis">{{ old('description', $coffeeBean->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan deskripsi otomatis dari hasil klasifikasi</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Gambar Biji Kopi
                </label>
                
                @if($coffeeBean->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $coffeeBean->image_path) }}" alt="{{ $coffeeBean->name }}" class="max-w-full h-48 object-cover rounded">
                        <p class="text-sm text-gray-600 mt-1">Gambar saat ini</p>
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/*" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror" 
                    onchange="previewImage(event)">
                @error('image')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-600 text-xs mt-1">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="preview" class="max-w-full h-48 object-cover rounded">
                    <p class="text-sm text-gray-600 mt-1">Preview gambar baru</p>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <h4 class="font-semibold text-blue-800 mb-2">ℹ️ Info Update</h4>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Jika mengubah gambar, sistem akan otomatis mengklasifikasikan ulang</li>
                    <li>Nama dan deskripsi akan diupdate otomatis jika dikosongkan</li>
                    <li>Anda bisa menambahkan informasi varietas dan asal secara manual</li>
                </ul>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Data
                </button>
                <a href="{{ route('coffee.show', $coffeeBean) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
