<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Aktivitas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $tanggal_mulai, $tanggal_selesai, $tempat, $penyelenggara, $keterangan, $subkegiatan_id,$aktivitas_id;

    public $modal = true;

    // public $aktivitas;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerpage()
    {
        $this->resetPage();
    }

    public function setPerPage($value)
    {
        $this->perpage = $value;
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.admin.aktivitas',[
            'aktivitas' => \App\Models\Aktivitas::where('penyelenggara', 'like', '%'.$this->search.'%')
                            ->orWhere('tempat', 'like', '%'.$this->search.'%')
                            ->orWhere('keterangan', 'like', '%'.$this->search.'%')
                            ->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Data Aktivitas']);
    }
}
