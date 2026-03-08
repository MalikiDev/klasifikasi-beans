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

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--paper);
        font-family: 'DM Sans', sans-serif;
        color: var(--ink);
    }

    /* ── TOPBAR ── */
    .topbar {
        position: sticky; top: 0; z-index: 40;
        background: rgba(250,250,248,0.92);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--paper-3);
        height: 56px;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 2rem;
    }
    .topbar-back {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; color: var(--ink-3); text-decoration: none;
        font-weight: 400; letter-spacing: 0.01em;
        transition: color 0.2s;
    }
    .topbar-back:hover { color: var(--ink); }
    .topbar-badge {
        display: flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 999px;
        border: 1px solid var(--paper-3);
        background: var(--paper-2);
        font-size: 10px; font-weight: 500; color: var(--ink-3);
        letter-spacing: 0.12em; text-transform: uppercase;
        font-family: 'JetBrains Mono', monospace;
    }
    .topbar-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--accent); animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

    /* ── LAYOUT ── */
    .page-wrap { max-width: 1100px; margin: 0 auto; padding: 3.5rem 2rem 5rem; }

    /* ── PAGE HEADER ── */
    .page-header { margin-bottom: 3.5rem; }
    .page-eyebrow {
        font-size: 10px; font-weight: 500; letter-spacing: 0.2em;
        text-transform: uppercase; color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: 10px;
    }
    .page-eyebrow::before {
        content: ''; display: inline-block;
        width: 24px; height: 1px; background: var(--accent);
    }
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.8rem, 5vw, 4.5rem);
        font-weight: 600; line-height: 1.05;
        color: var(--ink); margin-bottom: 1rem;
    }
    .page-title em { font-style: italic; color: var(--ink-4); }
    .page-subtitle {
        font-size: 14px; font-weight: 300; color: var(--ink-3);
        line-height: 1.7; max-width: 420px;
    }

    /* ── ERROR ALERT ── */
    .alert-error {
        margin-bottom: 2rem; padding: 14px 18px;
        background: #fdf2f2; border: 1px solid #f5c6c6;
        border-radius: 10px; font-size: 13px; color: #8b1a1a;
    }

    /* ── MODE SELECTION ── */
    #modeSelection { max-width: 900px; }
    .mode-heading { margin-bottom: 2.5rem; }
    .mode-heading h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem; font-weight: 600; color: var(--ink);
        margin-bottom: 6px;
    }
    .mode-heading p { font-size: 13px; color: var(--ink-3); font-weight: 300; }

    .mode-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 768px) { .mode-grid { grid-template-columns: 1fr; } }

    .mode-card {
        position: relative; overflow: hidden;
        border: 1.5px solid var(--paper-3);
        border-radius: 18px; padding: 28px 24px;
        background: #fff; cursor: pointer; text-align: left;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.2s;
        outline: none;
    }
    .mode-card:hover {
        border-color: var(--ink);
        box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        transform: translateY(-2px);
    }
    .mode-card-shine {
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(200,169,110,0.07) 0%, transparent 60%);
        opacity: 0; transition: opacity 0.3s;
        pointer-events: none;
    }
    .mode-card:hover .mode-card-shine { opacity: 1; }

    .mode-icon {
        width: 48px; height: 48px; border-radius: 14px;
        background: var(--ink); display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px; transition: transform 0.3s;
    }
    .mode-card:hover .mode-icon { transform: scale(1.08); }

    .mode-card h3 {
        font-size: 16px; font-weight: 500; color: var(--ink);
        margin-bottom: 8px;
    }
    .mode-card p {
        font-size: 12.5px; color: var(--ink-3); line-height: 1.65;
        font-weight: 300; margin-bottom: 16px;
    }
    .mode-tag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 10.5px; color: var(--ink-4);
        font-family: 'JetBrains Mono', monospace;
    }
    .mode-tag svg { flex-shrink: 0; }

    .mode-check {
        position: absolute; top: 16px; right: 16px;
        width: 22px; height: 22px; border-radius: 50%;
        border: 1.5px solid var(--paper-3);
        transition: all 0.25s; display: flex; align-items: center; justify-content: center;
    }
    .mode-card:hover .mode-check {
        border-color: var(--ink); background: var(--ink);
    }
    .mode-check-inner {
        opacity: 0; transition: opacity 0.2s;
    }
    .mode-card:hover .mode-check-inner { opacity: 1; }

    /* ── UPLOAD SECTION ── */
    #uploadSection { display: none; }
    #uploadSection.active {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 20px; align-items: start;
    }
    @media (max-width: 900px) {
        #uploadSection.active { grid-template-columns: 1fr; }
    }

    /* ── LEFT PANEL ── */
    .upload-panel {
        border: 1px solid var(--paper-3); border-radius: 20px;
        padding: 32px; background: #fff;
    }
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 11.5px; color: var(--ink-4); background: none; border: none;
        cursor: pointer; padding: 0; margin-bottom: 24px;
        transition: color 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .back-btn:hover { color: var(--ink); }

    .panel-label {
        font-size: 9.5px; font-weight: 500; letter-spacing: 0.16em;
        text-transform: uppercase; color: var(--ink-4);
        font-family: 'JetBrains Mono', monospace;
        display: flex; align-items: center; gap: 8px; margin-bottom: 24px;
    }
    .panel-label::before {
        content: ''; width: 16px; height: 1px; background: var(--paper-3);
    }

    /* ── DROP ZONE ── */
    .drop-zone {
        border: 2px dashed var(--paper-3); border-radius: 14px;
        cursor: pointer; transition: border-color 0.2s, background 0.2s;
        overflow: hidden;
    }
    .drop-zone:hover, .drop-zone.drag-over {
        border-color: var(--accent); background: #fdf9f3;
    }
    .drop-empty {
        padding: 3.5rem 2rem; display: flex; flex-direction: column;
        align-items: center; text-align: center;
    }
    .drop-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: var(--ink); display: flex; align-items: center; justify-content: center;
        margin-bottom: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .drop-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem; font-weight: 600; color: var(--ink); margin-bottom: 6px;
    }
    .drop-sub { font-size: 13px; color: var(--ink-3); font-weight: 300; }
    .drop-sub span { color: var(--ink); font-weight: 500; text-decoration: underline; text-underline-offset: 3px; }
    .drop-hint {
        font-size: 10.5px; color: var(--ink-4); margin-top: 14px;
        font-family: 'JetBrains Mono', monospace; letter-spacing: 0.06em;
    }

    .drop-preview { padding: 16px; }
    .drop-preview img {
        width: 100%; height: 280px; object-fit: cover;
        border-radius: 10px; border: 1px solid var(--paper-3);
    }
    .drop-preview-meta {
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 12px; padding: 0 4px;
    }
    .drop-preview-name { font-size: 12px; color: var(--ink-3); font-weight: 300; }
    .drop-preview-change {
        font-size: 11px; font-weight: 500; color: var(--ink-3);
        background: none; border: none; cursor: pointer; padding: 0;
        transition: color 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .drop-preview-change:hover { color: var(--ink); }

    /* Batch count display */
    .batch-count-display {
        padding: 2.5rem 2rem; display: flex; flex-direction: column;
        align-items: center; text-align: center; gap: 12px;
    }
    .batch-count-badge {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3.5rem; font-weight: 600; color: var(--ink);
        line-height: 1;
    }
    .batch-count-label { font-size: 13px; color: var(--ink-3); font-weight: 300; }
    .batch-count-change {
        font-size: 11px; color: var(--accent); font-weight: 500;
        background: none; border: none; cursor: pointer; padding: 6px 14px;
        border: 1px solid var(--accent); border-radius: 999px;
        transition: all 0.2s; font-family: 'DM Sans', sans-serif; margin-top: 4px;
    }
    .batch-count-change:hover { background: var(--accent); color: #fff; }

    /* ZIP display */
    .zip-display {
        padding: 2.5rem 2rem; display: flex; flex-direction: column;
        align-items: center; text-align: center; gap: 10px;
    }
    .zip-icon {
        width: 64px; height: 64px; border-radius: 16px;
        background: linear-gradient(135deg, #f8f3ea, #ede4d3);
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--paper-3);
    }
    .zip-name { font-size: 14px; font-weight: 500; color: var(--ink); margin-top: 4px; }
    .zip-size { font-size: 11px; color: var(--ink-3); font-weight: 300; }
    .zip-change {
        font-size: 11px; color: var(--accent); font-weight: 500;
        background: none; cursor: pointer; padding: 6px 14px;
        border: 1px solid var(--accent); border-radius: 999px;
        transition: all 0.2s; font-family: 'DM Sans', sans-serif; margin-top: 6px;
    }
    .zip-change:hover { background: var(--accent); color: #fff; }

    /* ── TIPS ── */
    .tips-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 20px; }
    .tip-item {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 12px; background: var(--paper); border-radius: 8px;
        border: 1px solid var(--paper-2); font-size: 11.5px;
        color: var(--ink-3); font-weight: 300;
    }
    .tip-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--accent); flex-shrink: 0; }

    /* ── ACTION BUTTONS ── */
    .action-group { margin-top: 24px; display: flex; flex-direction: column; gap: 10px; }
    .btn-primary {
        width: 100%; height: 48px;
        background: var(--ink); color: #fff;
        border: none; border-radius: 12px;
        font-size: 13.5px; font-weight: 500; font-family: 'DM Sans', sans-serif;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        letter-spacing: 0.01em;
    }
    .btn-primary:hover {
        background: #2a2a2a; transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
    }
    .btn-primary:active { transform: scale(0.99); }
    .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; box-shadow: none; }

    .btn-cancel {
        width: 100%; height: 40px;
        background: none; border: 1px solid var(--paper-3);
        border-radius: 12px; font-size: 13px; font-weight: 300;
        color: var(--ink-3); cursor: pointer; font-family: 'DM Sans', sans-serif;
        text-decoration: none; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s, color 0.2s;
    }
    .btn-cancel:hover { background: var(--paper); color: var(--ink); }

    /* ── RIGHT PANELS ── */
    .right-col { display: flex; flex-direction: column; gap: 16px; }

    .dark-card {
        border-radius: 20px; background: var(--ink);
        padding: 28px; position: relative; overflow: hidden;
    }
    .dark-card-dots {
        position: absolute; inset: 0; pointer-events: none;
        background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    .dark-card-ring1 {
        position: absolute; bottom: -40px; right: -40px;
        width: 140px; height: 140px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .dark-card-ring2 {
        position: absolute; bottom: -70px; right: -70px;
        width: 200px; height: 200px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.03);
    }
    .dark-card-eyebrow {
        font-size: 9px; font-weight: 500; letter-spacing: 0.16em;
        text-transform: uppercase; color: rgba(255,255,255,0.3);
        font-family: 'JetBrains Mono', monospace;
        display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
    }
    .dark-card-eyebrow::before { content: ''; width: 14px; height: 1px; background: rgba(255,255,255,0.15); }
    .dark-card h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.6rem; font-weight: 600; color: #fff;
        line-height: 1.3; margin-bottom: 10px;
    }
    .dark-card h2 span { color: rgba(255,255,255,0.35); font-style: italic; }
    .dark-card p { font-size: 12.5px; color: rgba(255,255,255,0.4); font-weight: 300; line-height: 1.7; }

    .info-card {
        border: 1px solid var(--paper-3); border-radius: 20px;
        padding: 24px; background: #fff;
    }
    .info-card-label {
        font-size: 9.5px; font-weight: 500; letter-spacing: 0.14em;
        text-transform: uppercase; color: var(--ink-4);
        font-family: 'JetBrains Mono', monospace;
        display: flex; align-items: center; gap: 8px; margin-bottom: 18px;
    }
    .info-card-label::before { content: ''; width: 14px; height: 1px; background: var(--paper-3); }

    .steps { display: flex; flex-direction: column; }
    .step {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 12px 0; border-bottom: 1px solid var(--paper-2);
    }
    .step:last-child { border-bottom: none; }
    .step-num {
        font-size: 10px; font-weight: 500; color: var(--ink-4);
        font-family: 'JetBrains Mono', monospace;
        width: 20px; flex-shrink: 0; margin-top: 2px;
    }
    .step-title { font-size: 12.5px; font-weight: 500; color: var(--ink); margin-bottom: 2px; }
    .step-sub { font-size: 11px; color: var(--ink-3); font-weight: 300; }

    /* ── MODE INDICATOR (badge in upload panel) ── */
    .mode-indicator {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: 999px;
        background: var(--paper); border: 1px solid var(--paper-3);
        font-size: 9.5px; font-weight: 500; color: var(--ink-3);
        font-family: 'JetBrains Mono', monospace; letter-spacing: 0.08em;
        text-transform: uppercase; margin-bottom: 20px;
    }
    .mode-indicator-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--accent); }

    /* Batch file list preview */
    .batch-file-list {
        max-height: 180px; overflow-y: auto;
        border: 1px solid var(--paper-3); border-radius: 10px;
        margin-top: 12px;
    }
    .batch-file-item {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 12px; border-bottom: 1px solid var(--paper-2);
        font-size: 11.5px; color: var(--ink-3);
    }
    .batch-file-item:last-child { border-bottom: none; }
    .batch-file-item svg { flex-shrink: 0; color: var(--accent); }

    /* Spinner */
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner { animation: spin 0.9s linear infinite; }

    /* Entry animation */
    .fade-up {
        animation: fadeUp 0.4s ease both;
    }
    @keyframes fadeUp { from { opacity:0; transform: translateY(16px); } to { opacity:1; transform: none; } }
    .delay-1 { animation-delay: 0.08s; }
    .delay-2 { animation-delay: 0.16s; }
    .delay-3 { animation-delay: 0.24s; }
