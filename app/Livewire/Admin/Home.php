<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Subkegiatan as SubkegiatanModel, Aktivitas};


class Home extends Component
{

    public function render()
    {
        $jumlah_anggaran = SubkegiatanModel::sum('anggaran');
        $jumlah_nominal = Aktivitas::sum('nominal');
        $sisa_anggaran = $jumlah_anggaran - $jumlah_nominal;
        $progress = ($jumlah_nominal / $jumlah_anggaran) * 100;
        // dd($progress);

        return view('livewire.admin.home',[
            'sisa_anggaran' => $sisa_anggaran,
            'progress' => $progress,
            'total_anggaran' => $jumlah_anggaran,
            'total_nominal' => $jumlah_nominal
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
