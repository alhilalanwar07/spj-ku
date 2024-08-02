<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Pegawai extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $nama, $nip, $jabatan, $golongan, $pegawai_id, $email, $password, $role, $user_id;

    public $modal = true;

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
        $pegawais = \App\Models\Pegawai::where('nama', 'like', '%'.$this->search.'%')->paginate($this->perpage);
        return view('livewire.admin.pegawai',[
            'pegawais' => $pegawais
        ])->layout('components.layouts.app', ['title' => 'Data Pegawai']);
    }

    public function resetInput()
    {
        $this->nama = null;
        $this->nip = null;
        $this->jabatan = null;
        $this->golongan = null;
        $this->email = null;
        $this->password = null;
        $this->role = null;
        $this->user_id = null;

        $this->modal = false;
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'golongan' => 'required',
            'email' => 'required | unique:users',
            'password' => 'required',
            'role' => 'required'
        ], [
            'email.unique' => 'Email sudah terdaftar'
        ]);

        $user = \App\Models\User::create([
            'name' => $this->nama,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role
        ]);

        \App\Models\Pegawai::create([
            'nama' => $this->nama,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'golongan' => $this->golongan,
            'user_id' => $user->id
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Pegawai Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $pegawai = \App\Models\Pegawai::find($id);

        $this->pegawai_id = $id;
        $this->nama = $pegawai->nama;
        $this->nip = $pegawai->nip;
        $this->jabatan = $pegawai->jabatan;
        $this->golongan = $pegawai->golongan;

        $this->modal = true;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'golongan' => 'required',
        ]);

        // hapus spasi dari nip
        $this->nip = preg_replace('/\s+/', '', $this->nip);
        // jabatan huruf besar
        $this->golongan = strtoupper($this->golongan);

        \App\Models\Pegawai::find($this->pegawai_id)->update([
            'nama' => $this->nama,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'golongan' => $this->golongan
        ]);

        $this->dispatch('updateAlert', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Pegawai Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->modal = false;

        $this->resetInput();
    }

    public function hapus($id)
    {
        // hapus user dengan user_id terlebih daulu
        $userID = \App\Models\Pegawai::find($id)->user_id;
        \App\Models\User::find($userID)->delete();
        \App\Models\Pegawai::find($id)->delete();

        $this->dispatch('hapusAlert', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Pegawai Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }
}
