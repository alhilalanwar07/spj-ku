<div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kegiatan</li>
                </ol>
            </nav>
            <h2 class="h4">Kegiatan</h2>
            <p class="mb-0"></p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modaTambah" wire:click="resetInput()">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Data
            </button>
        </div>
    </div>

    <div class="table-settings mb-2">
        <div class="row align-items-center">
            <div class="col col-md-6 col-lg-6 col-xl-6">
                {{-- select program --}}
                <div class="form-group mb-3">
                    <label class="form-label">Program</label>
                    <select class="form-select" wire:model.live="programPilih">
                        <option value="">-- Pilih Program --</option>
                        @foreach ($programs as $program)
                        <option value="{{ $program->id }}" title="[{{ $program->kode_rekening_program }}] {{ $program->nama_program }}">[{{ $program->kode_rekening_program }}] {{ $program->nama_program }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col col-md-6 col-lg-6 col-xl-6">
                {{-- select program --}}
                <div class="form-group mb-3">
                    <label class="form-label">Sub Program</label>
                    <select class="form-select" wire:model.live="subProgramPilih">
                        <option value="" disabled>-- Pilih Sub Program --</option>
                        @foreach ($subprograms as $subprogram)
                        <option value="{{ $subprogram->id }}" title="[{{ $subprogram->kode_rekening_subprogram }}] {{ $subprogram->nama_subprogram }}">[{{ $subprogram->kode_rekening_subprogram }}] {{ $subprogram->nama_subprogram }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-body py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent border-bottom py-3 px-0">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <span class="fs-6 fw-bolder text-dark">Total Anggaran</span>
                                </div>
                                <div class="col text-center">
                                    <input wire:model="totalAnggaran"  type="text" class="form-control form-control-sm fs-6 fw-bolder text-dark" disabled>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-body py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent border-bottom py-3 px-0">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <span class="fs-6 fw-bolder text-dark">Realisasi Anggaran</span>
                                </div>
                                <div class="col text-end">
                                    <input wire:model.live="realisasiAnggaran" type="text" class="form-control form-control-sm fs-6 fw-bolder text-danger" disabled>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow ">
                <div class="card-body py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent border-bottom py-3 px-0">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <span class="fs-6 fw-bolder text-dark">Sisa Anggaran</span>
                                </div>
                                <div class="col text-end">
                                    <input wire:model.live="sisaAnggaran" type="text" class="form-control form-control-sm fs-6 fw-bolder text-success" disabled>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="2" class="border-gray-200" style="width: 5%">#</th>
                    <th rowspan="2" class="border-gray-200">Kegiatan</th>
                    <th colspan="2" class="border-gray-200">Sub Kegiatan</th>
                    <th rowspan="2" class="border-gray-200" style="width: 10%">Action</th>
                </tr>
                <tr>
                    <th class="border-gray-200">Detail</th>
                    <th class="border-gray-200">Anggaran</th>
                </tr>
            </thead>
            <tbody>
                {{-- @if($kegiatans) --}}
                @forelse ($kegiatans as $kegiatan)
                <tr>
                    <td>
                        <a href="#" class="fw-bold">
                            {{ ($loop->index) + 1 }}
                        </a>
                    </td>
                    <td width="25%">
                        <span class="fw-normal text-wrap fw-bold">
                            <span class="badge bg-primary">
                                {{ $kegiatan->kode_rekening_kegiatan }}
                            </span>
                            <br>{{ $kegiatan->nama_kegiatan }}
                            @if($kegiatan->istilah_kegiatan != null)
                            ({{ $kegiatan->istilah_kegiatan }})
                            @endif
                        </span>
                    </td>
                    <td >
                        @forelse($kegiatan->subkegiatans as $subkegiatan)
                        <div class="sub-kegiatan-item ">
                            <span class="kode-rekening">[{{ $subkegiatan->kode_rekening_subkegiatan }}]</span>
                            <span class="nama">{{ $subkegiatan->nama_subkegiatan }}</span>
                        </div>
                        @empty
                        <span class="text-muted">Belum ada sub kegiatan.</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($kegiatan->subkegiatans as $subkegiatan)
                        <div class="sub-kegiatan-item">
                            <span class="anggaran">Rp. {{ number_format($subkegiatan->anggaran, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <span class="text-muted">Belum ada sub kegiatan.</span>
                        @endforelse
                    </td>
                    <td class="text-end">
                        <a href="# " class="btn btn-info btn-sm btn-rounded" wire:click.prevent="edit({{ $kegiatan->id }}) " data-bs-toggle="modal" data-bs-target="#modaEdit">Lihat</a>
                        <a href="#" wire:click.prevent="hapus({{ $kegiatan->id }})" class="btn btn-danger btn-sm btn-rounded">Delete</a>
                    </td>
                </tr>
                <tr class="text-primary fw-bold" style="background-color: #D0FFD3">
                    <td class="text-start"></td>
                    <td colspan="2" class="text-start">Total Anggaran</td>
                    <td class="text-start">Rp.
                        {{ number_format($kegiatan->subkegiatans->sum('anggaran'), 0, ',', '.') }}
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="4">
                        <div class="text-center">
                            Belum ada data.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>


    </div>
    <!-- Modal Content -->
    <div wire:ignore.self class="modal fade" id="modaTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Tambah Kegiatan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Pilih Program</label>
                            <select class="form-select" wire:model.live="program_id">
                                <option value="">Pilih Program</option>
                                @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->kode_rekening_program . ' - ' . $program->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Pilih Sub Program</label>
                            <select class="form-select" wire:model.live="subprogram_id">
                                <option value="">Pilih Sub Program</option>
                                @php
                                $subprograms = App\Models\Subprogram::where('program_id', $program_id)->get();
                                @endphp
                                @if (!is_null($program_id))
                                @foreach ($subprograms as $subprogram)
                                <option value="{{ $subprogram->id }}">{{ $subprogram->kode_rekening_subprogram . ' - ' . $subprogram->nama_subprogram }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <hr>
                        @if ($subprogram_id)
                        <table class="table mt-2 table-hover">
                            <thead>
                                <tr>
                                    <th width="35%">Kode Rekening Kegiatan</th>
                                    <th width="55%">Nama Kegiatan</th>
                                    <th class="text-end" width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kode_rekening_kegiatan as $index => $kode)
                                <tr>
                                    <td><input type="text" class="form-control" wire:model="kode_rekening_kegiatan.{{ $index }}" /></td>
                                    <td><textarea class="form-control" wire:model="nama_kegiatan.{{ $index }}" rows="3"></textarea></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger" wire:click.prevent="removeKegiatanInput({{ $index }})">Hapus</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-success btn-sm" wire:click.prevent="addKegiatanInput()">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Kegiatan</button>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" wire:click="simpan()" @if($modal) data-bs-dismiss="modal" @endif>Simpan</button>
                        <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modaEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    @if (!is_null($kegiatan_id))
                    <div class="d-flex justify-content-between text-middle fw-bold mb-0 modal-title">
                        <table class="table table-borderless ">
                            <tr class="gap-2">
                                <td class="p-0">
                                    <span class="badge bg-primary"> {{ $this->kode_rekening_program }} </span>
                                <td class="p-0"> </td>
                                </td>
                                <td class="p-0 ml-1">
                                    {{ $this->nama_program }}
                                </td>
                            </tr>
                            <tr class="gap-2">
                                <td class="p-0"><span class="badge bg-primary"> {{ $this->kode_rekening_subprogram }} </span>
                                </td>
                                <td class="p-0"> </td>
                                <td class="p-0 ml-1"> {{ $this->nama_subprogram  }} </td>
                            </tr>
                        </table>
                    </div>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @csrf

                    <div class="modal-body border-bottom">
                        <div class="table-responsive">
                            <table class="table table-lg table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Rek. Sub Kegiatan</th>
                                        <th>Nama Sub Kegiatan</th>
                                        <th>Anggaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $subkegiatans = $subkegiatans ?? \App\Models\Subkegiatan::where('kegiatan_id', $kegiatan_id)->get();
                                    @endphp
                                    @if(!$isTambahSub)
                                    <tr>
                                        <td colspan="4">
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click.prevent="tambahSub"><i class="fa fa-plus"></i> Tambah Sub Kegiatan</button>
                                        </td>
                                    </tr>

                                    @else
                                    <tr>
                                        <td width="25%">
                                            <input type="text" class="form-control" wire:model="kode_rekening_subkegiatan_baru">
                                        </td>
                                        <td width="50%">
                                            <textarea class="form-control" rows="3" wire:model="nama_subkegiatan_baru"></textarea>
                                        </td>
                                        <td width="15%">
                                            <input type="number" class="form-control" wire:model="anggaran_subkegiatan_baru">
                                        </td>
                                        <td width="10%">
                                            <button type="button" class="btn btn-sm btn-outline-success" wire:click.prevent="simpanSub"><i class="fa fa-check"></i> Simpan</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click.prevent="batalTambahSub"><i class="fa fa-times"></i> Batal</button>
                                        </td>
                                    </tr>
                                    @endif
                                    @forelse($subkegiatans as $subkegiatan)
                                    <tr>
                                        <td width="25%">
                                            @if($isEditing && $subkegiatan->id == $subkegiatan_id)
                                            <input type="text" class="form-control" wire:model="kode_rekening_subkegiatan.{{ $subkegiatan->id }}" value="{{ $subkegiatan->kode_rekening_subkegiatan }}" autofocus>
                                            @else
                                            {{ $subkegiatan->kode_rekening_subkegiatan }}
                                            @endif
                                        </td>
                                        <td width="40%" class="text-wrap">
                                            @if($isEditing && $subkegiatan->id == $subkegiatan_id)
                                            <textarea class="form-control" rows="3" wire:model="nama_subkegiatan.{{ $subkegiatan->id }}" autofocus>{{ $subkegiatan->nama_subkegiatan }}</textarea>
                                            @else
                                            {{ $subkegiatan->nama_subkegiatan }}
                                            @endif
                                        </td>
                                        <td width="18%">
                                            @if($isEditing && $subkegiatan->id == $subkegiatan_id)
                                            <input type="number" class="form-control" wire:model="anggaran_subkegiatan.{{ $subkegiatan->id }}" value="{{ $subkegiatan->anggaran }}" autofocus>
                                            @else
                                            {{ number_format($subkegiatan->anggaran, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td width="12%">
                                            @if($isEditing && $subkegiatan->id == $subkegiatan_id)
                                            <button type="button" class="btn btn-sm btn-outline-success" wire:click.prevent="updateSub({{ $subkegiatan->id }})"><i class="fa fa-check"></i> Update</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click.prevent="cancelEdit"><i class="fa fa-times"></i> Cancel</button>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-success" wire:click.prevent="editSub({{ $subkegiatan->id }})"><i class="fa fa-pencil"></i> Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click.prevent="hapusSub({{ $subkegiatan->id }})"><i class="fa fa-trash"></i> Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada sub kegiatan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Kode Rekening Kegiatan</label>
                            <input type="text" class="form-control @error('kode_rekening_kegiatan') is-invalid @enderror" wire:model="kode_rekening_kegiatan_edit">
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Nama Kegiatan</label>
                            <textarea class="form-control @error('nama_kegiatan') is-invalid @enderror" wire:model="nama_kegiatan_edit" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" wire:click="update()" @if($modal) data-bs-dismiss="modal" @endif>Update</button>
                        <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End of Modal Content -->
    @livewire('alert')
</div>
