@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        --ink: #0f0f0f;
        --ink-2: #3a3a3a;
        --ink-3: #7a7a7a;
        --ink-4: #b8b8b8;
        --paper: #fafaf8;
        --paper-2: #f2f1ee;
        --paper-3: #e8e6e1;
        --accent: #c8a96e;
        --accent-dark: #9d7c42;
        --danger: #c0392b;
        --success: #27ae60;
    }

    body { font-family: 'DM Sans', sans-serif; }
    .font-serif-display { font-family: 'Cormorant Garamond', serif; }
    .font-mono-custom   { font-family: 'JetBrains Mono', monospace; }

    @keyframes pulse-dot  { 0%,100%{opacity:1} 50%{opacity:0.35} }
    @keyframes fadeUp     { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:none} }
    @keyframes spin       { to{transform:rotate(360deg)} }
    @keyframes shimmer    { 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }

    .animate-pulse-dot { animation: pulse-dot 2s infinite; }
    .animate-fade-up   { animation: fadeUp 0.45s cubic-bezier(.22,.68,0,1.2) both; }
    .delay-1 { animation-delay:.07s }
    .delay-2 { animation-delay:.14s }
    .delay-3 { animation-delay:.21s }
    .animate-spin-slow { animation: spin 0.9s linear infinite; }

    /* mode card hover shine */
    .mode-card-shine {
        position:absolute;inset:0;pointer-events:none;
        background:linear-gradient(135deg,rgba(200,169,110,.09) 0%,transparent 55%);
        opacity:0;transition:opacity .3s;
    }
    .mode-card:hover .mode-card-shine { opacity:1; }

    /* number counter watermark */
    .card-watermark {
        position:absolute;bottom:-18px;right:10px;
        font-family:'Cormorant Garamond',serif;
        font-size:8rem;font-weight:600;
        color:rgba(0,0,0,.035);line-height:1;
        pointer-events:none;user-select:none;
        transition:color .3s;
    }
    .mode-card:hover .card-watermark { color:rgba(200,169,110,.08); }

    /* selected ring */
    .mode-card.selected {
        border-color: var(--ink) !important;
        box-shadow: 0 0 0 3px rgba(15,15,15,.07), 0 12px 40px rgba(0,0,0,.14);
    }

    /* drag-over */
    .drag-over { border-color:var(--accent)!important; background:#fdf9f3!important; }

    /* batch file list scroll */
    .batch-file-list { max-height:180px;overflow-y:auto; }
    .batch-file-list::-webkit-scrollbar { width:4px; }
    .batch-file-list::-webkit-scrollbar-thumb { background:var(--paper-3);border-radius:4px; }
</style>

<div class="min-h-screen" style="background:var(--paper)">

    {{-- ── TOPBAR ── --}}
    <div class="sticky top-0 z-40 flex items-center justify-between px-8 h-14 border-b"
         style="background:rgba(250,250,248,.92);backdrop-filter:blur(12px);border-color:var(--paper-3)">
        <a href="{{ route('coffee.index') }}"
           class="inline-flex items-center gap-2 text-[13px] transition-colors"
           style="color:var(--ink-3)" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink-3)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border text-[10px] font-medium tracking-widest uppercase font-mono-custom"
             style="background:var(--paper-2);border-color:var(--paper-3);color:var(--ink-3)">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse-dot" style="background:var(--accent)"></span>
            AI Online
        </div>
    </div>

    <div class="max-w-[1100px] mx-auto px-8 pt-14 pb-24">

        @if(session('error'))
        <div class="mb-8 px-5 py-3.5 rounded-xl text-[13px] animate-fade-up"
             style="background:#fdf2f2;border:1px solid #f5c6c6;color:#8b1a1a">
            {{ session('error') }}
        </div>
        @endif

        {{-- PAGE HEADER --}}
        <div class="mb-14 animate-fade-up">
            <p class="flex items-center gap-2.5 mb-4 text-[10px] font-medium tracking-[.2em] uppercase font-mono-custom"
               style="color:var(--accent)">
                <span class="inline-block w-6 h-px" style="background:var(--accent)"></span>
                Klasifikasi Biji Kopi
            </p>
            <h1 class="font-serif-display font-semibold leading-[1.05] mb-3"
                style="font-size:clamp(2.8rem,5vw,4.5rem);color:var(--ink)">
                Upload &amp; <em class="italic" style="color:var(--ink-4)">Analisis</em>
            </h1>
            <p class="text-sm font-light max-w-[400px] leading-relaxed" style="color:var(--ink-3)">
                Pilih mode prediksi, lalu unggah gambar untuk klasifikasi tingkat roasting secara otomatis.
            </p>
        </div>

        {{-- ════════════════════════════════
             MODE SELECTION
        ════════════════════════════════ --}}
        <div id="modeSelection" class="max-w-[900px]">

            <div class="mb-10 animate-fade-up delay-1">
                <h2 class="font-serif-display font-semibold text-[1.75rem] mb-1.5" style="color:var(--ink)">Pilih Mode Upload</h2>
                <p class="text-[13px] font-light" style="color:var(--ink-3)">Tersedia 3 cara untuk mengunggah gambar biji kopi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 animate-fade-up delay-2">

                {{-- ─ CARD 1: SINGLE ─ --}}
                <button type="button"
                        onclick="selectMode('single')"
                        class="mode-card relative overflow-hidden text-left rounded-[18px] p-7 cursor-pointer transition-all duration-200 border-[1.5px] outline-none group"
                        style="background:#fff;border-color:var(--paper-3)"
                        onmouseover="this.style.borderColor='var(--ink)';this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.11)'"
                        onmouseout="this.style.borderColor='var(--paper-3)';this.style.transform='';this.style.boxShadow=''">
                    <div class="mode-card-shine"></div>
                    <span class="card-watermark">1</span>

                    {{-- icon --}}
                    <div class="w-12 h-12 rounded-[14px] flex items-center justify-center mb-5 transition-transform duration-300 group-hover:scale-110"
                         style="background:var(--ink)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>

                    <h3 class="text-[15px] font-medium mb-2" style="color:var(--ink)">Gambar Tunggal</h3>
                    <p class="text-[12.5px] font-light leading-relaxed mb-4" style="color:var(--ink-3)">
                        Upload satu foto biji kopi untuk hasil klasifikasi instan dengan perbandingan kedua model AI.
                    </p>

                    {{-- tag --}}
                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-mono-custom" style="color:var(--ink-4)">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        1 gambar · Cepat &amp; Akurat
                    </span>

                    {{-- arrow indicator --}}
                    <div class="absolute bottom-6 right-6 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 translate-x-2"
                         style="background:var(--ink)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </button>

                {{-- ─ CARD 2: BATCH ─ --}}
                <button type="button"
                        onclick="selectMode('batch')"
                        class="mode-card relative overflow-hidden text-left rounded-[18px] p-7 cursor-pointer transition-all duration-200 border-[1.5px] outline-none group"
                        style="background:#fff;border-color:var(--paper-3)"
                        onmouseover="this.style.borderColor='var(--ink)';this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.11)'"
                        onmouseout="this.style.borderColor='var(--paper-3)';this.style.transform='';this.style.boxShadow=''">
                    <div class="mode-card-shine"></div>
                    <span class="card-watermark">2</span>

                    <div class="w-12 h-12 rounded-[14px] flex items-center justify-center mb-5 transition-transform duration-300 group-hover:scale-110"
                         style="background:var(--ink)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                            <line x1="12" y1="12" x2="12" y2="16"/>
                            <line x1="10" y1="14" x2="14" y2="14"/>
                        </svg>
                    </div>

                    <h3 class="text-[15px] font-medium mb-2" style="color:var(--ink)">Batch File</h3>
                    <p class="text-[12.5px] font-light leading-relaxed mb-4" style="color:var(--ink-3)">
                        Upload beberapa gambar sekaligus dari file manual. Cocok untuk evaluasi sejumlah sampel tertentu.
                    </p>

                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-mono-custom" style="color:var(--ink-4)">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Banyak file · Pilih manual
                    </span>

                    <div class="absolute bottom-6 right-6 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 translate-x-2"
                         style="background:var(--ink)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </button>

                {{-- ─ CARD 3: FOLDER ZIP ─ --}}
                <button type="button"
                        onclick="selectMode('folder')"
                        class="mode-card relative overflow-hidden text-left rounded-[18px] p-7 cursor-pointer transition-all duration-200 border-[1.5px] outline-none group"
                        style="background:var(--ink);border-color:var(--ink)"
                        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.28)'"
                        onmouseout="this.style.transform='';this.style.boxShadow=''">
                    {{-- dot grid --}}
                    <div class="absolute inset-0 pointer-events-none"
                         style="background-image:radial-gradient(circle,rgba(255,255,255,.055) 1px,transparent 1px);background-size:20px 20px"></div>
                    {{-- accent glow ring --}}
                    <div class="absolute -bottom-10 -right-10 w-36 h-36 rounded-full pointer-events-none"
                         style="border:1px solid rgba(200,169,110,.2)"></div>
                    <div class="absolute -bottom-16 -right-16 w-52 h-52 rounded-full pointer-events-none"
                         style="border:1px solid rgba(200,169,110,.08)"></div>

                    <span class="card-watermark" style="color:rgba(255,255,255,.04)">3</span>

                    <div class="relative">
                        <div class="w-12 h-12 rounded-[14px] flex items-center justify-center mb-5 transition-transform duration-300 group-hover:scale-110"
                             style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="1.5">
                                <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                                <path d="M12 11v6m-3-3h6"/>
                            </svg>
                        </div>

                        <h3 class="text-[15px] font-medium mb-2" style="color:#fff">Folder ZIP</h3>
                        <p class="text-[12.5px] font-light leading-relaxed mb-4" style="color:rgba(255,255,255,.45)">
                            Upload file ZIP berisi folder gambar. Jika terstruktur per kelas, confusion matrix digenerate otomatis.
                        </p>

                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-mono-custom" style="color:rgba(200,169,110,.7)">
                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Dataset besar · Auto label
                        </span>

                        <div class="absolute bottom-0 right-0 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 translate-x-2"
                             style="background:var(--accent)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>
                </button>

            </div>{{-- /mode-grid --}}

            {{-- Feature strip --}}
            <div class="mt-8 flex flex-wrap gap-2 animate-fade-up delay-3">
                @foreach([
                    ['Dua model paralel','MobileNetV3 Small &amp; Large'],
                    ['Analisis real-time','Hasil dalam hitungan detik'],
                    ['Confusion Matrix','Auto generate untuk dataset'],
                ] as [$title, $sub])
                <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-[12px]"
                     style="background:#fff;border-color:var(--paper-3)">
                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:var(--accent)"></span>
                    <span class="font-medium" style="color:var(--ink)">{{ $title }}</span>
                    <span class="hidden sm:inline" style="color:var(--ink-4)">·</span>
                    <span class="hidden sm:inline text-[11px] font-light" style="color:var(--ink-3)">{!! $sub !!}</span>
                </div>
                @endforeach
            </div>

        </div>{{-- /modeSelection --}}

        {{-- ════════════════════════════════
             UPLOAD SECTION
        ════════════════════════════════ --}}
        <div id="uploadSection" class="hidden">
            <div class="grid gap-5" style="grid-template-columns:1fr 380px;align-items:start">

                {{-- LEFT PANEL --}}
                <div class="rounded-[20px] p-8 border" style="background:#fff;border-color:var(--paper-3)">

                    <button type="button" onclick="backToModeSelection()"
                            class="inline-flex items-center gap-1.5 mb-6 text-[11.5px] transition-colors"
                            style="color:var(--ink-4);background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;padding:0"
                            onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink-4)'">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Kembali ke Pilihan Mode
                    </button>

                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border mb-5 text-[9.5px] font-medium tracking-[.08em] uppercase font-mono-custom"
                         style="background:var(--paper);border-color:var(--paper-3);color:var(--ink-3)">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                        <span id="modeIndicatorText">—</span>
                    </div>

                    <p class="flex items-center gap-2 mb-6 text-[9.5px] font-medium tracking-[.16em] uppercase font-mono-custom" style="color:var(--ink-4)">
                        <span class="w-4 h-px" style="background:var(--paper-3)"></span>
                        <span id="panelLabelText">Upload Gambar</span>
                    </p>

                    {{-- FORM SINGLE --}}
                    <form id="formSingle" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="hidden" name="mode" value="single">
                        <div class="rounded-[14px] border-2 border-dashed cursor-pointer transition-all duration-200 overflow-hidden"
                             id="dropZoneSingle" style="border-color:var(--paper-3)"
                             onclick="document.getElementById('inputSingle').click()"
                             onmouseover="this.style.borderColor='var(--accent)';this.style.background='#fdf9f3'"
                             onmouseout="if(!this.classList.contains('drag-over')){this.style.borderColor='var(--paper-3)';this.style.background=''}">
                            <input type="file" id="inputSingle" name="image" accept="image/jpeg,image/png,image/jpg" class="hidden" required onchange="handleSingle(event)">
                            <div id="promptSingle" class="flex flex-col items-center text-center py-14 px-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                <p class="font-serif-display font-semibold text-xl mb-1.5" style="color:var(--ink)">Seret &amp; Lepas Gambar</p>
                                <p class="text-[13px] font-light mb-3" style="color:var(--ink-3)">atau <span class="underline underline-offset-2 font-medium" style="color:var(--ink)">pilih file gambar</span></p>
                                <p class="text-[10.5px] tracking-[.06em] font-mono-custom" style="color:var(--ink-4)">PNG · JPG · JPEG · MAKS 2MB</p>
                            </div>
                            <div id="previewSingle" class="hidden p-4">
                                <img id="previewImgSingle" src="" alt="Preview" class="w-full rounded-[10px] border object-cover" style="height:280px;border-color:var(--paper-3)">
                                <div class="flex items-center justify-between mt-3 px-1">
                                    <span id="fileNameSingle" class="text-[12px] font-light" style="color:var(--ink-3)"></span>
                                    <button type="button" class="text-[11px] font-medium transition-colors" style="color:var(--ink-3);background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif" onclick="event.stopPropagation();resetSingle()" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink-3)'">Ganti</button>
                                </div>
                            </div>
                        </div>
                        @error('image') <p class="text-[11px] mt-2" style="color:var(--danger)">{{ $message }}</p> @enderror

                        <div class="grid grid-cols-2 gap-2 mt-5">
                            @foreach(['Pencahayaan yang cukup','Fokus pada biji kopi','Hindari bayangan berlebih','Ambil dari jarak dekat'] as $tip)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border text-[11.5px] font-light" style="background:var(--paper);border-color:var(--paper-2);color:var(--ink-3)">
                                <span class="w-1 h-1 rounded-full flex-shrink-0" style="background:var(--accent)"></span>{{ $tip }}
                            </div>
                            @endforeach
                        </div>
                        <div class="flex flex-col gap-2.5 mt-6">
                            <button type="submit" id="submitSingle" class="w-full h-12 rounded-xl flex items-center justify-center gap-2 text-[13.5px] font-medium tracking-[.01em] transition-all duration-200 text-white" style="background:var(--ink);font-family:'DM Sans',sans-serif" onmouseover="this.style.background='#2a2a2a';this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.18)'" onmouseout="this.style.background='var(--ink)';this.style.transform='';this.style.boxShadow=''">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                Klasifikasi Sekarang
                            </button>
                            <a href="{{ route('coffee.index') }}" class="w-full h-10 rounded-xl flex items-center justify-center text-[13px] font-light border transition-all duration-200" style="color:var(--ink-3);border-color:var(--paper-3)" onmouseover="this.style.background='var(--paper)';this.style.color='var(--ink)'" onmouseout="this.style.background='';this.style.color='var(--ink-3)'">Batalkan</a>
                        </div>
                    </form>

                    {{-- FORM BATCH --}}
                    <form id="formBatch" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="hidden" name="mode" value="batch">
                        <div class="rounded-[14px] border-2 border-dashed cursor-pointer transition-all duration-200 overflow-hidden"
                             id="dropZoneBatch" style="border-color:var(--paper-3)"
                             onclick="document.getElementById('inputBatch').click()"
                             onmouseover="this.style.borderColor='var(--accent)';this.style.background='#fdf9f3'"
                             onmouseout="if(!this.classList.contains('drag-over')){this.style.borderColor='var(--paper-3)';this.style.background=''}">
                            <input type="file" id="inputBatch" name="image[]" accept="image/jpeg,image/png,image/jpg" multiple class="hidden" required onchange="handleBatch(event)">
                            <div id="promptBatch" class="flex flex-col items-center text-center py-14 px-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                <p class="font-serif-display font-semibold text-xl mb-1.5" style="color:var(--ink)">Pilih Beberapa Gambar</p>
                                <p class="text-[13px] font-light mb-3" style="color:var(--ink-3)">atau <span class="underline underline-offset-2 font-medium" style="color:var(--ink)">pilih banyak file</span></p>
                                <p class="text-[10.5px] tracking-[.06em] font-mono-custom" style="color:var(--ink-4)">PNG · JPG · JPEG · Bisa pilih banyak sekaligus</p>
                            </div>
                            <div id="previewBatch" class="hidden">
                                <div class="flex flex-col items-center text-center gap-3 pt-8 pb-4 px-8">
                                    <span class="font-serif-display font-semibold leading-none" style="font-size:3.5rem;color:var(--ink)" id="batchCount">0</span>
                                    <span class="text-[13px] font-light" style="color:var(--ink-3)">gambar dipilih</span>
                                    <button type="button" onclick="event.stopPropagation();resetBatch()" class="text-[11px] font-medium px-3.5 py-1.5 rounded-full border transition-all duration-200" style="color:var(--accent);border-color:var(--accent);background:none;cursor:pointer;font-family:'DM Sans',sans-serif" onmouseover="this.style.background='var(--accent)';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='var(--accent)'">Pilih Ulang</button>
                                </div>
                                <div id="batchFileList" class="batch-file-list mx-4 mb-4 rounded-[10px] border" style="border-color:var(--paper-3)"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-5">
                            @foreach(['Pilih semua gambar sekaligus','Format JPG/PNG/JPEG','Setiap gambar diproses terpisah','Hasil disimpan per gambar'] as $tip)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border text-[11.5px] font-light" style="background:var(--paper);border-color:var(--paper-2);color:var(--ink-3)">
                                <span class="w-1 h-1 rounded-full flex-shrink-0" style="background:var(--accent)"></span>{{ $tip }}
                            </div>
                            @endforeach
                        </div>
                        <div class="flex flex-col gap-2.5 mt-6">
                            <button type="submit" id="submitBatch" class="w-full h-12 rounded-xl flex items-center justify-center gap-2 text-[13.5px] font-medium text-white transition-all duration-200" style="background:var(--ink);font-family:'DM Sans',sans-serif" onmouseover="this.style.background='#2a2a2a';this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.18)'" onmouseout="this.style.background='var(--ink)';this.style.transform='';this.style.boxShadow=''">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                Klasifikasi Batch
                            </button>
                            <a href="{{ route('coffee.index') }}" class="w-full h-10 rounded-xl flex items-center justify-center text-[13px] font-light border transition-all duration-200" style="color:var(--ink-3);border-color:var(--paper-3)" onmouseover="this.style.background='var(--paper)';this.style.color='var(--ink)'" onmouseout="this.style.background='';this.style.color='var(--ink-3)'">Batalkan</a>
                        </div>
                    </form>

                    {{-- FORM FOLDER --}}
                    <form id="formFolder" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="hidden" name="mode" value="folder">
                        <div class="rounded-[14px] border-2 border-dashed cursor-pointer transition-all duration-200 overflow-hidden"
                             id="dropZoneFolder" style="border-color:var(--paper-3)"
                             onclick="document.getElementById('inputFolder').click()"
                             onmouseover="this.style.borderColor='var(--accent)';this.style.background='#fdf9f3'"
                             onmouseout="if(!this.classList.contains('drag-over')){this.style.borderColor='var(--paper-3)';this.style.background=''}">
                            <input type="file" id="inputFolder" name="folder" accept=".zip" class="hidden" required onchange="handleFolder(event)">
                            <div id="promptFolder" class="flex flex-col items-center text-center py-14 px-8">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                <p class="font-serif-display font-semibold text-xl mb-1.5" style="color:var(--ink)">Upload File ZIP</p>
                                <p class="text-[13px] font-light mb-3" style="color:var(--ink-3)">atau <span class="underline underline-offset-2 font-medium" style="color:var(--ink)">pilih file .zip</span></p>
                                <p class="text-[10.5px] tracking-[.06em] font-mono-custom" style="color:var(--ink-4)">ZIP · Struktur flat atau per kelas</p>
                            </div>
                            <div id="previewFolder" class="hidden">
                                <div class="flex flex-col items-center text-center gap-2.5 py-8 px-8">
                                    <div class="w-16 h-16 rounded-[16px] flex items-center justify-center border" style="background:linear-gradient(135deg,#f8f3ea,#ede4d3);border-color:var(--paper-3)">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                                    </div>
                                    <span id="zipFileName" class="text-[14px] font-medium" style="color:var(--ink)">—</span>
                                    <span id="zipFileSize" class="text-[11px] font-light" style="color:var(--ink-3)">—</span>
                                    <button type="button" onclick="event.stopPropagation();resetFolder()" class="text-[11px] font-medium px-3.5 py-1.5 rounded-full border mt-1.5 transition-all duration-200" style="color:var(--accent);border-color:var(--accent);background:none;cursor:pointer;font-family:'DM Sans',sans-serif" onmouseover="this.style.background='var(--accent)';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='var(--accent)'">Ganti File</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 rounded-xl border" style="background:var(--paper);border-color:var(--paper-3)">
                            <p class="text-[10.5px] font-medium mb-3 font-mono-custom tracking-[.08em]" style="color:var(--ink-3)">STRUKTUR ZIP YANG DIDUKUNG</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-medium mb-1.5 font-mono-custom" style="color:var(--accent)">FLAT (tanpa label)</p>
                                    <pre class="text-[10px] leading-[1.8] font-mono-custom" style="color:var(--ink-3)">images.zip
