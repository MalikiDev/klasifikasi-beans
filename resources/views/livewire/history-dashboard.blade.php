<div class="w-full">

    @php
    $classStyle = [
        'Dark'   => ['badge' => 'bg-gray-900 text-white',        'bar' => 'bg-gray-800'],
        'Green'  => ['badge' => 'bg-green-100 text-green-800',   'bar' => 'bg-green-400'],
        'Light'  => ['badge' => 'bg-yellow-100 text-yellow-800', 'bar' => 'bg-yellow-300'],
        'Medium' => ['badge' => 'bg-orange-100 text-orange-800', 'bar' => 'bg-orange-400'],
    ];
    @endphp

    {{-- ══ TAB NAV ══ --}}
    <div class="flex items-center gap-0 mb-8 border-b border-gray-200 overflow-x-auto">
        @foreach([
            ['key'=>'all',    'label'=>'Overview',     'count'=>null],
            ['key'=>'single', 'label'=>'Single Image', 'count'=>$singleCount],
            ['key'=>'batch',  'label'=>'Batch',        'count'=>$batchCount],
            ['key'=>'folder', 'label'=>'Folder ZIP',   'count'=>$folderCount],
        ] as $tab)
        <button wire:click="setTab('{{ $tab['key'] }}')"
                class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold whitespace-nowrap transition-colors -mb-px border-b-2
                       {{ $activeTab===$tab['key'] ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
            {{ $tab['label'] }}
            @if($tab['count'] !== null)
            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $activeTab===$tab['key'] ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }}">
                {{ $tab['count'] }}
            </span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- ══ TAB: OVERVIEW ══ --}}
    @if($activeTab === 'all')
    @php
        $bFirst = $latestBatch?->first();
        $bTotal = $latestBatch?->count() ?? 0;
        $bDist  = $bTotal > 0 ? $latestBatch->groupBy('final_classification')->map->count() : collect();
        $bAgree = $bTotal > 0 ? round($latestBatch->where('models_agree',true)->count()/$bTotal*100) : 0;
        $bConf  = $bTotal > 0 ? round(($latestBatch->avg('confidence_small')+$latestBatch->avg('confidence_large'))/2,1) : 0;
        $fFirst = $latestFolder?->first();
        $fTotal = $latestFolder?->count() ?? 0;
        $fDist  = $fTotal > 0 ? $latestFolder->groupBy('final_classification')->map->count() : collect();
        $fAgree = $fTotal > 0 ? round($latestFolder->where('models_agree',true)->count()/$fTotal*100) : 0;
        $fConf  = $fTotal > 0 ? round(($latestFolder->avg('confidence_small')+$latestFolder->avg('confidence_large'))/2,1) : 0;
        $fHasCM = $fFirst ? \App\Models\BatchConfusionMatrix::where('batch_id',$fFirst->batch_id)->exists() : false;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch">

        {{-- SINGLE CARD --}}
        <div class="bg-white rounded-2xl border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all flex flex-col">
            <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 leading-tight">Single Image</p>
                        <p class="text-[10px] text-gray-400">Klasifikasi satu gambar</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full flex-shrink-0">{{ $singleCount }}</span>
            </div>
            <div class="flex-1 p-5 flex flex-col">
                @if($latestSingle)
                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-gray-100 mb-4 flex-shrink-0">
                        @if($latestSingle->image_path)
                            <img src="{{ asset('storage/'.$latestSingle->image_path) }}" class="w-full h-full object-cover" alt="{{ $latestSingle->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-semibold text-gray-900 truncate mb-2">{{ $latestSingle->name }}</p>
                    <div class="flex items-center gap-2 mb-3">
                        @if($latestSingle->final_classification)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $classStyle[$latestSingle->final_classification]['badge'] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $latestSingle->final_classification }}
                        </span>
                        @endif
                        <span class="text-[10px] font-semibold {{ $latestSingle->models_agree ? 'text-green-600' : 'text-amber-500' }}">
                            {{ $latestSingle->models_agree ? '✓ Agree' : '⚠ Differ' }}
                        </span>
                        <span class="ml-auto text-[10px] text-gray-400 flex-shrink-0">{{ $latestSingle->created_at->diffForHumans() }}</span>
                    </div>
                    @if($latestSingle->confidence_small && $latestSingle->confidence_large)
                    <div class="space-y-1.5 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400 w-10 flex-shrink-0">Small</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-gray-400 rounded-full" style="width:{{ $latestSingle->confidence_small }}%"></div></div>
                            <span class="text-[10px] font-bold text-gray-600 w-8 text-right flex-shrink-0">{{ number_format($latestSingle->confidence_small,0) }}%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400 w-10 flex-shrink-0">Large</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-gray-900 rounded-full" style="width:{{ $latestSingle->confidence_large }}%"></div></div>
                            <span class="text-[10px] font-bold text-gray-600 w-8 text-right flex-shrink-0">{{ number_format($latestSingle->confidence_large,0) }}%</span>
                        </div>
                    </div>
                    @endif
                    <div class="mt-auto">
                        <a href="{{ route('coffee.show', $latestSingle) }}" class="block w-full text-center py-2 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-gray-700 transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <p class="text-xs text-gray-400">Belum ada data</p>
                    </div>
                @endif
            </div>
            <div class="px-5 pb-5">
                <button wire:click="setTab('single')" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    Lihat Semua ( {{ $singleCount }} sesi ) <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

        {{-- BATCH CARD --}}
        <div class="bg-white rounded-2xl border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all flex flex-col">
            <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 leading-tight">Batch Upload</p>
                        <p class="text-[10px] text-gray-400">Multiple gambar sekaligus</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full flex-shrink-0">{{ $batchCount }} sesi</span>
            </div>
            <div class="flex-1 p-5 flex flex-col">
                @if($bFirst && $bTotal > 0)
                    {{-- 2x2 thumbnail grid, same aspect-video height as single --}}
                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-gray-100 mb-4 flex-shrink-0">
                        <div class="grid grid-cols-2 grid-rows-2 h-full gap-0.5 p-0.5">
                            @foreach($latestBatch->take(4) as $item)
                            <div class="overflow-hidden rounded-lg bg-gray-200">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                            @for($i = $latestBatch->take(4)->count(); $i < 4; $i++)
                            <div class="rounded-lg bg-gray-100"></div>
                            @endfor
                        </div>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-mono text-[10px] text-gray-400 truncate max-w-[160px]">{{ Str::limit($bFirst->batch_id, 20) }}</p>
                        <span class="text-[10px] text-gray-400 flex-shrink-0">{{ $bFirst->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold text-gray-900">{{ $bTotal }}</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Gambar</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold text-gray-900">{{ $bConf }}%</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Avg Conf</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold {{ $bAgree>=80?'text-green-600':'text-amber-500' }}">{{ $bAgree }}%</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Agree</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex h-1.5 rounded-full overflow-hidden gap-px mb-2">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @php $cnt=$bDist[$cls]??0; $pct=$bTotal>0?($cnt/$bTotal)*100:0; @endphp
                                @if($pct>0)<div class="{{ $classStyle[$cls]['bar'] }}" style="width:{{ $pct }}%" title="{{ $cls }}: {{ $cnt }}"></div>@endif
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @if(($bDist[$cls]??0)>0)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $classStyle[$cls]['badge'] }}">{{ $cls }} · {{ $bDist[$cls] }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('coffee.batch-results', $bFirst->batch_id) }}" class="block w-full text-center py-2 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-gray-700 transition-colors">
                            Lihat Batch Terbaru
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <p class="text-xs text-gray-400">Belum ada data</p>
                    </div>
                @endif
            </div>
            <div class="px-5 pb-5">
                <button wire:click="setTab('batch')" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    Lihat Semua ( {{ $batchCount }} sesi ) <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

        {{-- FOLDER CARD --}}
        <div class="bg-white rounded-2xl border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all flex flex-col">
            <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 leading-tight">Folder ZIP</p>
                        <p class="text-[10px] text-gray-400">Upload ZIP dengan subfolder</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full flex-shrink-0">{{ $folderCount }} sesi</span>
            </div>
            <div class="flex-1 p-5 flex flex-col">
                @if($fFirst && $fTotal > 0)
                    {{-- Bar chart visual, same aspect-video height --}}
                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-gray-50 border border-gray-100 mb-4 flex-shrink-0 flex items-end justify-center px-6 pb-3 pt-4 gap-3">
                        @foreach(['Dark','Green','Light','Medium'] as $cls)
                        @php $cnt=$fDist[$cls]??0; $pct=$fTotal>0?round(($cnt/$fTotal)*100):0; @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5">
                            <span class="text-[9px] font-bold text-gray-600">{{ $cnt }}</span>
                            <div class="w-full bg-gray-200 rounded-t-md overflow-hidden" style="height:60px">
                                <div class="{{ $classStyle[$cls]['bar'] }} w-full rounded-t-md transition-all" style="height:{{ max($pct,3) }}%"></div>
                            </div>
                            <span class="text-[9px] font-bold text-gray-500">{{ Str::limit($cls,1) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-mono text-[10px] text-gray-400 truncate max-w-[160px]">{{ Str::limit($fFirst->batch_id, 20) }}</p>
                        <span class="text-[10px] text-gray-400 flex-shrink-0">{{ $fFirst->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold text-gray-900">{{ $fTotal }}</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Gambar</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold text-gray-900">{{ $fConf }}%</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Avg Conf</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5 text-center border border-gray-100">
                            <p class="text-sm font-bold {{ $fAgree>=80?'text-green-600':'text-amber-500' }}">{{ $fAgree }}%</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-wide mt-0.5">Agree</p>
                        </div>
                    </div>
                    <div class="mb-3 min-h-[2rem]">
                        @if($fHasCM)
                        <div class="flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-100">
                            <svg class="w-3.5 h-3.5 text-green-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span class="text-[10px] font-semibold text-green-700">Confusion Matrix tersedia</span>
                        </div>
                        @endif
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('coffee.folder-results', $fFirst->batch_id) }}" class="block w-full text-center py-2 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-gray-700 transition-colors">
                            Lihat Folder Terbaru
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400">Belum ada data</p>
                    </div>
                @endif
            </div>
            <div class="px-5 pb-5">
                <button wire:click="setTab('folder')" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    Lihat Semua ( {{ $folderCount }} sesi ) <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ══ TAB: SINGLE ══ --}}
    @if($activeTab === 'single')
    <div>
        @if($singleItems && $singleItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($singleItems as $bean)
            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-gray-300 transition-all">
                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                    @if($bean->image_path)
                        <img src="{{ asset('storage/'.$bean->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                    @if($bean->final_classification)
                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/90 backdrop-blur {{ $classStyle[$bean->final_classification]['badge'] ?? '' }}">
                        {{ $bean->final_classification }}
                    </span>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-sm font-semibold text-gray-900 truncate mb-2">{{ $bean->name }}</p>
                    @if($bean->confidence_small && $bean->confidence_large)
                    <div class="space-y-1 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400 w-9">Small</span>
                            <div class="flex-1 h-1 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-gray-400 rounded-full" style="width:{{ $bean->confidence_small }}%"></div></div>
                            <span class="text-[10px] font-bold text-gray-600 w-8 text-right">{{ number_format($bean->confidence_small,0) }}%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400 w-9">Large</span>
                            <div class="flex-1 h-1 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-gray-900 rounded-full" style="width:{{ $bean->confidence_large }}%"></div></div>
                            <span class="text-[10px] font-bold text-gray-600 w-8 text-right">{{ number_format($bean->confidence_large,0) }}%</span>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-gray-400">{{ $bean->created_at->diffForHumans() }}</span>
                        <a href="{{ route('coffee.show', $bean) }}" class="text-[10px] font-bold text-gray-900 hover:underline">Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $singleItems->links() }}</div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Belum ada data Single</p>
            <p class="text-xs text-gray-400">Upload gambar tunggal untuk mulai.</p>
        </div>
        @endif
    </div>
    @endif

    {{-- ══ TAB: BATCH ══ --}}
    @if($activeTab === 'batch')
    <div>
        @if($batchGroups && $batchGroups->count() > 0)
        <div class="space-y-3">
            @foreach($batchGroups as $group)
            @php $s = $group->stats; @endphp
            <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4 px-5 py-4 flex-wrap sm:flex-nowrap">
                    <div class="min-w-0 flex-1">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 mb-1">Batch</span>
                        <p class="font-mono text-xs text-gray-500 truncate">{{ $group->batch_id }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($group->created_at)->format('d M Y, H:i') }} · {{ \Carbon\Carbon::parse($group->created_at)->diffForHumans() }}</p>
                    </div>
                    <div class="hidden sm:block flex-1 min-w-0">
                        <div class="flex h-1.5 rounded-full overflow-hidden gap-px mb-2">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @php $cnt=$s['classifications'][$cls]??0; $pct=$s['total']>0?($cnt/$s['total'])*100:0; @endphp
                                @if($pct>0)<div class="{{ $classStyle[$cls]['bar'] }}" style="width:{{ $pct }}%"></div>@endif
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @if(($s['classifications'][$cls]??0)>0)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $classStyle[$cls]['badge'] }}">{{ $cls }} {{ $s['classifications'][$cls] }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0 text-center">
                        <div><p class="text-base font-bold text-gray-900">{{ $s['total'] }}</p><p class="text-[9px] text-gray-400 uppercase">Gambar</p></div>
                        <div><p class="text-base font-bold text-gray-900">{{ $s['avg_confidence'] }}%</p><p class="text-[9px] text-gray-400 uppercase">Conf</p></div>
                        <div><p class="text-base font-bold {{ $s['agreement_rate']>=80?'text-green-600':'text-amber-500' }}">{{ $s['agreement_rate'] }}%</p><p class="text-[9px] text-gray-400 uppercase">Agree</p></div>
                        <a href="{{ route('coffee.batch-results', $group->batch_id) }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-gray-700 transition-colors whitespace-nowrap">Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $batchGroups->links() }}</div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Belum ada data Batch</p>
            <p class="text-xs text-gray-400">Upload batch gambar untuk mulai.</p>
        </div>
        @endif
    </div>
    @endif

    {{-- ══ TAB: FOLDER ══ --}}
    @if($activeTab === 'folder')
    <div>
        @if($folderGroups && $folderGroups->count() > 0)
        <div class="space-y-3">
            @foreach($folderGroups as $group)
            @php $s = $group->stats; @endphp
            <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4 px-5 py-4 flex-wrap sm:flex-nowrap">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">Folder ZIP</span>
                            @if($s['has_confusion_matrix'])<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">✓ CM</span>@endif
                        </div>
                        <p class="font-mono text-xs text-gray-500 truncate">{{ $group->batch_id }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($group->created_at)->format('d M Y, H:i') }} · {{ \Carbon\Carbon::parse($group->created_at)->diffForHumans() }}</p>
                    </div>
                    <div class="hidden sm:block flex-1 min-w-0">
                        <div class="flex h-1.5 rounded-full overflow-hidden gap-px mb-2">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @php $cnt=$s['classifications'][$cls]??0; $pct=$s['total']>0?($cnt/$s['total'])*100:0; @endphp
                                @if($pct>0)<div class="{{ $classStyle[$cls]['bar'] }}" style="width:{{ $pct }}%"></div>@endif
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach(['Dark','Green','Light','Medium'] as $cls)
                                @if(($s['classifications'][$cls]??0)>0)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $classStyle[$cls]['badge'] }}">{{ $cls }} {{ $s['classifications'][$cls] }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0 text-center">
                        <div><p class="text-base font-bold text-gray-900">{{ $s['total'] }}</p><p class="text-[9px] text-gray-400 uppercase">Gambar</p></div>
                        <div><p class="text-base font-bold text-gray-900">{{ $s['avg_confidence'] }}%</p><p class="text-[9px] text-gray-400 uppercase">Conf</p></div>
                        <div><p class="text-base font-bold {{ $s['agreement_rate']>=80?'text-green-600':'text-amber-500' }}">{{ $s['agreement_rate'] }}%</p><p class="text-[9px] text-gray-400 uppercase">Agree</p></div>
                        <a href="{{ route('coffee.folder-results', $group->batch_id) }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-gray-700 transition-colors whitespace-nowrap">Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $folderGroups->links() }}</div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Belum ada data Folder</p>
            <p class="text-xs text-gray-400">Upload ZIP folder untuk mulai.</p>
        </div>
        @endif
    </div>
    @endif 
</div>