</style>

<div class="min-h-screen" style="background: var(--paper);">

    <!-- Topbar -->
    <div class="topbar">
        <a href="{{ route('coffee.index') }}" class="topbar-back">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali
        </a>
        <div class="topbar-badge">
            <span class="topbar-dot"></span>
            AI Online
        </div>
    </div>

    <div class="page-wrap">

        @if(session('error'))
            <div class="alert-error fade-up">{{ session('error') }}</div>
        @endif

        <!-- Page Header -->
        <div class="page-header fade-up">
            <p class="page-eyebrow">Klasifikasi Biji Kopi</p>
            <h1 class="page-title">Upload &amp; <em>Analisis</em></h1>
            <p class="page-subtitle">
                Pilih mode prediksi, lalu unggah gambar untuk klasifikasi tingkat roasting secara otomatis.
            </p>
        </div>

        <!-- ══════════════════════════════════════
             MODE SELECTION
        ══════════════════════════════════════ -->
        <div id="modeSelection" class="fade-up delay-1">
            <div class="mode-heading">
                <h2>Pilih Mode Upload</h2>
                <p>Tersedia 3 cara untuk mengunggah gambar biji kopi</p>
            </div>

            <div class="mode-grid">

                <!-- Card 1: Single -->
                <button type="button" class="mode-card" onclick="selectMode('single')">
                    <div class="mode-card-shine"></div>
                    <div class="mode-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <h3>Gambar Tunggal</h3>
                    <p>Upload satu foto biji kopi untuk mendapatkan hasil klasifikasi instan dengan perbandingan kedua model.</p>
                    <span class="mode-tag">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        1 gambar · Cepat & Akurat
                    </span>
                    <div class="mode-check">
                        <svg class="mode-check-inner" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </button>

                <!-- Card 2: Batch -->
                <button type="button" class="mode-card" onclick="selectMode('batch')">
                    <div class="mode-card-shine"></div>
                    <div class="mode-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                            <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
                        </svg>
                    </div>
                    <h3>Batch File</h3>
                    <p>Upload beberapa gambar sekaligus dari pilihan file manual. Cocok untuk evaluasi sejumlah sampel tertentu.</p>
                    <span class="mode-tag">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Banyak file · Pilih manual
                    </span>
                    <div class="mode-check">
                        <svg class="mode-check-inner" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </button>

                <!-- Card 3: Folder ZIP -->
                <button type="button" class="mode-card" onclick="selectMode('folder')">
                    <div class="mode-card-shine"></div>
                    <div class="mode-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                            <path d="M12 11v6m-3-3h6"/>
                        </svg>
                    </div>
                    <h3>Folder ZIP</h3>
                    <p>Upload file ZIP berisi folder gambar. Jika terstruktur per kelas, confusion matrix digenerate otomatis.</p>
                    <span class="mode-tag">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Dataset besar · Auto label
                    </span>
                    <div class="mode-check">
                        <svg class="mode-check-inner" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </button>

            </div>
        </div>

        <!-- ══════════════════════════════════════
             UPLOAD SECTION (hidden initially)
        ══════════════════════════════════════ -->
        <div id="uploadSection">
            <div class="upload-panel fade-up">

                <button type="button" class="back-btn" onclick="backToModeSelection()">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Kembali ke Pilihan Mode
                </button>

                <div class="mode-indicator">
                    <span class="mode-indicator-dot"></span>
                    <span id="modeIndicatorText">—</span>
                </div>

                <p class="panel-label"><span id="panelLabelText">Upload Gambar</span></p>

                <!-- ─ FORM SINGLE ─ -->
                <form id="formSingle" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" style="display:none">
                    @csrf
                    <input type="hidden" name="mode" value="single">

                    <div class="drop-zone" id="dropZoneSingle"
                         onclick="document.getElementById('inputSingle').click()">
                        <input type="file" id="inputSingle" name="image" accept="image/jpeg,image/png,image/jpg"
                               class="hidden" required onchange="handleSingle(event)">

                        <div class="drop-empty" id="promptSingle">
                            <div class="drop-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                            </div>
                            <p class="drop-title">Seret & Lepas Gambar</p>
                            <p class="drop-sub">atau <span>pilih file gambar</span></p>
                            <p class="drop-hint">PNG · JPG · JPEG · MAKS 2MB</p>
                        </div>

                        <div class="drop-preview hidden" id="previewSingle">
                            <img id="previewImgSingle" src="" alt="Preview">
                            <div class="drop-preview-meta">
                                <span class="drop-preview-name" id="fileNameSingle"></span>
                                <button type="button" class="drop-preview-change"
                                        onclick="event.stopPropagation(); resetSingle()">Ganti</button>
                            </div>
                        </div>
                    </div>

                    @error('image') <p style="color:var(--danger);font-size:11px;margin-top:8px">{{ $message }}</p> @enderror

                    <div class="tips-grid">
                        @foreach(['Pencahayaan yang cukup','Fokus pada biji kopi','Hindari bayangan berlebih','Ambil dari jarak dekat'] as $tip)
                        <div class="tip-item"><span class="tip-dot"></span>{{ $tip }}</div>
                        @endforeach
                    </div>

                    <div class="action-group">
                        <button type="submit" id="submitSingle" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Klasifikasi Sekarang
                        </button>
                        <a href="{{ route('coffee.index') }}" class="btn-cancel">Batalkan</a>
                    </div>
                </form>

                <!-- ─ FORM BATCH ─ -->
                <form id="formBatch" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" style="display:none">
                    @csrf
                    <input type="hidden" name="mode" value="batch">

                    <div class="drop-zone" id="dropZoneBatch"
                         onclick="document.getElementById('inputBatch').click()">
                        <input type="file" id="inputBatch" name="image[]"
                               accept="image/jpeg,image/png,image/jpg"
                               multiple class="hidden" required onchange="handleBatch(event)">

                        <div class="drop-empty" id="promptBatch">
                            <div class="drop-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                            </div>
                            <p class="drop-title">Pilih Beberapa Gambar</p>
                            <p class="drop-sub">atau <span>pilih banyak file</span></p>
                            <p class="drop-hint">PNG · JPG · JPEG · Bisa pilih banyak sekaligus</p>
                        </div>

                        <div class="hidden" id="previewBatch">
                            <div class="batch-count-display">
                                <span class="batch-count-badge" id="batchCount">0</span>
                                <span class="batch-count-label">gambar dipilih</span>
                                <button type="button" class="batch-count-change"
                                        onclick="event.stopPropagation(); resetBatch()">Pilih Ulang</button>
                            </div>
                            <div class="batch-file-list" id="batchFileList"></div>
                        </div>
                    </div>

                    <div class="tips-grid">
                        @foreach(['Pilih semua gambar sekaligus','Format JPG/PNG/JPEG','Setiap gambar diproses terpisah','Hasil disimpan per gambar'] as $tip)
                        <div class="tip-item"><span class="tip-dot"></span>{{ $tip }}</div>
                        @endforeach
                    </div>

                    <div class="action-group">
                        <button type="submit" id="submitBatch" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Klasifikasi Batch
                        </button>
                        <a href="{{ route('coffee.index') }}" class="btn-cancel">Batalkan</a>
                    </div>
                </form>

                <!-- ─ FORM FOLDER ─ -->
                <form id="formFolder" action="{{ route('coffee.store') }}" method="POST" enctype="multipart/form-data" style="display:none">
                    @csrf
                    <input type="hidden" name="mode" value="folder">

                    <div class="drop-zone" id="dropZoneFolder"
                         onclick="document.getElementById('inputFolder').click()">
                        <input type="file" id="inputFolder" name="folder"
                               accept=".zip" class="hidden" required onchange="handleFolder(event)">

                        <div class="drop-empty" id="promptFolder">
                            <div class="drop-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                                    <path d="M12 11v6m-3-3h6"/>
                                </svg>
                            </div>
                            <p class="drop-title">Upload File ZIP</p>
                            <p class="drop-sub">atau <span>pilih file .zip</span></p>
                            <p class="drop-hint">ZIP · Struktur flat atau per kelas (Dark/ Green/ Light/ Medium/)</p>
                        </div>

                        <div class="hidden" id="previewFolder">
                            <div class="zip-display">
                                <div class="zip-icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="1.5">
                                        <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                                    </svg>
                                </div>
                                <span class="zip-name" id="zipFileName">—</span>
                                <span class="zip-size" id="zipFileSize">—</span>
                                <button type="button" class="zip-change"
                                        onclick="event.stopPropagation(); resetFolder()">Ganti File</button>
                            </div>
                        </div>
                    </div>

                    <!-- Folder structure info -->
                    <div style="margin-top:16px; padding:14px 16px; background:var(--paper); border-radius:10px; border:1px solid var(--paper-3);">
                        <p style="font-size:10.5px; font-weight:500; color:var(--ink-3); margin-bottom:10px; font-family:'JetBrains Mono',monospace; letter-spacing:0.08em;">STRUKTUR ZIP YANG DIDUKUNG</p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div>
                                <p style="font-size:10px; color:var(--accent); font-weight:500; margin-bottom:6px; font-family:'JetBrains Mono',monospace;">FLAT (tanpa label)</p>
                                <pre style="font-size:10px; color:var(--ink-3); line-height:1.8; font-family:'JetBrains Mono',monospace;">images.zip
