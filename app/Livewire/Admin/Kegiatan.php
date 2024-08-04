<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Subkegiatan as SubkegiatanModel;

class Kegiatan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $kode_rekening_program, $nama_program, $istilah_program, $program_id;
    public $kode_rekening_subprogram, $nama_subprogram, $istilah_subprogram, $subprogram_id;
    public $modal = true;

    // public $updatesubprogram = false;

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
    public $kode_rekening_subkegiatan = [];
    public $nama_subkegiatan = [];
    public $anggaran_subkegiatan = [];
    public $nama_kegiatan_edit, $kode_rekening_kegiatan_edit;

    public $program, $subprogram, $kegiatan_id;

    public $kode_rekening_subkegiatan_baru;
    public $nama_subkegiatan_baru;
    public $anggaran_subkegiatan_baru;
    public $subkegiatan_id;

    public $totalAnggaran = 0, $realisasiAnggaran = 0, $sisaAnggaran = 0;


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

        // update total anggaran, realisasi anggaran, sisa anggaran
        $this->totalAnggaran = number_format($this->kegiatans->sum(function ($kegiatan) {
            return $kegiatan->subkegiatans->sum('anggaran');
        }), 0, ',', '.');
    }

    public function updatedSubProgramPilih($value)
    {
        if ($value === null) {
            $this->updatedProgramPilih($this->programPilih);
        } else {
            $this->kegiatans = \App\Models\Kegiatan::where('subprogram_id', $value)->with('subkegiatans')->get();
        }

        // update total anggaran, realisasi anggaran, sisa anggaran
        $this->totalAnggaran = number_format($this->kegiatans->sum(function ($kegiatan) {
            return $kegiatan->subkegiatans->sum('anggaran');
        }), 0, ',', '.');
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
            $this->dispatch('updateAlertToast', [
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

    public function edit($id)
    {
        $kegiatan = \App\Models\Kegiatan::find($id);

        $this->kegiatan_id = $id;
        $subprogram_id = $kegiatan->subprogram_id;
        $this->kode_rekening_kegiatan_edit = $kegiatan->kode_rekening_kegiatan;
        $this->nama_kegiatan_edit = $kegiatan->nama_kegiatan;

        // program dan subprogram
        $this->subprogram = \App\Models\Subprogram::where('id', $subprogram_id)->first();
        $this->program = \App\Models\Program::where('id', $this->subprogram->program_id)->first();

        // kode_rekening_program, nama_program, istilah_program
        $this->kode_rekening_program = $this->program->kode_rekening_program;
        $this->nama_program = $this->program->nama_program;
        $this->istilah_program = $this->program->istilah_program;

        // kode_rekening_subprogram, nama_subprogram, istilah_subprogram
        $this->kode_rekening_subprogram = $this->subprogram->kode_rekening_subprogram;
        $this->nama_subprogram = $this->subprogram->nama_subprogram;
        $this->istilah_subprogram = $this->subprogram->istilah_subprogram;

        $this->isEditing = true;

        $this->modal = true;

        $this->subkegiatans = \App\Models\Subkegiatan::where('kegiatan_id', $id)->get();

        $this->updatesubkegiatan = true;

    }

    private function resetInputSubKegiatan()
    {
        $this->kode_rekening_subkegiatan_baru = '';
        $this->nama_subkegiatan_baru = '';
        $this->anggaran_subkegiatan_baru = '';
    }

    public function editSub($id)
    {
        $this->subkegiatan_id = $id;
        $subkegiatan = SubkegiatanModel::findOrFail($id);
        $this->kode_rekening_subkegiatan[$id] = $subkegiatan->kode_rekening_subkegiatan;
        $this->nama_subkegiatan[$id] = $subkegiatan->nama_subkegiatan;
        $this->anggaran_subkegiatan[$id] = $subkegiatan->anggaran;
        $this->isEditing = true;
    }

    public function tambahSub()
    {
        $this->isTambahSub = true;
    }

    public function batalTambahSub()
    {
        $this->isTambahSub = false;
        $this->resetInputSubKegiatan();
    }

    public function simpanSub()
    {
        $this->validate([
            'kode_rekening_subkegiatan_baru' => 'required|string|max:255',
            'nama_subkegiatan_baru' => 'required|string|max:255',
            'anggaran_subkegiatan_baru' => 'required|numeric',
        ]);

        SubkegiatanModel::create([
            'kegiatan_id' => $this->kegiatan_id,
            'kode_rekening_subkegiatan' => $this->kode_rekening_subkegiatan_baru,
            'nama_subkegiatan' => $this->nama_subkegiatan_baru,
            'anggaran' => $this->anggaran_subkegiatan_baru,
        ]);

        $this->dispatch('updateAlertToast', [
            'title'     => 'Tambah data berhasil',
            'text'      => 'Data Sub Kegiatan Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 2000
        ]);

        // Reload data
        $this->subkegiatans = SubkegiatanModel::where('kegiatan_id', $this->kegiatan_id)->get();

        // Reset input fields and state
        $this->resetInputSubKegiatan();
        $this->isTambahSub = false;
    }

    public function hapusSub($id)
    {
        $subkegiatan = SubkegiatanModel::findOrFail($id);

        // jika ada kegiatan
        if(\App\Models\Aktivitas::where('subkegiatan_id', $id)->count() > 0){
            $this->dispatch('updateAlertToast', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Sub kegiatan Tidak Bisa Dihapus Karena Memiliki Aktivitas',
                'type'      => 'error',
                'timeout'   => 1500
            ]);
            return;
        }

        $subkegiatan->delete();

        $this->dispatch('updateAlertToast', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Sub kegiatan Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1500
        ]);

        // reload data
        $this->subkegiatans = SubkegiatanModel::where('kegiatan_id', $this->kegiatan_id)->get();

    }

    public function updateSub($id)
    {
        $subkegiatan = SubkegiatanModel::findOrFail($id);
        $subkegiatan->kode_rekening_subkegiatan = $this->kode_rekening_subkegiatan[$id];
        $subkegiatan->nama_subkegiatan = $this->nama_subkegiatan[$id];
        $subkegiatan->anggaran = $this->anggaran_subkegiatan[$id];
        $subkegiatan->save();

        $this->dispatch('updateAlertToast', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Sub kegiatan Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        // reload data
        $this->subkegiatans = SubkegiatanModel::where('kegiatan_id', $this->kegiatan_id)->get();

        $this->isEditing = false;
    }

}
