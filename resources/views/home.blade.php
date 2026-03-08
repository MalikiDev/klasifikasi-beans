@extends('layouts.app')

@section('title', 'Beranda — BeanRoast ML')

@section('content')

<!-- ===================== HERO SECTION ===================== -->
<section class="relative min-h-[calc(100vh-4rem)] overflow-hidden flex items-center">

    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 z-0 pointer-events-none
                bg-[linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)]
                bg-[size:4rem_4rem]
                [mask-image:radial-gradient(ellipse_80%_80%_at_50%_50%,#000_10%,transparent_100%)]
                opacity-40">
    </div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-32
                flex flex-col lg:flex-row items-center gap-16 lg:gap-8">

        <!-- =====content kiri-->
        <div class="flex-1 w-full max-w-2xl lg:max-w-none flex flex-col">
            <h1 class="text-4xl sm:text-5xl lg:text-[4.5rem] font-semibold tracking-tighter text-gray-900 leading-tight" style="font-family: 'Cormorant+Infant', sans-serif;">
                Klasifikasi Roasting Biji Kopi, <br>
                <span class="text-gray-400">didukung AI.</span>
            </h1>
            <p class="mt-8 text-sm sm:text-lg text-gray-500 leading-relaxed max-w-xl" style="font-family: 'Poppins', serif;" >
                Identifikasi jenis roasting biji kopi secara otomatis dan akurat.
                Upload gambar, dapatkan hasil klasifikasi lengkap beserta informasi roasting dalam hitungan detik.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-10 flex flex-col sm:flex-row gap-3 max-w-md">
                <a href="{{ route('coffee.create') }}"
                   class="h-14 inline-flex items-center justify-center gap-2 rounded-lg bg-[#0a0a0a] px-8
                          text-base font-medium text-white shadow-sm transition-all
                          hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Mulai Klasifikasi
                </a>
                <a href="{{ route('coffee.roasting-info') }}"
                   class="h-14 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-8
                          text-base font-medium text-gray-700 shadow-sm transition-all
                          hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                    Pelajari Info Roasting
                </a>
            </div>

            <!-- Social Proof -->
            <div class="mt-12 flex items-center gap-4">
                <div class="flex -space-x-3">
                    <div class="h-9 w-9 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-semibold text-gray-600">A</div>
                    <div class="h-9 w-9 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center text-xs font-semibold text-gray-600">B</div>
                    <div class="h-9 w-9 rounded-full bg-gray-400 border-2 border-white flex items-center justify-center text-xs font-semibold text-white">C</div>
                </div>
                <span class="text-sm font-medium text-gray-500">Dipercaya para petani &amp; roaster kopi.</span>
            </div>

        </div>

        <!-- ===== Right Column: Hero Image ===== -->
        <div class="hidden lg:flex flex-1 w-full relative items-center justify-center lg:justify-end min-h-[400px] lg:min-h-[600px]">

            <!-- Decorative background elements -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] aspect-square max-w-[700px] rounded-full
                        [background-image:radial-gradient(#d1d5db_1px,transparent_1px)]
                        [background-size:12px_12px] opacity-40
                        [mask-image:radial-gradient(circle_at_center,black_30%,transparent_70%)]
                        rotate-12 pointer-events-none">
            </div>

            <!-- Main Hero Image -->
            <div class="relative z-20 w-full max-w-lg">
                    <!-- Image -->
                    <img src="{{ asset('img/modelhero.png') }}" 
                         alt="Coffee Beans Classification" 
                         class="w-full h-full object-cover"
                         onerror="this.src='{{ asset('img/hero1.png') }}'">  
                    <!-- Floating Badge -->
                    <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center">
                                <img src="{{ asset('img/training.png') }}" alt="Flask" class="h-9 w-9 object-contain text-white">
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium">AI Classification</p>
                                <p class="text-sm font-bold text-gray-900">94.2% Accuracy</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ✓ Active
                                </span>
                            </div>
                        </div>
                    </div>

                <!-- Decorative dots -->
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-gray-900 rounded-full opacity-5"></div>
                <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gray-900 rounded-full opacity-5"></div>
            </div>

        </div>
    </div>
</section>
<!-- ================= END HERO ================= -->

<!-- ===================== TIMELINE / HOW IT WORKS ===================== -->
<section class="relative py-24 sm:py-36 bg-[#fafafa] overflow-hidden border-t border-gray-200/50" style="font-family: 'Roboto Mono', monospace;">
    <!-- Subtle Dotted Background -->
    <div class="absolute inset-0 z-0 pointer-events-none
                [background-image:radial-gradient(#e5e7eb_1px,transparent_1px)]
                [background-size:24px_24px] opacity-70">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">

        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-20 sm:mb-28 opacity-0 translate-y-8 transition-all duration-1000 ease-out scroll-reveal">
            <h2 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900">
                Dari Upload ke Hasil
            </h2>
            <p class="text-lg text-gray-500 mt-5">
                Proses klasifikasi yang cepat, akurat, dan mudah dipahami.
            </p>
        </div>

        <!-- Timeline Structure -->
        <div id="timeline-container" class="relative w-full max-w-4xl">

            <!-- Background Static Line -->
            <div class="absolute left-[23px] md:left-1/2 top-4 bottom-4 w-px bg-gray-200 md:-translate-x-1/2 z-0"></div>

            <!-- Animated Progress Line (height controlled by JS) -->
            <div id="timeline-progress"></div>

            <div class="space-y-24 sm:space-y-36 relative z-10 pb-8 pt-4">

                <!-- Step 1: Upload -->
                <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between
                            opacity-0 translate-y-12 transition-all duration-700 ease-out scroll-reveal"
                     data-step="0">
                    <!-- Node Dot -->
                    <div class="timeline-node absolute left-[19px] md:left-1/2 -translate-x-1/2 top-2.5 md:top-auto
                                w-3 h-3 rounded-full bg-gray-300 ring-[5px] ring-[#fafafa]
                                transition-all duration-500 ease-out"></div>

                    <!-- Left: Text -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-0 md:pr-20 text-left md:text-right">
                        <span class="text-xs font-medium tracking-widest text-gray-400 uppercase">01 — Upload</span>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mt-2 tracking-tight">Unggah Gambar</h3>
                        <p class="text-lg text-gray-500 mt-2.5">Foto biji kopi dari kamera atau galeri.</p>
                    </div>

                    <!-- Right: Card -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-20 mt-8 md:mt-0 flex justify-start">
                        <div class="bg-white border border-gray-200/80 rounded-xl px-5 py-4 shadow-sm flex items-center gap-3 w-full max-w-[280px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 flex-shrink-0" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-800">beans_sample.jpg</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Analisis AI -->
                <div class="relative flex flex-col md:flex-row-reverse items-start md:items-center justify-between
                            opacity-0 translate-y-12 transition-all duration-700 ease-out scroll-reveal"
                     data-step="1">
                    <!-- Node Dot -->
                    <div class="timeline-node absolute left-[19px] md:left-1/2 -translate-x-1/2 top-2.5 md:top-auto
                                w-3 h-3 rounded-full bg-gray-300 ring-[5px] ring-[#fafafa]
                                transition-all duration-500 ease-out"></div>

                    <!-- Right: Text -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-20 text-left">
                        <span class="text-xs font-medium tracking-widest text-gray-400 uppercase">02 — Analisis</span>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mt-2 tracking-tight">Proses AI</h3>
                        <p class="text-lg text-gray-500 mt-2.5">Model deep learning menganalisis warna & tekstur.</p>
                    </div>

                    <!-- Left: Card -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-0 md:pr-20 mt-8 md:mt-0 flex justify-start md:justify-end">
                        <div class="bg-white border border-gray-200/80 rounded-xl px-6 py-5 shadow-sm text-left w-full max-w-[240px]">
                            <span class="text-xs text-gray-400 font-medium block mb-1">Model</span>
                            <span class="text-base font-semibold text-gray-900 tracking-tight">CNN + Flask API</span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Klasifikasi -->
                <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between
                            opacity-0 translate-y-12 transition-all duration-700 ease-out scroll-reveal"
                     data-step="2">
                    <!-- Node Dot -->
                    <div class="timeline-node absolute left-[19px] md:left-1/2 -translate-x-1/2 top-2.5 md:top-auto
                                w-3 h-3 rounded-full bg-gray-300 ring-[5px] ring-[#fafafa]
                                transition-all duration-500 ease-out"></div>

                    <!-- Left: Text -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-0 md:pr-20 text-left md:text-right">
                        <span class="text-xs font-medium tracking-widest text-gray-400 uppercase">03 — Klasifikasi</span>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mt-2 tracking-tight">Hasil Roasting</h3>
                        <p class="text-lg text-gray-500 mt-2.5">Light, Medium, atau Dark Roast terdeteksi.</p>
                    </div>

                    <!-- Right: Card -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-20 mt-8 md:mt-0 flex justify-start">
                        <div class="bg-white border border-gray-200/80 rounded-xl px-4 py-3.5 shadow-sm flex items-center gap-3.5 w-full max-w-[280px]">
                            <div class="bg-amber-50 p-1.5 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] text-amber-500" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-800">Medium Roast — 94.2%</span>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Info & Rekomendasi -->
                <div class="relative flex flex-col md:flex-row-reverse items-start md:items-center justify-between
                            opacity-0 translate-y-12 transition-all duration-700 ease-out scroll-reveal"
                     data-step="3">
                    <!-- Node Dot -->
                    <div class="timeline-node absolute left-[19px] md:left-1/2 -translate-x-1/2 top-2.5 md:top-auto
                                w-3 h-3 rounded-full bg-gray-300 ring-[5px] ring-[#fafafa]
                                transition-all duration-500 ease-out"></div>

                    <!-- Right: Text -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-20 text-left">
                        <span class="text-xs font-medium tracking-widest text-gray-400 uppercase">04 — Rekomendasi</span>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mt-2 tracking-tight">Info & Panduan</h3>
                        <p class="text-lg text-gray-500 mt-2.5">Suhu, waktu roasting, dan profil rasa lengkap.</p>
                    </div>

                    <!-- Left: Card -->
                    <div class="w-full md:w-1/2 pl-14 md:pl-0 md:pr-20 mt-8 md:mt-0 flex justify-start md:justify-end">
                        <a href="{{ route('coffee.roasting-info') }}"
                           class="bg-[#0a0a0a] rounded-lg px-5 py-3 flex items-center gap-2.5 shadow-md text-white
                                  hover:bg-gray-800 transition-colors">
                            <span class="text-sm font-semibold tracking-wide">Lihat Info Roasting</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- ================= END TIMELINE ================= -->

<!-- ===================== FEATURE CARDS SECTION ===================== -->
<section class="py-24 sm:py-32 bg-white border-t border-gray-100" style="font-family: 'Poppins', Sans-serif;">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="max-w-xl mb-16 scroll-reveal opacity-0 translate-y-8 transition-all duration-700 ease-out">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Fitur Utama</p>
            <h2 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 leading-tight">
                Semua yang kamu <br><span class="text-gray-400">butuhkan.</span>
            </h2>
        </div>

        <!-- 2-column grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Card 1: Kalibrasi Akurasi (large, left) -->
            <div class="scroll-reveal opacity-0 translate-y-8 transition-all duration-700 ease-out
                        rounded-2xl border border-gray-200/80 bg-[#fafafa] p-8 flex flex-col gap-8
                        hover:border-gray-300 hover:shadow-sm transition-shadow">
                <!-- Icon -->
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <!-- Text -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 tracking-tight">Kalibrasi Akurasi</h3>
                    <p class="mt-2 text-base text-gray-500 leading-relaxed max-w-sm">
                        Sesuaikan threshold confidence model. Dari deteksi cepat hingga presisi tinggi, dalam satu klik.
                    </p>
                </div>
                <!-- Interactive visual: confidence slider mock -->
                <div class="rounded-xl border border-gray-200 bg-white p-5 flex flex-col gap-4">
                    <div class="flex items-center justify-between text-xs font-medium text-gray-400 uppercase tracking-widest">
                        <span>Cepat</span>
                        <span>Presisi</span>
                    </div>
                    <!-- Slider track -->
                    <div class="relative w-full h-1.5 bg-gray-100 rounded-full">
                        <div class="absolute left-0 top-0 h-1.5 w-[72%] bg-gray-900 rounded-full"></div>
                        <div class="absolute top-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-gray-900 border-2 border-white shadow-md"
                             style="left: calc(72% - 8px)"></div>
                    </div>
                    <!-- Result labels -->
                    <div class="flex flex-col gap-2 mt-1">
                        <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-lg bg-gray-900 text-white">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                            <span class="text-sm font-medium">"Medium Roast — confidence 94.2%"</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-lg bg-gray-50 text-gray-400 border border-gray-100">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                            <span class="text-sm">"Dark Roast — confidence 71.0%"</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Riwayat Klasifikasi (right) -->
            <div class="scroll-reveal opacity-0 translate-y-8 transition-all duration-700 ease-out
                        rounded-2xl border border-gray-200/80 bg-[#fafafa] p-8 flex flex-col gap-8
                        hover:border-gray-300 hover:shadow-sm transition-shadow">
                <!-- Icon -->
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <!-- Text -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 tracking-tight">Riwayat Klasifikasi</h3>
                    <p class="mt-2 text-base text-gray-500 leading-relaxed max-w-sm">
                        AI belajar dari setiap prediksi. Riwayat tersimpan otomatis — analisis batch berikutnya makin akurat.
                    </p>
                </div>
                <!-- Visual: history list mock -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 flex flex-col gap-2">
                    @foreach([
                        ['Light Roast',  '98.1%', 'bg-amber-100',  'text-amber-700',  '2m lalu'],
                        ['Medium Roast', '94.2%', 'bg-orange-100', 'text-orange-700', '15m lalu'],
                        ['Dark Roast',   '87.5%', 'bg-stone-200',  'text-stone-700',  '1j lalu'],
                        ['Medium Roast', '91.0%', 'bg-orange-100', 'text-orange-700', '3j lalu'],
                    ] as $row)
                    <div class="flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold {{ $row[2] }} {{ $row[3] }}">
                                {{ $row[0] }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-gray-700">{{ $row[1] }}</span>
                            <span class="text-xs text-gray-400">{{ $row[4] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Card 3: Hybrid Tech Stack (full width) -->
            <div id="card-techstack"
                 class="tech-card scroll-reveal opacity-0 translate-y-8 transition-all duration-700 ease-out
                        md:col-span-2 rounded-2xl border border-gray-200/80 bg-[#fafafa] p-8
                        flex flex-col md:flex-row gap-10 items-center md:justify-start
                        relative overflow-hidden cursor-default">

                <!-- Hover grid pattern overlay (hidden by default, shown on hover via JS) -->
                <div class="tech-card-grid pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500
                            [background-image:linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)]
                            [background-size:28px_28px]
                            [mask-image:radial-gradient(ellipse_70%_70%_at_70%_50%,#000_30%,transparent_100%)]">
                </div>

                <!-- Left: Text content -->
                <div class="flex flex-col gap-5 relative z-10 max-w-md">
                    <!-- Icon -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </div>
                    <!-- Text -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 tracking-tight">Dibangun dengan Hybrid Tech Stack</h3>
                        <p class="mt-2.5 text-base text-gray-500 leading-relaxed max-w-md">
                            Frontend & backend diotentikasi oleh <strong class="text-gray-800 font-semibold">Laravel</strong>,
                            inferensi model berjalan di <strong class="text-gray-800 font-semibold">Flask AI API</strong>,
                            dan hasilnya diperkuat oleh model <strong class="text-gray-800 font-semibold">Deep Learning</strong> terlatih.
                            Tiga lapisan, satu pipeline yang solid.
                        </p>
                    </div>
                    <!-- Connector badges -->
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-semibold text-gray-700 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Laravel 11
                        </span>
                        <span class="text-gray-300 text-sm">→</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-semibold text-gray-700 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Flask API
                        </span>
                        <span class="text-gray-300 text-sm">→</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-semibold text-gray-700 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> AI Model
                        </span>
                    </div>
                </div>

                <!-- Right: Floating tech icons -->
                <div class="tech-icons-area relative w-56 h-48 flex items-center justify-center z-10 mx-auto">
                    <!-- Laravel -->
                    <div class="tech-icon tech-icon-laravel
                                absolute flex flex-col items-center gap-2
                                transition-transform duration-700 ease-in-out">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white border border-gray-200 shadow-lg">
                            {{-- Laravel logo SVG --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Laravel--Streamline-Svg-Logos" height="24" width="24">
                            <desc>
                                Laravel Streamline Icon: https://streamlinehq.com
                            </desc>
                            <path fill="#ff2d20" d="M23.401 5.566325c0.00845 0.031325 0.01285 0.063625 0.01285 0.096075v5.044225c0 0.131775 -0.070475 0.253475 -0.1848 0.31905l-4.2337 2.4375v4.831375c0 0.131475 -0.069875 0.25285 -0.1839 0.31905L9.973975 23.701025c-0.020225 0.0115 -0.0423 0.01885 -0.064375 0.02665 -0.008275 0.002775 -0.016075 0.007825 -0.024825 0.010125 -0.061775 0.016275 -0.1267 0.016275 -0.188475 0 -0.010125 -0.00275 -0.019325 -0.008275 -0.028975 -0.01195 -0.020225 -0.00735 -0.041375 -0.0138 -0.060675 -0.024825L0.770995 18.6136c-0.114245 -0.065625 -0.1848125 -0.1873 -0.1848125 -0.31905V3.1619c0 -0.0331 0.0045975 -0.065275 0.0128725 -0.09655 0.0027575 -0.010575 0.009195 -0.020225 0.0128725 -0.0308 0.006895 -0.0193 0.0133325 -0.039075 0.023445 -0.057 0.0068975 -0.01195 0.01701 -0.0216 0.025285 -0.03265 0.010575 -0.0147 0.02023 -0.029875 0.0326425 -0.04275 0.010575 -0.010575 0.024365 -0.0184 0.03632 -0.027575 0.0133325 -0.01105 0.025285 -0.023 0.040455 -0.031725h0.00046L5.1886 0.2991325c0.113825 -0.06551 0.253925 -0.06551 0.367775 0L9.974425 2.84285h0.000925c0.0147 0.0092 0.027125 0.020675 0.04045 0.03125 0.01195 0.0092 0.0253 0.017475 0.035875 0.0276 0.012875 0.013325 0.02205 0.0285 0.0331 0.0432 0.0078 0.01105 0.018375 0.0207 0.024825 0.03265 0.010575 0.0184 0.01655 0.0377 0.0239 0.057 0.003675 0.010575 0.0101 0.020225 0.012875 0.031275 0.00845 0.031325 0.012775 0.063625 0.012875 0.096075v9.4517l3.68155 -2.11985V5.66195c0 -0.0322 0.0046 -0.064825 0.012875 -0.095625 0.003225 -0.01105 0.0092 -0.0207 0.012875 -0.031275 0.00735 -0.0193 0.0138 -0.039075 0.0239 -0.057 0.0069 -0.01195 0.017 -0.0216 0.024825 -0.03265 0.011025 -0.0147 0.020225 -0.029875 0.0331 -0.04275 0.010575 -0.010575 0.0239 -0.0184 0.035875 -0.027575 0.013775 -0.01105 0.025725 -0.023 0.04045 -0.031725h0.00045l4.418525 -2.543725c0.1138 -0.0656 0.25395 -0.0656 0.367775 0L23.2295 5.34335c0.01565 0.0092 0.0276 0.020675 0.041375 0.03125 0.0115 0.0092 0.024825 0.017475 0.0354 0.0276 0.012875 0.013325 0.022075 0.0285 0.0331 0.0432 0.008275 0.01105 0.0184 0.0207 0.024825 0.03265 0.010575 0.017925 0.01655 0.0377 0.023925 0.057 0.004125 0.010575 0.0101 0.020225 0.012875 0.031275ZM22.677375 10.49375V6.299125l-1.5461 0.89005 -2.135925 1.2298v4.194625l3.682475 -2.11985h-0.00045ZM18.259325 18.081675V13.8843l-2.101 1.1999 -5.99955 3.424125v4.2369l8.10055 -4.66355ZM1.3226775 3.798625v14.28305L9.4223 22.744775v-4.236l-4.231425 -2.39475 -0.001375 -0.000925 -0.001825 -0.000925c-0.01425 -0.008275 -0.0262 -0.020225 -0.03955 -0.03035 -0.011475 -0.009175 -0.024825 -0.01655 -0.034925 -0.02665l-0.000925 -0.001375c-0.01195 -0.0115 -0.020225 -0.02575 -0.03035 -0.038625 -0.0092 -0.012425 -0.020225 -0.023 -0.027575 -0.03585l-0.000475 -0.0014c-0.008275 -0.013775 -0.013325 -0.030325 -0.0193 -0.045975 -0.005975 -0.013775 -0.0138 -0.02665 -0.017475 -0.041375v-0.00045c-0.0046 -0.017475 -0.0055 -0.03585 -0.00735 -0.0538 -0.001825 -0.013775 -0.005525 -0.027575 -0.005525 -0.041375V5.918475l-2.13545 -1.23025 -1.5460975 -0.889125v-0.000475ZM5.37295 1.0429825 1.691845 3.1619l3.68018 2.118925 3.680625 -2.119375 -3.680625 -2.1184675h0.000925Zm1.914325 13.2238175 2.135475 -1.229325V3.798625l-1.5461 0.89005 -2.135925 1.2298v9.238825l1.54655 -0.8905Zm11.340275 -10.723325 -3.680625 2.118925 3.680625 2.118925 3.6802 -2.119375 -3.6802 -2.118475Zm-0.368225 4.8755 -2.13595 -1.2298 -1.546075 -0.89005V10.49375l2.135475 1.22935 1.54655 0.8905V8.418975Zm-8.46925 9.4526L15.18875 14.789525l2.69865 -1.540125 -3.6779 -2.117525 -4.2346 2.437975 -3.859475 2.2219 3.67465 2.079825Z" stroke-width="0.25"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600">Laravel</span>
                    </div>

                    <!-- Flask -->
                    <div class="tech-icon tech-icon-flask absolute flex flex-col items-center gap-2
                                transition-transform duration-700 ease-in-out">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white border border-gray-200 shadow-lg">
                            <img src="{{ asset('img/flask.png') }}" alt="Flask" class="h-9 w-9 object-contain">
                        </div>
                        <span class="text-xs font-semibold text-gray-600">Flask</span>
                    </div>

                    <!-- AI / Brain -->
                    <div class="tech-icon tech-icon-ai
                                absolute flex flex-col items-center gap-2
                                transition-transform duration-700 ease-in-out">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white border border-gray-200 shadow-lg">
                        <img src="{{ asset('img/ai-model.png') }}" alt="AI Model" class="h-9 w-9 object-contain">
                    </div>
                        <span class="text-xs font-semibold text-gray-600">AI Model</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================= END FEATURE CARDS ================= -->
 
<!--PERSONAL SECTION -->
<section class="relative py-24 sm:py-32 overflow-hidden border-t border-gray-200/50 bg-[#fafafa]">

    <!-- Background Halftone Pattern -->
    <div class="absolute inset-0 z-0 pointer-events-none
                bg-[radial-gradient(circle_at_center,#9ca3af_1.5px,transparent_1.5px)]
                bg-[length:16px_16px] opacity-[0.15]">
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">

            <!-- Kiri: Konten teks -->
            <div class="flex-1 w-full max-w-2xl lg:max-w-none flex flex-col
                        scroll-reveal opacity-0 translate-y-8 transition-all duration-1000 ease-out">

                <span class="text-xs font-medium tracking-widest text-gray-400 uppercase">Tentang Pembuat</span>

                <h2 class="mt-4 text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 leading-tight" style="font-family: 'Poppins', serif;">
                    HI!, Saya <br class="hidden sm:block"> Rahmat Maliki
                </h2>

                <p class="mt-6 text-sm leading-relaxed text-gray-500 max-w-xl" style="font-family: 'Poppins', sans-serif; ">
                    Klasifikasi kopi seharusnya tidak butuh keahlian khusus. Dengan latar belakang
                    di bidang <strong class="text-gray-800 font-semibold">machine learning</strong> dan
                    kecintaan pada dunia perkopian, saya membangun BeanRoast ML untuk
                    menjembatani petani, roaster, dan konsumen lewat teknologi yang mudah dipakai.
                </p>

                <p class="mt-4 text-sm leading-relaxed text-gray-500 max-w-xl" style="font-family: 'Poppins', sans-serif; ">
                    Proyek ini dibangun sebagai tugas akhir skripsi dengan tujuan nyata membantu
                    industri kopi lokal meningkatkan standar kualitas menggunakan
                    <strong class="text-gray-800 font-semibold">deep learning (CNN)</strong> yang berjalan
                    langsung di browser, dengan memanfaatkan Model terbaru dari <strong class="text-gray-800 font-semibold">Arsitektur MobileNet V3.</strong>
                </p>

                <!-- Signature -->
                <div class="mt-10 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-gray-900 flex items-center justify-center text-white flex-shrink-0">
                        <img src=" {{ asset('img/candraa.jpg') }}" alt="RM" class="rounded-full">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-semibold text-gray-900">{{ config('app.author', 'Developer BeanRoast ML') }}</span>
                        <span class="text-sm text-gray-500">Builder & Researcher</span>
                    </div>
                </div>

                <!-- Stack mini badges -->
                <div class="mt-8 flex flex-wrap gap-2">
                    @php
                        $technologies = [
                            ['name' => 'Laravel 11', 'color' => 'bg-red-500'],
                            ['name' => 'Flask', 'color' => 'bg-blue-500'],
                            ['name' => 'TensorFlow', 'color' => 'bg-orange-500'],
                            ['name' => 'Python', 'color' => 'bg-yellow-500'],
                            ['name' => 'Tailwind CSS', 'color' => 'bg-cyan-500']
                        ];
                    @endphp
                    @foreach($technologies as $tech)
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-gray-200
                                 text-xs font-semibold text-gray-600 shadow-sm">
                        <span class="w-2 h-2 rounded-full {{ $tech['color'] }}"></span>
                        {{ $tech['name'] }}
                    </span>
                    @endforeach
                </div>
            </div>

            <!-- Kanan: Foto profil -->
            <div class="flex-1 w-full max-w-lg lg:max-w-none relative
                        scroll-reveal opacity-0 translate-y-12 transition-all duration-1000 ease-out"
                 style="transition-delay: 150ms;">

                <!-- Dekorasi latar belakang -->
                <div class="absolute -inset-4 rounded-3xl bg-gray-200/60 border border-gray-300/40 -rotate-3 z-0"></div>
                <div class="absolute -inset-4 rounded-3xl bg-gray-100/60 border border-gray-200/40 rotate-2 z-0"></div>

                <!-- Foto utama -->
                <div class="relative z-10 aspect-[4/5] sm:aspect-square lg:aspect-[4/5] w-full
                            rounded-2xl overflow-hidden shadow-2xl shadow-gray-200/50
                            border border-white/80 bg-gray-100">
                    <img src="{{ asset('img/profile.jpeg') }}"
                         alt="Foto Profil"
                         class="h-full w-full object-cover object-center grayscale hover:grayscale-0 transition-all duration-700"
                         onerror="this.style.display='none'; this.parentElement.classList.add('profile-placeholder')">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================= END PERSONAL INTRO ================= -->


@push('scripts')
<style>
    .scroll-reveal {
        opacity: 0;
        transform: translateY(2rem);
        transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .scroll-reveal.visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    [data-step] {
        opacity: 0;
        transform: translateY(3rem);
        transition: opacity 0.7s ease, transform 0.7s ease;
    }
    [data-step].visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    @keyframes nodeActivate {
        0%   { box-shadow: 0 0 0 0 rgba(17,17,17,0.40); }
        60%  { box-shadow: 0 0 0 10px rgba(17,17,17,0); }
        100% { box-shadow: 0 0 0 0 rgba(17,17,17,0); }
    }
    .timeline-node { transition: background-color 0.35s ease, transform 0.35s ease; }
    .timeline-node.is-active {
        background-color: #111 !important;
        transform: scale(1.3);
        animation: nodeActivate 0.65s ease-out forwards;
    }
    #timeline-progress {
        position: absolute;
        width: 1px;
        top: 0;
        left: 23px;
        height: 0;
        background: #111;
        z-index: 1;
        will-change: height;
    }
    @media (min-width: 768px) {
        #timeline-progress { left: 50%; transform: translateX(-50%); }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // Hapus semua inline Tailwind opacity/translate classes dulu
    document.querySelectorAll(".scroll-reveal, [data-step]").forEach(el => {
        el.classList.remove("opacity-0","translate-y-8","translate-y-12");
    });

    // Scroll reveal umum
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add("visible");
                revealObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });

    document.querySelectorAll(".scroll-reveal").forEach(el => revealObs.observe(el));

    // Staggered steps
    const stepEls = Array.from(document.querySelectorAll("[data-step]"));
    const stepObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const delay = parseInt(e.target.dataset.step) * 120;
                setTimeout(() => e.target.classList.add("visible"), delay);
                stepObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });

    stepEls.forEach(el => stepObs.observe(el));

    // Timeline progress line
    const container = document.getElementById("timeline-container");
    const fillLine  = document.getElementById("timeline-progress");
    const nodes     = stepEls.map(el => el.querySelector(".timeline-node"));
    if (!container || !fillLine || !nodes.length) return;

    const getNodeOffset = (node) => {
        let el = node, top = 0;
        while (el && el !== container) { top += el.offsetTop; el = el.offsetParent; }
        return top + node.offsetHeight / 2;
    };

    const update = () => {
        const rect   = container.getBoundingClientRect();
        const filled = (window.innerHeight * 0.65) - rect.top;
        const fillPx = Math.max(0, Math.min(container.offsetHeight, filled));
        fillLine.style.height = fillPx + "px";
        nodes.forEach(node => {
            if (node) node.classList.toggle("is-active", fillPx >= getNodeOffset(node));
        });
    };

    update();
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
});
</script>
@endpush
@endsection