├── img1.jpg
├── img2.jpg
└── img3.png</pre>
                            </div>
                            <div>
                                <p style="font-size:10px; color:var(--success); font-weight:500; margin-bottom:6px; font-family:'JetBrains Mono',monospace;">PER KELAS (auto label ✓)</p>
                                <pre style="font-size:10px; color:var(--ink-3); line-height:1.8; font-family:'JetBrains Mono',monospace;">dataset.zip
├── Dark/
├── Green/
├── Light/
└── Medium/</pre>
                            </div>
                        </div>
                    </div>

                    <div class="action-group">
                        <button type="submit" id="submitFolder" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Klasifikasi Folder
                        </button>
                        <a href="{{ route('coffee.index') }}" class="btn-cancel">Batalkan</a>
                    </div>
                </form>

            </div><!-- /upload-panel -->

            <!-- Right Column -->
            <div class="right-col fade-up delay-2">

                <!-- Dark Card -->
                <div class="dark-card">
                    <div class="dark-card-dots"></div>
                    <div class="dark-card-ring1"></div>
                    <div class="dark-card-ring2"></div>
                    <div style="position:relative">
                        <p class="dark-card-eyebrow">Sistem AI</p>
                        <h2>Presisi tinggi untuk<br>Setiap <span>roasting</span></h2>
                        <p>Model deep learning terlatih dengan ribuan gambar biji kopi. Identifikasi instan, akurasi tinggi dengan dua model paralel.</p>
                    </div>
                </div>

                <!-- Steps -->
                <div class="info-card">
                    <p class="info-card-label">Cara Kerja</p>
                    <div class="steps">
                        @foreach([
                            ['01','Upload gambar/folder','Pilih mode yang sesuai kebutuhan'],
                            ['02','Dikirim ke Flask API','Analisis gambar secara real-time'],
                            ['03','Dua model bekerja','MobileNetV3 Small & Large'],
                            ['04','Hasil tersimpan','Lengkap dengan perbandingan model'],
                        ] as [$n, $t, $s])
                        <div class="step">
                            <span class="step-num">{{ $n }}</span>
                            <div><p class="step-title">{{ $t }}</p><p class="step-sub">{{ $s }}</p></div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mode comparison mini table -->
                <div class="info-card fade-up delay-3">
                    <p class="info-card-label">Perbandingan Mode</p>
                    <table style="width:100%; border-collapse:collapse; font-size:11.5px;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:6px 0; color:var(--ink-4); font-weight:400; font-family:'JetBrains Mono',monospace; font-size:9.5px; letter-spacing:0.08em;">MODE</th>
                                <th style="text-align:center; padding:6px 0; color:var(--ink-4); font-weight:400; font-family:'JetBrains Mono',monospace; font-size:9.5px;">LABEL</th>
                                <th style="text-align:center; padding:6px 0; color:var(--ink-4); font-weight:400; font-family:'JetBrains Mono',monospace; font-size:9.5px;">CM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['Single','—','—'],
                                ['Batch','Manual','✓'],
                                ['Folder ZIP','Otomatis','✓'],
                            ] as [$m,$l,$c])
                            <tr style="border-top:1px solid var(--paper-2)">
                                <td style="padding:9px 0; color:var(--ink); font-weight:500;">{{ $m }}</td>
                                <td style="padding:9px 0; text-align:center; color:var(--ink-3);">{{ $l }}</td>
                                <td style="padding:9px 0; text-align:center; color:{{ $c === '✓' ? 'var(--success)' : 'var(--ink-4)' }}; font-size:13px;">{{ $c }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p style="font-size:10px; color:var(--ink-4); margin-top:10px;">CM = Confusion Matrix</p>
                </div>

            </div>
        </div><!-- /uploadSection -->

    </div>
</div>

<script>
let currentMode = null;

// ── MODE SELECTION ──
function selectMode(mode) {
    currentMode = mode;

    document.getElementById('modeSelection').style.display = 'none';

    const sec = document.getElementById('uploadSection');
    sec.classList.add('active');

    // Show correct form
    document.getElementById('formSingle').style.display  = mode === 'single' ? 'block' : 'none';
    document.getElementById('formBatch').style.display   = mode === 'batch'  ? 'block' : 'none';
    document.getElementById('formFolder').style.display  = mode === 'folder' ? 'block' : 'none';

    // Labels
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
    const sec = document.getElementById('uploadSection');
    sec.classList.remove('active');
    resetSingle(); resetBatch(); resetFolder();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── SINGLE ──
function handleSingle(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { alert('Ukuran file melebihi 2MB!'); resetSingle(); return; }
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
    document.getElementById('previewImgSingle').src = '';
    document.getElementById('promptSingle').classList.remove('hidden');
    document.getElementById('previewSingle').classList.add('hidden');
}

// ── BATCH ──
function handleBatch(e) {
    const files = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
    if (files.length === 0) { alert('Tidak ada gambar valid!'); resetBatch(); return; }

    document.getElementById('batchCount').textContent = files.length;
    const list = document.getElementById('batchFileList');
    list.innerHTML = files.slice(0, 20).map(f =>
        `<div class="batch-file-item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span>${f.name}</span>
        </div>`
    ).join('') + (files.length > 20 ? `<div class="batch-file-item" style="color:var(--ink-4)">...dan ${files.length - 20} file lainnya</div>` : '');

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

// ── FOLDER ──
function handleFolder(e) {
    const file = e.target.files[0];
    if (!file) return;
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
function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024*1024) return (bytes/1024).toFixed(1) + ' KB';
    return (bytes/(1024*1024)).toFixed(2) + ' MB';
}

// ── DRAG & DROP (untuk semua zone) ──
['Single','Batch','Folder'].forEach(type => {
    const zone = document.getElementById('dropZone' + type);
    if (!zone) return;
    ['dragenter','dragover','dragleave','drop'].forEach(ev =>
        zone.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); })
    );
    zone.addEventListener('dragenter', () => zone.classList.add('drag-over'));
    zone.addEventListener('dragover',  () => zone.classList.add('drag-over'));
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        zone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (!files.length) return;
        if (type === 'Single') {
            document.getElementById('inputSingle').files = files;
            handleSingle({ target: { files } });
        } else if (type === 'Batch') {
            document.getElementById('inputBatch').files = files;
            handleBatch({ target: { files } });
        } else {
            document.getElementById('inputFolder').files = files;
            handleFolder({ target: { files } });
        }
    });
});

// ── SUBMIT LOADING STATE ──
['formSingle','formBatch','formFolder'].forEach((id, i) => {
    const form = document.getElementById(id);
    const btnIds = ['submitSingle','submitBatch','submitFolder'];
    if (form) {
        form.addEventListener('submit', () => {
            const btn = document.getElementById(btnIds[i]);
            btn.disabled = true;
            btn.classList.add('opacity-60');
            btn.innerHTML = `
                <svg class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                    <path d="M12 2a10 10 0 0110 10"/>
                </svg>
                Menganalisis...`;
        });
    }
});
</script>

@endsection