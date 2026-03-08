@extends('layouts.app')

@section('title', 'Info Roasting — CoffeeAI')

@section('content')

<!-- ─────────────────────── WRAPPER ─────────────────────── -->
<div class="info-bg min-h-screen" style="font-family: 'Poppins';">

    <!-- ══════════ HERO / HEADER ══════════ -->
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">

            <!-- Top bar -->
            <div class="flex items-center justify-between mb-10 anim d1">
                <a href="{{ route('coffee.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Kembali ke Beranda
                </a>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 text-xs font-semibold text-gray-500 bg-white">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 dot-pulse"></span>
                    AI Model Aktif
                </span>
            </div>

            <!-- Heading block -->
            <div class="max-w-3xl anim d2">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Panduan Lengkap</p>
                <h1 class="text-5xl sm:text-6xl font-semibold tracking-tighter text-gray-900 leading-[1.05] mb-5">
                    Tingkat Roasting<br>
                    <span class="text-gray-400">Biji Kopi.</span>
                </h1>
                <p class="text-lg text-gray-500 leading-relaxed max-w-xl">
                    Memahami perbedaan Green, Light, Medium, dan Dark roast —
                    agar kamu tahu apa yang dideteksi oleh sistem AI kami.
                </p>
            </div>

            <!-- Stats strip -->
            <div class="mt-10 flex flex-wrap gap-8 anim d3">
                @foreach([
                    ['4',      'Tingkat Roasting'],
                    ['94.2%',  'Akurasi Model AI'],
                    ['< 2s',   'Waktu Analisis'],
                    ['CNN',    'MobileNetV3 Arch'],
                ] as [$val, $label])
                <div>
                    <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $val }}</p>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-1">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ══════════ MAIN CONTENT ══════════ -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ═══ LEFT: Roasting Level Cards (2/3) ═══ -->
            <div class="lg:col-span-2 space-y-4">

                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2 anim d2">
                    Jenis-Jenis Roasting
                </p>

                @php
                // Definisi SVG Icon (Coffee Bean shape)
                $svgIcon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>';

                $roasts = [
                    [
                        'icon'   => $svgIcon, // Menggunakan SVG
                        'color'  => 'text-green-500', // Class warna untuk ikon & gradien
                        'label'  => 'Green',
                        'title'  => 'Green Bean',
                        'temp'   => 'Belum diproses',
                        'bar'    => '5%',
                        'badges' => ['Mentah', 'Tidak bisa diseduh', 'Benih kopi'],
                        'desc'   => 'Biji kopi yang belum melalui proses roasting sama sekali. Berwarna hijau keabu-abuan, bertekstur keras dan padat. Tidak memiliki aroma kopi dan tidak dapat langsung diseduh.',
                        'chars'  => [
                            'Warna hijau keabu-abuan, padat',
                            'Tidak beraroma kopi',
                            'Tidak bisa langsung diseduh',
                            'Kadar air masih tinggi',
                        ],
                        'delay' => 'd3',
                    ],
                    [
                        'icon'   => $svgIcon,
                        'color'  => 'text-yellow-500',
                        'label'  => 'Light',
                        'title'  => 'Light Roast',
                        'temp'   => '180 – 205°C',
                        'bar'    => '28%',
                        'badges' => ['Asam tinggi', 'Body ringan', 'Floral & fruity'],
                        'desc'   => 'Dipanggang hingga suhu 180–205°C, biji light roast memiliki warna cokelat muda. Rasa asam sangat menonjol dengan aroma floral dan fruity. Kafein relatif lebih tinggi karena proses roasting lebih singkat.',
                        'chars'  => [
                            'Warna cokelat muda, permukaan kering',
                            'Tingkat keasaman tinggi',
                            'Aroma floral, fruity, tea-like',
                            'Body ringan, kafein tinggi',
                        ],
                        'delay' => 'd4',
                    ],
                    [
                        'icon'   => $svgIcon,
                        'color'  => 'text-orange-500',
                        'label'  => 'Medium',
                        'title'  => 'Medium Roast',
                        'temp'   => '210 – 220°C',
                        'bar'    => '62%',
                        'badges' => ['Seimbang', 'Karamel', 'Paling populer'],
                        'desc'   => 'Titik keseimbangan sempurna antara keasaman dan kepahitan. Warna cokelat sedang dengan aroma karamel dan nutty. Tingkat roasting paling populer di Indonesia dan dunia.',
                        'chars'  => [
                            'Warna cokelat medium, mulai berminyak',
                            'Asam & pahit seimbang',
                            'Aroma karamel, nutty, cokelat',
                            'Body sedang, disukai semua kalangan',
                        ],
                        'delay' => 'd5',
                    ],
                    [
                        'icon'   => $svgIcon,
                        'color'  => 'text-stone-800', // Warna gelap untuk Dark
                        'label'  => 'Dark',
                        'title'  => 'Dark Roast',
                        'temp'   => '225 – 240°C+',
                        'bar'    => '95%',
                        'badges' => ['Pahit kuat', 'Smoky', 'Body penuh'],
                        'desc'   => 'Dipanggang hingga suhu tertinggi, menghasilkan biji berwarna cokelat gelap hingga hitam dengan permukaan berminyak. Rasa pahit mendominasi dengan aroma smoky dan bold.',
                        'chars'  => [
                            'Warna cokelat gelap–hitam, berminyak',
                            'Pahit kuat, sedikit asam',
                            'Aroma smoky, bold, dark chocolate',
                            'Body penuh, kafein lebih rendah',
                        ],
                        'delay' => 'd6',
                    ],
                ];
                @endphp

                @foreach($roasts as $roast)
                <div class="roast-detail bg-white rounded-2xl border border-gray-200 p-6 scroll-reveal anim {{ $roast['delay'] }}">

                    <!-- Card header -->
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <!-- Icon Container dengan Gradien (Seperti kode sebelumnya) -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ str_replace('text-', 'from-', $roast['color']) }} flex items-center justify-center {{ $roast['color'] }}">
                                {!! $roast['icon'] !!}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ $roast['title'] }}</h3>
                                    <span class="inline-block px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wide">
                                        {{ $roast['label'] }}
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    🌡 {{ $roast['temp'] }}
                                </p>
                            </div>
                        </div>
                        <!-- Intensity bar -->
                        <div class="text-right flex-shrink-0 min-w-[80px]">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Intensitas</p>
                            <div class="roast-bar" data-width="{{ $roast['bar'] }}">
                                <div class="roast-fill"></div>
                            </div>
                            <p class="text-xs font-bold text-gray-700 mt-1">{{ $roast['bar'] }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $roast['desc'] }}</p>

                    <!-- Characteristics list -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                        @foreach($roast['chars'] as $char)
                        <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></div>
                            <span class="text-xs font-medium text-gray-600">{{ $char }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Flavor badges -->
                    <div class="flex flex-wrap gap-2">
                        @foreach($roast['badges'] as $badge)
                        <span class="inline-flex items-center px-3 py-1 rounded-full border border-gray-200 bg-white text-xs font-semibold text-gray-700 shadow-sm">
                            {{ $badge }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach

            </div>

            <!-- ═══ RIGHT: Sidebar (1/3, sticky) ═══ -->
            <div class="flex flex-col gap-5 lg:sticky lg:top-24 h-fit">

                <!-- Dark card: About AI system -->
                <div class="relative overflow-hidden rounded-2xl bg-gray-900 p-7 anim d2">
                    <div class="absolute inset-0 grid-overlay pointer-events-none"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Tentang Sistem AI</p>
                        <h3 class="text-xl font-semibold text-white leading-snug tracking-tight mb-3">
                            Klasifikasi otomatis<br>berbasis Deep Learning.
                        </h3>
                        <p class="text-sm text-gray-400 leading-relaxed mb-5">
                            Model <strong class="text-gray-300">CNN MobileNetV3-Small</strong> dilatih dengan ribuan
                            gambar biji kopi dari 4 kelas roasting. Inferensi berjalan di
                            Flask API dan hasilnya tersimpan otomatis di Laravel.
                        </p>
                        <!-- Pipeline badges -->
                        <div class="flex items-center gap-2 flex-wrap">
                            @foreach(['Input 224×224', '→', 'MobileNetV3', '→', 'Softmax × 4'] as $step)
                            @if($step === '→')
                                <span class="text-gray-600 text-xs">→</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg bg-white/10 text-white text-[10px] font-semibold tracking-wide">{{ $step }}</span>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tech stack card -->
                <div class="bg-white rounded-2xl border border-gray-200 p-7 anim d3">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-5">Tech Stack</p>
                    <div class="flex flex-col gap-3">
                        @foreach([
                            ['laravel.png', 'Laravel 11',     'Backend & Auth',       'bg-red-50',   'text-red-600'],
                            ['flask.png',   'Flask API',      'AI Inference Server',  'bg-green-50', 'text-green-600'],
                            ['ai-model.png','CNN Model',      'MobileNetV3-Small',    'bg-blue-50',  'text-blue-600'],
                        ] as [$img, $name, $role, $bg, $dot])
                        
                        <div class="tech-row flex items-center gap-4 px-4 py-3 rounded-xl border border-gray-100 bg-gray-50">
                            
                            <!-- Container Icon (Ukuran disamakan h-10 w-10) -->
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white flex-shrink-0">
                                
                                {{-- KONDISI: Jika Laravel, tampilkan SVG, jika lain tampilkan Gambar --}}
                                @if($name == 'Laravel 11')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Laravel--Streamline-Svg-Logos" height="24" width="24" class="h-6 w-6">
                                        <desc>Laravel Streamline Icon: https://streamlinehq.com</desc>
                                        <path fill="#ff2d20" d="M23.401 5.566325c0.00845 0.031325 0.01285 0.063625 0.01285 0.096075v5.044225c0 0.131775 -0.070475 0.253475 -0.1848 0.31905l-4.2337 2.4375v4.831375c0 0.131475 -0.069875 0.25285 -0.1839 0.31905L9.973975 23.701025c-0.020225 0.0115 -0.0423 0.01885 -0.064375 0.02665 -0.008275 0.002775 -0.016075 0.007825 -0.024825 0.010125 -0.061775 0.016275 -0.1267 0.016275 -0.188475 0 -0.010125 -0.00275 -0.019325 -0.008275 -0.028975 -0.01195 -0.020225 -0.00735 -0.041375 -0.0138 -0.060675 -0.024825L0.770995 18.6136c-0.114245 -0.065625 -0.1848125 -0.1873 -0.1848125 -0.31905V3.1619c0 -0.0331 0.0045975 -0.065275 0.0128725 -0.09655 0.0027575 -0.010575 0.009195 -0.020225 0.0128725 -0.0308 0.006895 -0.0193 0.0133325 -0.039075 0.023445 -0.057 0.0068975 -0.01195 0.01701 -0.0216 0.025285 -0.03265 0.010575 -0.0147 0.02023 -0.029875 0.0326425 -0.04275 0.010575 -0.010575 0.024365 -0.0184 0.03632 -0.027575 0.0133325 -0.01105 0.025285 -0.023 0.040455 -0.031725h0.00046L5.1886 0.2991325c0.113825 -0.06551 0.253925 -0.06551 0.367775 0L9.974425 2.84285h0.000925c0.0147 0.0092 0.027125 0.020675 0.04045 0.03125 0.01195 0.0092 0.0253 0.017475 0.035875 0.0276 0.012875 0.013325 0.02205 0.0285 0.0331 0.0432 0.0078 0.01105 0.018375 0.0207 0.024825 0.03265 0.010575 0.0184 0.01655 0.0377 0.0239 0.057 0.003675 0.010575 0.0101 0.020225 0.012875 0.031275 0.00845 0.031325 0.012775 0.063625 0.012875 0.096075v9.4517l3.68155 -2.11985V5.66195c0 -0.0322 0.0046 -0.064825 0.012875 -0.095625 0.003225 -0.01105 0.0092 -0.0207 0.012875 -0.031275 0.00735 -0.0193 0.0138 -0.039075 0.0239 -0.057 0.0069 -0.01195 0.017 -0.0216 0.024825 -0.03265 0.011025 -0.0147 0.020225 -0.029875 0.0331 -0.04275 0.010575 -0.010575 0.0239 -0.0184 0.035875 -0.027575 0.013775 -0.01105 0.025725 -0.023 0.04045 -0.031725h0.00045l4.418525 -2.543725c0.1138 -0.0656 0.25395 -0.0656 0.367775 0L23.2295 5.34335c0.01565 0.0092 0.0276 0.020675 0.041375 0.03125 0.0115 0.0092 0.024825 0.017475 0.0354 0.0276 0.012875 0.013325 0.022075 0.0285 0.0331 0.0432 0.008275 0.01105 0.0184 0.0207 0.024825 0.03265 0.010575 0.017925 0.01655 0.0377 0.023925 0.057 0.004125 0.010575 0.0101 0.020225 0.012875 0.031275ZM22.677375 10.49375V6.299125l-1.5461 0.89005 -2.135925 1.2298v4.194625l3.682475 -2.11985h-0.00045ZM18.259325 18.081675V13.8843l-2.101 1.1999 -5.99955 3.424125v4.2369l8.10055 -4.66355ZM1.3226775 3.798625v14.28305L9.4223 22.744775v-4.236l-4.231425 -2.39475 -0.001375 -0.000925 -0.001825 -0.000925c-0.01425 -0.008275 -0.0262 -0.020225 -0.03955 -0.03035 -0.011475 -0.009175 -0.024825 -0.01655 -0.034925 -0.02665l-0.000925 -0.001375c-0.01195 -0.0115 -0.020225 -0.02575 -0.03035 -0.038625 -0.0092 -0.012425 -0.020225 -0.023 -0.027575 -0.03585l-0.000475 -0.0014c-0.008275 -0.013775 -0.013325 -0.030325 -0.0193 -0.045975 -0.005975 -0.013775 -0.0138 -0.02665 -0.017475 -0.041375v-0.00045c-0.0046 -0.017475 -0.0055 -0.03585 -0.00735 -0.0538 -0.001825 -0.013775 -0.005525 -0.027575 -0.005525 -0.041375V5.918475l-2.13545 -1.23025 -1.5460975 -0.889125v-0.000475ZM5.37295 1.0429825 1.691845 3.1619l3.68018 2.118925 3.680625 -2.119375 -3.680625 -2.1184675h0.000925Zm1.914325 13.2238175 2.135475 -1.229325V3.798625l-1.5461 0.89005 -2.135925 1.2298v9.238825l1.54655 -0.8905Zm11.340275 -10.723325 -3.680625 2.118925 3.680625 2.118925 3.6802 -2.119375 -3.6802 -2.118475Zm-0.368225 4.8755 -2.13595 -1.2298 -1.546075 -0.89005V10.49375l2.135475 1.22935 1.54655 0.8905V8.418975Zm-8.46925 9.4526L15.18875 14.789525l2.69865 -1.540125 -3.6779 -2.117525 -4.2346 2.437975 -3.859475 2.2219 3.67465 2.079825Z" stroke-width="0.25"></path>
                                    </svg>
                                @else
                                    <img src="{{ asset('img/' . $img) }}" alt="{{ $name }}"
                                        class="h-6 w-6 object-contain"
                                        onerror="this.parentElement.innerHTML='<span class=\'text-lg\'>⚙️</span>'">
                                @endif
                                
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900">{{ $name }}</p>
                                <p class="text-xs text-gray-400">{{ $role }}</p>
                            </div>
                            <span class="w-2 h-2 rounded-full {{ $bg }} border {{ str_replace('bg-', 'border-', $bg) }} flex-shrink-0">
                                <span class="block w-2 h-2 rounded-full {{ $dot }} opacity-60"></span>
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- How it works mini card -->
                <div class="bg-white rounded-2xl border border-gray-200 p-7 anim d4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-5">Cara Kerja</p>
                    <div class="flex flex-col divide-y divide-gray-100">
                        @foreach([
                            ['01', 'Upload foto biji kopi',       'JPG, PNG, JPEG'],
                            ['02', 'Gambar dikirim ke Flask',      'Resize 224×224, normalize'],
                            ['03', 'MobileNetV3 klasifikasi',      'Inferensi CNN deep learning'],
                            ['04', 'Hasil & confidence score',     'Tersimpan otomatis di DB'],
                        ] as [$num, $title, $sub])
                        <div class="flex items-start gap-4 py-3.5 group">
                            <span class="flex-shrink-0 w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center
                                         text-[11px] font-bold text-gray-400
                                         group-hover:border-gray-900 group-hover:text-gray-900 transition-colors">
                                {{ $num }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sub }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- CTA card -->
                <div class="bg-gray-900 rounded-2xl p-7 anim d5 relative overflow-hidden">
                    <div class="absolute inset-0 grid-overlay pointer-events-none"></div>
                    <div class="relative z-10">
                        <p class="text-sm font-semibold text-white mb-1">Siap mencoba?</p>
                        <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                            Upload gambar biji kopi kamu dan dapatkan hasil klasifikasi instan.
                        </p>
                        <a href="{{ route('coffee.create') }}"
                           class="flex items-center justify-center gap-2 w-full h-11 rounded-xl bg-white text-gray-900
                                  text-sm font-bold hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Mulai Klasifikasi
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
    // ── Animate intensity bars on scroll ──────────────────
    const bars = document.querySelectorAll('.roast-bar');
    const barObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const fill = e.target.querySelector('.roast-fill');
                setTimeout(() => { fill.style.width = e.target.dataset.width; }, 200);
                barObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.4 });
    bars.forEach(b => barObs.observe(b));

    // ── Scroll reveal for roast cards ────────────────────
    const revealEls = document.querySelectorAll('.scroll-reveal');
    const revObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                revObs.unobserve(e.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
    revealEls.forEach(el => revObs.observe(el));
</script>

@endsection