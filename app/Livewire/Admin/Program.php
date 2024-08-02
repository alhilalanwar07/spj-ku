<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
// use App\Models\Program as ProgramModel;
use App\Models\Subprogram as SubprogramModel;

class Program extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $kode_rekening_program, $nama_program, $istilah_program, $program_id;
    public $modal = true;

    public $subprograms;
    public $updatesubprogram = false;

    public $subprogram_id;
    public $kode_rekening_subprogram = [];
    public $nama_subprogram = [];
    public $isEditing = false;

    public $isTambahSub = false;
    public $kode_rekening_subprogram_baru;
    public $nama_subprogram_baru;

    public function editSub($id)
    {
        $this->subprogram_id = $id;
        $subprogram = SubprogramModel::findOrFail($id);
        $this->kode_rekening_subprogram[$id] = $subprogram->kode_rekening_subprogram;
        $this->nama_subprogram[$id] = $subprogram->nama_subprogram;
        $this->isEditing = true;
    }

    // Metode untuk menyimpan perubahan
    public function updateSub($id)
    {
        $subprogram = SubprogramModel::findOrFail($id);
        $subprogram->kode_rekening_subprogram = $this->kode_rekening_subprogram[$id];
        $subprogram->nama_subprogram = $this->nama_subprogram[$id];
        $subprogram->save();

        $this->dispatch('updateAlertToast', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Sub Program Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        // reload data
        $this->subprograms = SubprogramModel::where('program_id', $this->program_id)->get();


        $this->isEditing = false;
    }

    public function hapusSub($id)
    {
        $subprogram = SubprogramModel::findOrFail($id);

        // jika ada kegiatan
        if(\App\Models\Kegiatan::where('subprogram_id', $id)->count() > 0){
            $this->dispatch('updateAlertToast', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Sub Program Tidak Bisa Dihapus Karena Memiliki Kegiatan',
                'type'      => 'error',
                'timeout'   => 1500
            ]);
            return;
        }

        $subprogram->delete();

        $this->dispatch('updateAlertToast', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Sub Program Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1500
        ]);

        // reload data
        $this->subprograms = SubprogramModel::where('program_id', $this->program_id)->get();

    }

    // Metode untuk membatalkan pengeditan
    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->subprograms = SubprogramModel::where('program_id', $this->program_id)->get();
    }

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
        return view('livewire.admin.program',[
            'programs' => \App\Models\Program::where('nama_program', 'like', '%'.$this->search.'%')->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Data Program']);
    }

    public function resetInput()
    {
        $this->kode_rekening_program = null;
        $this->nama_program = null;
        $this->istilah_program = null;
        $this->program_id = null;

        $this->modal = false;
    }

    public function simpan()
    {
        $this->validate([
            'kode_rekening_program' => 'required',
            'nama_program' => 'required',
        ]);

        // hapus spasi di kode rekening
        $this->kode_rekening_program = str_replace(' ', '', $this->kode_rekening_program);

        \App\Models\Program::create([
            'kode_rekening_program' => $this->kode_rekening_program,
            'nama_program' => $this->nama_program,
            'istilah_program' => $this->istilah_program ?? null
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Program Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $program = \App\Models\Program::find($id);
        $this->program_id = $id;
        $this->kode_rekening_program = $program->kode_rekening_program;
        $this->nama_program = $program->nama_program;
        $this->istilah_program = $program->istilah_program ?? null;

        // subprogram
        $this->subprograms = \App\Models\Subprogram::where('program_id', $id)->get();

        $this->updatesubprogram = true;

        $this->modal = true;
    }

    public function update()
    {
        $this->validate([
            'kode_rekening_program' => 'required',
            'nama_program' => 'required',
        ]);

        $this->kode_rekening_program = str_replace(' ', '', $this->kode_rekening_program);

        $program = \App\Models\Program::find($this->program_id);
        $program->update([
            'kode_rekening_program' => $this->kode_rekening_program,
            'nama_program' => $this->nama_program,
            'istilah_program' => $this->istilah_program ?? null
        ]);
        $this->dispatch('updateAlert', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Program Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1500
        ]);

        $this->resetInput();
    }

    public function hapus($id)
    {
        // jika ada subprogram
        if(\App\Models\SubProgram::where('program_id', $id)->count() > 0){
            $this->dispatch('hapusAlert', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Program Tidak Bisa Dihapus Karena Memiliki Sub Program',
                'type'      => 'error',
                'timeout'   => 1500
            ]);
            return;
        }

        \App\Models\Program::find($id)->delete();

        $this->dispatch('tambahAlert', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Program Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1500
        ]);
    }

    private function resetInputSubProgram()
    {
        $this->kode_rekening_subprogram_baru = '';
        $this->nama_subprogram_baru = '';
    }

    public function tambahSub()
    {
        $this->isTambahSub = true;
    }

    public function batalTambahSub()
    {
        $this->isTambahSub = false;
        $this->resetInputSubProgram();
    }

    public function simpanSub()
    {
        $this->validate([
            'kode_rekening_subprogram_baru' => 'required|string|max:255',
            'nama_subprogram_baru' => 'required|string|max:255',
        ]);

        SubprogramModel::create([
            'program_id' => $this->program_id,
            'kode_rekening_subprogram' => $this->kode_rekening_subprogram_baru,
            'nama_subprogram' => $this->nama_subprogram_baru,
        ]);

        $this->dispatch('updateAlertToast', [
            'title'     => 'Tambah data berhasil',
            'text'      => 'Data Sub Program Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        // Reload data
        $this->subprograms = SubprogramModel::where('program_id', $this->program_id)->get();

        // Reset input fields and state
        $this->resetInputSubProgram();
        $this->isTambahSub = false;
    }
}
