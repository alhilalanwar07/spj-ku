<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Kegiatan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $kode_rekening_program, $nama_program, $istilah_program, $program_id;
    public $modal = true;

    // public $updatesubprogram = false;

    public $subprogram_id;
    // public $kode_rekening_subprogram = [];
    // public $nama_subprogram = [];
    public $isEditing = false;

    public $isTambahSub = false;
    // public $kode_rekening_subprogram_baru;
    // public $nama_subprogram_baru;

    // public $programs, $subprograms, $kegiatans, $subkegiatans;

    public  $kegiatanPilih, $subkegiatanPilih;

    public $programs;
    public $subprograms = [];
    public $kegiatans = [];
    public $programPilih = '';
    public $subProgramPilih = '';

    public $kode_rekening_kegiatan = [];
    public $nama_kegiatan = [];

    public function mount()
    {
        $this->programs = \App\Models\Program::all();
    }

    public function addKegiatanInput()
    {
        $this->kode_rekening_kegiatan[] = '';
        $this->nama_kegiatan[] = '';
    }

    public function removeKegiatanInput($index)
    {
        unset($this->kode_rekening_kegiatan[$index]);
        unset($this->nama_kegiatan[$index]);
        $this->kode_rekening_kegiatan = array_values($this->kode_rekening_kegiatan);
        $this->nama_kegiatan = array_values($this->nama_kegiatan);
    }

    public function updatedProgramPilih($value)
    {
        $this->subprograms = \App\Models\Subprogram::where('program_id', $value)->get();
        $this->kegiatans = \App\Models\Kegiatan::whereIn('subprogram_id', $this->subprograms->pluck('id'))->with('subkegiatans')->get();
        // $this->kegiatans = \App\Models\Kegiatan::where('subprogram_id',
        $this->subProgramPilih = '';
    }

    public function updatedSubProgramPilih($value)
    {
        $this->kegiatans = \App\Models\Kegiatan::where('subprogram_id', $value)->with('subkegiatans')->get();
    }

    public function render()
    {
        // $query = \App\Models\Kegiatan::orderBy('kode_rekening_kegiatan', 'asc');

        // $this->kegiatans = $query->get();
        return view('livewire.admin.kegiatan',[
            // 'kegiatans' => $this->kegiatans
        ])->layout('components.layouts.app', ['title' => 'Data Kegiatan']);
    }

    public function resetInput()
    {
        $this->program_id = '';
        $this->subprogram_id = '';
        $this->kegiatanPilih = '';
        $this->subkegiatanPilih = '';
        $this->kode_rekening_kegiatan = [];
        $this->nama_kegiatan = [];
        $this->isEditing = false;
    }

    public function simpan()
    {
        foreach ($this->kode_rekening_kegiatan as $index => $kode) {
            \App\Models\Kegiatan::create([
                'subprogram_id' => $this->subprogram_id,
                'kode_rekening_kegiatan' => $kode,
                'nama_kegiatan' => $this->nama_kegiatan[$index]
            ]);
        }

        $this->dispatch('updateAlertToast', [
            'title' => 'Sukses',
            'text' => 'Kegiatan berhasil disimpan',
            'type' => 'success',
            'timeout' => 2000,
        ]);

        // tetap ambil data
        if ($this->subprogram_id) {
            $this->kegiatans = \App\Models\Kegiatan::where('subprogram_id', $this->subprogram_id)->with('subkegiatans')->get();
            $this->subProgramPilih = $this->subprogram_id;
            $this->programPilih = \App\Models\Subprogram::find($this->subprogram_id)->program_id;
        } else {
            $this->kegiatans = collect();
        }
    }

    public function hapus($id)
    {
        $cekSubkegiatan = \App\Models\Subkegiatan::where('kegiatan_id', $id)->count();
        if ($cekSubkegiatan > 0) {
            $this->dispatch('updateAlert', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Kegiatan Tidak Bisa Dihapus Karena Memiliki Sub Kegiatan',
                'type'      => 'error',
                'timeout'   => 1500
            ]);
            return;
        }

        \App\Models\Kegiatan::find($id)->delete();

        $this->dispatch('updateAlertToast', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Kegiatan Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1500
        ]);

        // tetap ambil data
        if ($this->subprograms !== null) {
            $this->kegiatans = \App\Models\Kegiatan::whereIn('subprogram_id', $this->subprograms->pluck('id'))->with('subkegiatans')->get();
        } elseif ($this->subprograms === null && $this->programs !== null) {
            $this->kegiatans = \App\Models\Kegiatan::where('subprogram_id', $this->subprograms->pluck('id'))->with('subkegiatans')->get();
        } else {
            $this->kegiatans = collect();
        }
    }
}
