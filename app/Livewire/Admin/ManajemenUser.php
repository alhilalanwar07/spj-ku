<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use RealRashid\SweetAlert\Facades\Alert;

class ManajemenUser extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $name, $email, $password, $role;

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
        $users = User::where('name', 'like', '%'.$this->search.'%')->paginate($this->perpage);

        return view('livewire.admin.manajemen-user',[
            'users' => $users
        ])->layout('components.layouts.app', ['title' => 'Manajemen User']);
    }

    public function resetInput()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->role = null;
    }

    public function simpan()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required | unique:users',
            'password' => 'required',
            'role' => 'required',
        ],[
            'email.unique' => 'Email sudah terdaftar'
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role
        ]);

        // jika berhasil di tambah
        $this->dispatch('tambah', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data User Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->dispatch('closeModal');

        $this->resetInput();

    }

    public function hapus($id)
    {
        User::find($id)->delete();

        $this->dispatch('hapus', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data User Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }



}
