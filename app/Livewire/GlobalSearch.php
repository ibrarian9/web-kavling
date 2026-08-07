<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\Unit;
use App\Models\Worker;
use Livewire\Component;

class GlobalSearch extends Component
{
    public bool $isOpen = false;
    public string $query = '';

    protected $listeners = ['openGlobalSearch' => 'openModal'];

    public function openModal(): void
    {
        $this->isOpen = true;
        $this->query = '';
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->query = '';
    }

    public function render()
    {
        $results = [
            'menus' => [],
            'units' => [],
            'projects' => [],
            'bookings' => [],
            'workers' => [],
            'documents' => [],
        ];

        $q = trim($this->query);

        // Predefined App Menus
        $allMenus = [
            ['title' => 'Dashboard Utama', 'category' => 'Menu', 'url' => route('dashboard'), 'icon' => 'home'],
            ['title' => 'Proyek Properti', 'category' => 'Menu', 'url' => route('projects.index'), 'icon' => 'office-building'],
            ['title' => 'Unit Kavling & Rumah', 'category' => 'Menu', 'url' => route('units.index'), 'icon' => 'home'],
            ['title' => 'Booking Fee & DP', 'category' => 'Menu', 'url' => route('bookings.index'), 'icon' => 'cash'],
            ['title' => 'Pengajuan & Approval Proposal', 'category' => 'Menu', 'url' => route('proposals.index'), 'icon' => 'clipboard-check'],
            ['title' => 'Surat Resmi SPP (PDF)', 'category' => 'Menu', 'url' => route('documents.index'), 'icon' => 'document-text'],
            ['title' => 'Cicilan Pembeli & Skema', 'category' => 'Menu', 'url' => route('installments.index'), 'icon' => 'credit-card'],
            ['title' => 'Arus Kas Global & Konsolidasi', 'category' => 'Menu', 'url' => route('cashflow.index'), 'icon' => 'chart-bar'],
            ['title' => 'Invoice Manual', 'category' => 'Menu', 'url' => route('manual-invoices.index'), 'icon' => 'receipt'],
            ['title' => 'Mandor & Tukang Lapangan', 'category' => 'Menu', 'url' => route('workers.index'), 'icon' => 'user-group'],
            ['title' => 'Laporan Belanja & Upah', 'category' => 'Menu', 'url' => route('field-expenses.index'), 'icon' => 'shopping-cart'],
            ['title' => 'Penggajian Karyawan & Slip Gaji', 'category' => 'Menu', 'url' => route('employee-salaries.index'), 'icon' => 'banknotes'],
            ['title' => 'Profil Akun & Legalitas Perusahaan', 'category' => 'Menu', 'url' => route('profile.index'), 'icon' => 'user'],
            ['title' => 'Tutorial & Panduan Sistem', 'category' => 'Menu', 'url' => route('tutorial.index'), 'icon' => 'academic-cap'],
        ];

        if ($q === '') {
            $results['menus'] = array_slice($allMenus, 0, 5);
        } else {
            $s = '%' . strtolower($q) . '%';

            // Filter menus
            $results['menus'] = array_values(array_filter($allMenus, function ($m) use ($q) {
                return stripos($m['title'], $q) !== false || stripos($m['category'], $q) !== false;
            }));

            // Filter Units
            $results['units'] = Unit::with('project')
                ->where(function ($queryBuilder) use ($s) {
                    $queryBuilder->where('code', 'like', $s)
                        ->orWhere('category', 'like', $s)
                        ->orWhere('type', 'like', $s)
                        ->orWhere('status', 'like', $s)
                        ->orWhereHas('project', function ($pq) use ($s) {
                            $pq->where('name', 'like', $s);
                        });
                })
                ->take(5)
                ->get();

            // Filter Projects
            $results['projects'] = Project::where('name', 'like', $s)
                ->orWhere('location', 'like', $s)
                ->take(4)
                ->get();

            // Filter Bookings / Buyers
            $results['bookings'] = Booking::with('unit')
                ->where('buyer_name', 'like', $s)
                ->orWhere('buyer_phone', 'like', $s)
                ->take(4)
                ->get();

            // Filter Workers / Mandor
            $results['workers'] = Worker::where('name', 'like', $s)
                ->orWhere('phone', 'like', $s)
                ->orWhere('specialty', 'like', $s)
                ->take(4)
                ->get();

            // Filter Official Documents (SPP)
            $results['documents'] = OfficialDocument::where('document_number', 'like', $s)
                ->orWhere('buyer_name', 'like', $s)
                ->take(4)
                ->get();
        }

        return view('livewire.global-search', [
            'results' => $results,
        ]);
    }
}
