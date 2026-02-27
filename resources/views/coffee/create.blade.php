@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('coffee.index') }}" class="text-blue-500 hover:text-blue-700">← Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Klasifikasi Biji Kopi</h1>
        <p class="text-gray-600 mb-6">Upload gambar biji kopi untuk klasifikasi otomatis</p>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Upload Gambar Biji Kopi <span class="text-red-500">*</span>
                </label>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                    <input type="file" name="image" id="image" accept="image/*" 
                        class="hidden" 
                        required 
                        onchange="previewImage(event)">
                    
                    <div id="uploadPrompt" class="cursor-pointer" onclick="document.getElementById('image').click()">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-semibold text-blue-500">Klik untuk upload</span> atau drag & drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG (Max. 2MB)</p>
                    </div>
                    
                    <div id="imagePreview" class="hidden mt-4">
                        <img id="preview" class="max-w-full h-64 object-cover rounded mx-auto">
                        <p class="text-sm text-gray-600 mt-2" id="fileName"></p>
                        <button type="button" onclick="resetImage()" class="mt-2 text-sm text-red-500 hover:text-red-700">
                            Ganti Gambar
                        </button>
                    </div>
                </div>
                
                @error('image')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Cara Kerja Sistem
                </h4>
                <ol class="text-sm text-blue-700 space-y-1 ml-7 list-decimal">
                    <li>Upload gambar biji kopi</li>
                    <li>Sistem akan mengirim ke AI untuk analisis</li>
                    <li>AI mengklasifikasikan tingkat roasting (Green/Light/Medium/Dark)</li>
                    <li>Hasil klasifikasi dan deskripsi otomatis tersimpan</li>
                </ol>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">📸 Tips Foto yang Baik:</h4>
                <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                    <li>Gunakan pencahayaan yang cukup</li>
                    <li>Fokus pada biji kopi</li>
                    <li>Hindari bayangan yang berlebihan</li>
                    <li>Ambil dari jarak dekat untuk detail yang jelas</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">Tingkat Roasting yang Akan Diklasifikasi:</h4>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(\App\Helpers\RoastingHelper::getAllLevels() as $level)
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="text-2xl mr-2">{{ $level['icon'] }}</span>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm">{{ $level['name'] }}</span>
                                <p class="text-xs text-gray-600">{{ explode(' ', $level['description'])[0] }}...</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">
                    <a href="{{ route('coffee.roasting-info') }}" class="text-blue-500 hover:underline">
                        Pelajari lebih lanjut tentang tingkat roasting →
                    </a>
                </p>
            </div>

            <div class="flex gap-4">
                <button type="submit" id="submitBtn" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    Klasifikasi Sekarang
                </button>
                <a href="{{ route('coffee.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline">
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
    const uploadPrompt = document.getElementById('uploadPrompt');
    const fileName = document.getElementById('fileName');
    const file = event.target.files[0];
    
    if (file) {
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            resetImage();
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG.');
            resetImage();
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            fileName.textContent = file.name;
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function resetImage() {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const uploadPrompt = document.getElementById('uploadPrompt');
    
    imageInput.value = '';
    preview.src = '';
    uploadPrompt.classList.remove('hidden');
    previewContainer.classList.add('hidden');
}

// Drag and drop functionality
const uploadArea = document.querySelector('.border-dashed');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('image').files = files;
        previewImage({ target: { files: files } });
    }
}

// Loading state saat submit
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Sedang Mengklasifikasi...
    `;
});
</script>
@endsection
