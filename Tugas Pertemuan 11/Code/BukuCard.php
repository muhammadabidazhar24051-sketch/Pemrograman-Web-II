<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Buku;

class BukuCard extends Component
{
    /**
     * Data buku yang akan ditampilkan di card.
     */
    public Buku $buku;

    /**
     * Menentukan apakah tombol aksi (Detail & Edit) ditampilkan.
     */
    public bool $showActions;

    /**
     * Inisialisasi property component.
     */
    public function __construct(Buku $buku, bool $showActions = true)
    {
        $this->buku        = $buku;
        $this->showActions = $showActions;
    }

    /**
     * Mengarahkan ke file view component ini.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('components.buku-card');
    }
}