├── img1.jpg
├── img2.jpg
└── img3.png</pre>
                                </div>
                                <div>
                                    <p class="text-[10px] font-medium mb-1.5 font-mono-custom" style="color:var(--success)">PER KELAS (auto label ✓)</p>
                                    <pre class="text-[10px] leading-[1.8] font-mono-custom" style="color:var(--ink-3)">dataset.zip
├── Dark/
├── Green/
├── Light/
└── Medium/</pre>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2.5 mt-6">
                            <button type="submit" id="submitFolder" class="w-full h-12 rounded-xl flex items-center justify-center gap-2 text-[13.5px] font-medium text-white transition-all duration-200" style="background:var(--ink);font-family:'DM Sans',sans-serif" onmouseover="this.style.background='#2a2a2a';this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.18)'" onmouseout="this.style.background='var(--ink)';this.style.transform='';this.style.boxShadow=''">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                Klasifikasi Folder
                            </button>
                            <a href="{{ route('coffee.index') }}" class="w-full h-10 rounded-xl flex items-center justify-center text-[13px] font-light border transition-all duration-200" style="color:var(--ink-3);border-color:var(--paper-3)" onmouseover="this.style.background='var(--paper)';this.style.color='var(--ink)'" onmouseout="this.style.background='';this.style.color='var(--ink-3)'">Batalkan</a>
                        </div>
                    </form>

                </div>{{-- /left panel --}}

                {{-- RIGHT COLUMN --}}
                <div class="flex flex-col gap-4">

                    {{-- Dark card --}}
                    <div class="rounded-[20px] p-7 relative overflow-hidden" style="background:var(--ink)">
                        <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(circle,rgba(255,255,255,.055) 1px,transparent 1px);background-size:20px 20px"></div>
                        <div class="absolute -bottom-10 -right-10 w-36 h-36 rounded-full pointer-events-none" style="border:1px solid rgba(255,255,255,.06)"></div>
                        <div class="absolute -bottom-16 -right-16 w-52 h-52 rounded-full pointer-events-none" style="border:1px solid rgba(255,255,255,.03)"></div>
                        <div class="relative">
                            <p class="flex items-center gap-2 mb-4 text-[9px] font-medium tracking-[.16em] uppercase font-mono-custom" style="color:rgba(255,255,255,.3)">
                                <span class="w-3.5 h-px" style="background:rgba(255,255,255,.15)"></span>Sistem AI
                            </p>
                            <h2 class="font-serif-display font-semibold leading-[1.3] mb-2.5" style="font-size:1.6rem;color:#fff">
                                Presisi tinggi untuk<br>Setiap <span class="italic" style="color:rgba(255,255,255,.35)">roasting</span>
                            </h2>
                            <p class="text-[12.5px] font-light leading-relaxed" style="color:rgba(255,255,255,.4)">Model deep learning terlatih dengan ribuan gambar biji kopi. Identifikasi instan, akurasi tinggi dengan dua model paralel.</p>
                        </div>
                    </div>

                    {{-- Steps --}}
                    <div class="rounded-[20px] p-6 border" style="background:#fff;border-color:var(--paper-3)">
                        <p class="flex items-center gap-2 mb-4 text-[9.5px] font-medium tracking-[.14em] uppercase font-mono-custom" style="color:var(--ink-4)">
                            <span class="w-3.5 h-px" style="background:var(--paper-3)"></span>Cara Kerja
                        </p>
                        <div>
                            @foreach([
                                ['01','Upload gambar/folder','Pilih mode yang sesuai kebutuhan'],
                                ['02','Dikirim ke Flask API','Analisis gambar secara real-time'],
                                ['03','Dua model bekerja','MobileNetV3 Small & Large'],
                                ['04','Hasil tersimpan','Lengkap dengan perbandingan model'],
                            ] as [$n,$t,$s])
                            <div class="flex items-start gap-3.5 py-3 border-b last:border-b-0" style="border-color:var(--paper-2)">
                                <span class="text-[10px] font-medium w-5 flex-shrink-0 mt-0.5 font-mono-custom" style="color:var(--ink-4)">{{ $n }}</span>
                                <div>
                                    <p class="text-[12.5px] font-medium mb-0.5" style="color:var(--ink)">{{ $t }}</p>
                                    <p class="text-[11px] font-light" style="color:var(--ink-3)">{{ $s }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mode comparison --}}
                    <div class="rounded-[20px] p-6 border" style="background:#fff;border-color:var(--paper-3)">
                        <p class="flex items-center gap-2 mb-4 text-[9.5px] font-medium tracking-[.14em] uppercase font-mono-custom" style="color:var(--ink-4)">
                            <span class="w-3.5 h-px" style="background:var(--paper-3)"></span>Perbandingan Mode
                        </p>
                        <table class="w-full border-collapse" style="font-size:11.5px">
                            <thead>
                                <tr>
                                    <th class="text-left pb-2 font-normal font-mono-custom text-[9.5px] tracking-[.08em]" style="color:var(--ink-4)">MODE</th>
                                    <th class="text-center pb-2 font-normal font-mono-custom text-[9.5px]" style="color:var(--ink-4)">LABEL</th>
                                    <th class="text-center pb-2 font-normal font-mono-custom text-[9.5px]" style="color:var(--ink-4)">CM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach([['Single','—','—'],['Batch','Manual','✓'],['Folder ZIP','Otomatis','✓']] as [$m,$l,$c])
                                <tr style="border-top:1px solid var(--paper-2)">
                                    <td class="py-2.5 font-medium" style="color:var(--ink)">{{ $m }}</td>
                                    <td class="py-2.5 text-center" style="color:var(--ink-3)">{{ $l }}</td>
                                    <td class="py-2.5 text-center text-[13px]" style="color:{{ $c==='✓' ? 'var(--success)' : 'var(--ink-4)' }}">{{ $c }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="mt-2.5 text-[10px]" style="color:var(--ink-4)">CM = Confusion Matrix</p>
                    </div>

                </div>{{-- /right col --}}
            </div>
        </div>{{-- /uploadSection --}}

    </div>
</div>

<script>
let currentMode = null;

function selectMode(mode) {
    currentMode = mode;
    document.getElementById('modeSelection').style.display = 'none';
    const sec = document.getElementById('uploadSection');
    sec.classList.remove('hidden');

    document.getElementById('formSingle').classList.toggle('hidden', mode !== 'single');
    document.getElementById('formBatch').classList.toggle('hidden',  mode !== 'batch');
    document.getElementById('formFolder').classList.toggle('hidden', mode !== 'folder');

    const labels = {
        single: { indicator: 'Mode: Gambar Tunggal', panel: 'Upload Gambar Tunggal' },
        batch:  { indicator: 'Mode: Batch File',     panel: 'Upload Beberapa Gambar' },
        folder: { indicator: 'Mode: Folder ZIP',     panel: 'Upload Folder (ZIP)' },
    };
    document.getElementById('modeIndicatorText').textContent = labels[mode].indicator;
    document.getElementById('panelLabelText').textContent    = labels[mode].panel;

    setTimeout(() => sec.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80);
}

function backToModeSelection() {
    currentMode = null;
    document.getElementById('modeSelection').style.display = 'block';
    document.getElementById('uploadSection').classList.add('hidden');
    resetSingle(); resetBatch(); resetFolder();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function handleSingle(e) {
    const file = e.target.files[0]; if (!file) return;
    if (file.size > 2*1024*1024) { alert('Ukuran file melebihi 2MB!'); resetSingle(); return; }
    if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) { alert('Format tidak valid!'); resetSingle(); return; }
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('previewImgSingle').src = ev.target.result;
        document.getElementById('fileNameSingle').textContent = file.name;
        document.getElementById('promptSingle').classList.add('hidden');
        document.getElementById('previewSingle').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
function resetSingle() {
    document.getElementById('inputSingle').value = '';
    document.getElementById('promptSingle').classList.remove('hidden');
    document.getElementById('previewSingle').classList.add('hidden');
}

function handleBatch(e) {
    const files = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
    if (!files.length) { alert('Tidak ada gambar valid!'); resetBatch(); return; }
    document.getElementById('batchCount').textContent = files.length;
    const list = document.getElementById('batchFileList');
    list.innerHTML = files.slice(0,20).map(f =>
        `<div style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-bottom:1px solid var(--paper-2);font-size:11.5px;color:var(--ink-3)">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>${f.name}</span></div>`
    ).join('') + (files.length > 20 ? `<div style="padding:8px 12px;font-size:11.5px;color:var(--ink-4)">...dan ${files.length-20} file lainnya</div>` : '');
    document.getElementById('promptBatch').classList.add('hidden');
    document.getElementById('previewBatch').classList.remove('hidden');
}
function resetBatch() {
    document.getElementById('inputBatch').value = '';
    document.getElementById('batchCount').textContent = '0';
    document.getElementById('batchFileList').innerHTML = '';
    document.getElementById('promptBatch').classList.remove('hidden');
    document.getElementById('previewBatch').classList.add('hidden');
}

function handleFolder(e) {
    const file = e.target.files[0]; if (!file) return;
    if (!file.name.toLowerCase().endsWith('.zip')) { alert('Harus file .zip!'); resetFolder(); return; }
    document.getElementById('zipFileName').textContent = file.name;
    document.getElementById('zipFileSize').textContent = formatBytes(file.size);
    document.getElementById('promptFolder').classList.add('hidden');
    document.getElementById('previewFolder').classList.remove('hidden');
}
function resetFolder() {
    document.getElementById('inputFolder').value = '';
    document.getElementById('zipFileName').textContent = '—';
    document.getElementById('zipFileSize').textContent = '—';
    document.getElementById('promptFolder').classList.remove('hidden');
    document.getElementById('previewFolder').classList.add('hidden');
}
function formatBytes(b) {
    if (b<1024) return b+' B';
    if (b<1024*1024) return (b/1024).toFixed(1)+' KB';
    return (b/(1024*1024)).toFixed(2)+' MB';
}

['Single','Batch','Folder'].forEach(type => {
    const zone = document.getElementById('dropZone'+type);
    if (!zone) return;
    ['dragenter','dragover','dragleave','drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); }));
    zone.addEventListener('dragenter', () => { zone.classList.add('drag-over'); zone.style.borderColor='var(--accent)'; zone.style.background='#fdf9f3'; });
    zone.addEventListener('dragover',  () => { zone.classList.add('drag-over'); zone.style.borderColor='var(--accent)'; zone.style.background='#fdf9f3'; });
    zone.addEventListener('dragleave', () => { zone.classList.remove('drag-over'); zone.style.borderColor='var(--paper-3)'; zone.style.background=''; });
    zone.addEventListener('drop', e => {
        zone.classList.remove('drag-over'); zone.style.borderColor='var(--paper-3)'; zone.style.background='';
        const files = e.dataTransfer.files; if (!files.length) return;
        if (type==='Single') { document.getElementById('inputSingle').files=files; handleSingle({target:{files}}); }
        else if (type==='Batch') { document.getElementById('inputBatch').files=files; handleBatch({target:{files}}); }
        else { document.getElementById('inputFolder').files=files; handleFolder({target:{files}}); }
    });
});

[['formSingle','submitSingle'],['formBatch','submitBatch'],['formFolder','submitFolder']].forEach(([fId,bId]) => {
    const form = document.getElementById(fId);
    if (!form) return;
    form.addEventListener('submit', () => {
        const btn = document.getElementById(bId);
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin-slow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0110 10"/></svg> Menganalisis...`;
    });
});
</script>

@endsection