<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CoffeeBeans;
use App\Models\BatchConfusionMatrix;

class HistoryDashboard extends Component
{
    use WithPagination;

    public string $activeTab = 'all';

    // Counts
    public int $singleCount  = 0;
    public int $batchCount   = 0;
    public int $folderCount  = 0;

    // Latest previews for "all" tab (1 per mode)
    public $latestSingle = null;
    public $latestBatch  = null;   // collection (items of latest batch_id)
    public $latestFolder = null;   // collection (items of latest folder batch_id)

    protected $paginationTheme = 'tailwind';

    // Reset pagination when tab changes
    public function updatingActiveTab(): void
    {
        $this->resetPage('singlePage');
        $this->resetPage('batchPage');
        $this->resetPage('folderPage');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function mount(): void
    {
        $this->loadCounts();
        $this->loadPreviews();
    }

    private function loadCounts(): void
    {
        $this->singleCount = CoffeeBeans::where('upload_mode', 'single')->count();
        $this->batchCount  = CoffeeBeans::where('upload_mode', 'batch')
            ->whereNotNull('batch_id')->distinct('batch_id')->count('batch_id');
        $this->folderCount = CoffeeBeans::where('upload_mode', 'folder')
            ->whereNotNull('batch_id')->distinct('batch_id')->count('batch_id');
    }

    private function loadPreviews(): void
    {
        // Latest single record
        $this->latestSingle = CoffeeBeans::where('upload_mode', 'single')->latest()->first();

        // Latest batch group → all items of that batch_id
        $latestBatchId = CoffeeBeans::where('upload_mode', 'batch')
            ->whereNotNull('batch_id')->latest()->value('batch_id');
        if ($latestBatchId) {
            $this->latestBatch = CoffeeBeans::where('batch_id', $latestBatchId)
                ->orderBy('batch_sequence')->get();
        }

        // Latest folder group → all items of that folder batch_id
        $latestFolderId = CoffeeBeans::where('upload_mode', 'folder')
            ->whereNotNull('batch_id')->latest()->value('batch_id');
        if ($latestFolderId) {
            $this->latestFolder = CoffeeBeans::where('batch_id', $latestFolderId)
                ->orderBy('batch_sequence')->get();
        }
    }

    public function render()
    {
        // Only query the active tab data to avoid unnecessary DB hits
        $singleItems  = null;
        $batchGroups  = null;
        $folderGroups = null;

        if ($this->activeTab === 'single') {
            $singleItems = CoffeeBeans::where('upload_mode', 'single')
                ->latest()
                ->paginate(12, ['*'], 'singlePage');
        }

        if ($this->activeTab === 'batch') {
            $batchGroups = CoffeeBeans::where('upload_mode', 'batch')
                ->whereNotNull('batch_id')
                ->select('batch_id', 'batch_total', 'upload_mode', 'created_at')
                ->groupBy('batch_id', 'batch_total', 'upload_mode', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'batchPage');

            // Attach stats to each group
            $batchGroups->getCollection()->transform(
                fn($g) => tap($g, fn($g) => $g->stats = $this->groupStats($g->batch_id))
            );
        }

        if ($this->activeTab === 'folder') {
            $folderGroups = CoffeeBeans::where('upload_mode', 'folder')
                ->whereNotNull('batch_id')
                ->select('batch_id', 'batch_total', 'upload_mode', 'created_at')
                ->groupBy('batch_id', 'batch_total', 'upload_mode', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'folderPage');

            $folderGroups->getCollection()->transform(
                fn($g) => tap($g, fn($g) => $g->stats = $this->groupStats($g->batch_id))
            );
        }

        return view('livewire.history-dashboard', compact(
            'singleItems', 'batchGroups', 'folderGroups'
        ));
    }

    private function groupStats(string $batchId): array
    {
        $items = CoffeeBeans::where('batch_id', $batchId)->get();
        $total = $items->count();
        $hasCM = BatchConfusionMatrix::where('batch_id', $batchId)->exists();

        return [
            'total'           => $total,
            'classifications' => $items->groupBy('final_classification')->map->count()->toArray(),
            'avg_confidence'  => $total > 0
                ? round(($items->avg('confidence_small') + $items->avg('confidence_large')) / 2, 1)
                : 0,
            'agreement_rate'  => $total > 0
                ? round($items->where('models_agree', true)->count() / $total * 100, 1)
                : 0,
            'has_confusion_matrix' => $hasCM,
        ];
    }
}