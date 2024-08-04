<div>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/mycalendar.css') }}">
    <style>
        .marquee-container {
            background-color: #D1FFD1;
            /* Hijau muda hampir putih */
            color: red;
            /* Teks merah */
            padding: 10px;
            border-radius: 5px;
            white-space: nowrap;
            overflow: hidden;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 25s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .holiday div[title] {
            position: relative;
            cursor: pointer;
        }

        .holiday div[title]::after {
            content: attr(title);
            position: absolute;
            white-space: nowrap;
            background: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .holiday div[title]:hover::after {
            opacity: 1;
        }

        .accordion-header .accordion-button {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center;
            width: 100%;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            color: #495057;
            line-height: 1.5;
        }

        .select2-container--bootstrap-5 .select2-selection__arrow {
            height: 100%;
            right: 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap-5 .select2-results__options {
            max-height: 200px;
            /* Set the maximum height */
            overflow-y: auto;
            /* Enable vertical scrollbar */
        }

    </style>
    @endsection
    <div class="card card-body border-0 shadow table-wrapper table-responsive mt-4">
        <table class="table">
            <div class="d-flex justify-content-between align-items-center border-bottom">
                <button class="btn btn-primary btn-sm mb-2" wire:click="previousMonth">&lt;</button>
                <span class="h5 badge bg-primary">{{ $monthName }} {{ $year }}</span>
                <button class="btn btn-primary btn-sm mb-2" wire:click="nextMonth">&gt;</button>
            </div>
            <thead>
                <tr>
                    @foreach($daysOfWeek as $day)
                    <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($calendar as $week)
                <tr>
                    @foreach($week as $day)
                    @php
                    $isHoliday = $day['isHoliday'];
                    $isNextMonth = $day['isNextMonth'];
                    $isPrevMonth = $day['isPrevMonth'];
                    $isCurrentMonth = !$isNextMonth && !$isPrevMonth;
                    $holidayDescription = $isHoliday ? $day['holidayDescription'] : '';
                    @endphp
                    <td class="{{ $isPrevMonth ? 'prev-month' : '' }}  {{ isset($day['today']) ? 'today' : '' }} {{ $isHoliday ? 'holiday' : '' }} {{ $isNextMonth ? 'next-month' : '' }}">
                        <div title="{{ $holidayDescription }}">
                            <span>{{ $day['day'] }}</span>
                            @foreach($day['events'] as $event)
                            <div class="event">{{ $event }}</div>
                            @endforeach
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach

            </tbody>
        </table>

        <div class="my-3">

            @php
            $allHolidays = '';
            foreach($listHariLibur as $holiday) {
            $allHolidays .= '<span class="text-danger">' . date('d-m-Y', strtotime($holiday->tanggal_libur)) . '</span> : ' . $holiday->keterangan_libur . ' | &nbsp;';
            }
            @endphp

            <div class="my-2 marquee-container">
                <div class="marquee-text text-uppercase">
                    {!! $allHolidays !!}
                </div>
            </div>
        </div>
        {{-- aktivitas bulanan --}}
        <div x-data="{ viewState: @entangle('viewState') }">

            <div class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="h3 badge bg-primary">Aktivitas Bulanan</span>
                    <button class="btn btn-primary btn-sm mb-2" @click="viewState = 'view1'" x-show="viewState !== 'view1'">
                        Tambah
                    </button>
                    <button class="btn btn-danger btn-sm mb-2" @click="viewState = null" x-show="viewState === 'view1'">
                        Close
                    </button>
                </div>

                <div x-show="viewState === 'view1'" style="display: none;">
                    <form>
                        @csrf
                        <div class="my-2 mb-4">
                            <div class="row mb-3">
                                <div wire:ignore x-data x-init="function() {
                                    $nextTick(() => {
                                        $('#subkegiatan').select2({
                                            placeholder: 'Pilih Subkegiatan',
                                            width: '100%',
                                            theme: 'bootstrap-5'
                                        });

                                        Livewire.hook('message.processed', (message, component) => {
                                            $('#subkegiatan').select2({
                                                placeholder: 'Pilih Subkegiatan',
                                                width: '100%',
                                                theme: 'bootstrap-5'
                                            });
                                        });
                                    });
                                }">
                                    <label class="form-label">Sub Kegiatan</label>
                                    <select id="subkegiatan" wire:model="selectedSubkegiatan" class=" form-select  select2">
                                        <option value="" disabled selected>Pilih Subkegiatan</option>
                                        @foreach($subkegiatans as $sub)
                                        <option value="{{ $sub['id'] }}">[{{ $sub['kode_rekening_subkegiatan'] }}] &nbsp;{{ $sub['nama_subkegiatan'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedSubkegiatan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div x-data="{ subViewState: @entangle('SubViewState') }">
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="form-check">
                                                <input wire:model.live="isPerjalananDinas" class="form-check-input" type="checkbox" @click="subViewState = subViewState === 'view1' ? 'view2' : 'view1'">
                                                <label class="form-check-label" for="isPerjalananDinas">
                                                    Perjalanan Dinas
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="tempat" class="form-label">Tempat</label>
                                                    <input type="text" class="form-control" id="tempat" wire:model.defer="tempat">
                                                    @error('tempat') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                                    <input type="text" class="form-control" id="penyelenggara" wire:model.defer="penyelenggara">
                                                    @error('penyelenggara') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="keterangan" class="form-label">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan" wire:model.defer="keterangan"></textarea>
                                                    @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="nominal" class="form-label">Nominal</label>
                                                    <input type="number" class="form-control" id="nominal" wire:model.defer="nominal">
                                                    @error('nominal') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-6 order-md-last mt-3">
                                            <div x-show="subViewState === 'view1'" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                                        <input type="date" class="form-control" id="tanggal_mulai" wire:model.live="tanggal_mulai">
                                                        @error('tanggal_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- @if($isPerjalananDinas) --}}
                                                    <div class="col-md-6">
                                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                                        <input type="date" class="form-control" id="tanggal_selesai" wire:model.live="tanggal_selesai">
                                                        @error('tanggal_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-12 mt-3">
                                                        <label for="pegawai" class="form-label">Pegawai</label>
                                                        <div class="row">
                                                            @foreach($pegawais as $pegawai)
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="{{ $pegawai->id }}" id="pegawai-{{ $pegawai->id }}" wire:model="selectedPegawai">
                                                                    <label class="form-check-label" for="pegawai-{{ $pegawai->id }}">
                                                                        {{ $pegawai->nama }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @error('selectedPegawai') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    {{-- @endif --}}
                                                </div>
                                            </div>
                                            <div x-show="subViewState !== 'view1'" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="tanggal_mulai" class="form-label">Tanggal</label>
                                                        <input type="date" class="form-control" id="tanggal_mulai" wire:model.live="tanggal_mulai">
                                                        @error('tanggal_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <input type="text" class="form-control" name="activeView" wire:model.live="activeView" value="{{ $subViewState === 'view1' ? 'view1' : 'view2' }}"> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" wire:click.prevent="simpanAktivitas">Simpan</button>
                        </div>
                    </form>
                    <hr>
                    <br>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Program/Kegiatan</th>
                                <th>Tempat / Tanggal</th>
                                <th>Penyelenggara / Keterangan</th>
                                <th>Anggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @forelse ($listAktivitasBulanan as $aktivitas)
                            <tr class="text-left" style="align: left !important;">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-left" style="text-align: left !important">
                                    [{{ $aktivitas->subkegiatan->kegiatan->subprogram->program->kode_rekening_program }}] {{ $aktivitas->subkegiatan->kegiatan->subprogram->program->nama_program }}<br>
                                    &nbsp;&nbsp;[{{ $aktivitas->subkegiatan->kegiatan->subprogram->kode_rekening_subprogram }}] {{ $aktivitas->subkegiatan->kegiatan->subprogram->nama_subprogram }}<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;[{{ $aktivitas->subkegiatan->kegiatan->kode_rekening_kegiatan }}] {{ $aktivitas->subkegiatan->kegiatan->nama_kegiatan }}<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[{{ $aktivitas->subkegiatan->kode_rekening_subkegiatan }}] {{ $aktivitas->subkegiatan->nama_subkegiatan }}
                                </td>
                                <td>
                                <span class="badge bg-info mb-1 text-uppercase">{{ $aktivitas->tempat }}</span>
                                     <br>
                                    {{ date('d-m-Y', strtotime($aktivitas->tanggal_mulai)) }} <br> s/d <br>
                                    {{ date('d-m-Y', strtotime($aktivitas->tanggal_selesai)) }}
                                </td>
                                <td style="text-align: left !important" class="text-wrap">
                                    <span class="text-capitalize">{{ $aktivitas->penyelenggara }}</span> <br>
                                    Ket.: {{ $aktivitas->keterangan }}
                                </td>
                                <td>Rp. {{ number_format($aktivitas->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada program/kegiatan bulan {{ $monthName }} {{ $year }}.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div wire:ignore class="my-3">
            <span class="h3 badge bg-primary">Aktivitas Tahunan</span>
            <div class="accordion mb-2" id="accordionExample">
                @foreach($listProgram as $indexProgram => $program)
                <div class="accordion-item">
                    <h2 class="accordion-header " id="headingProgram{{ $program->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProgram{{ $program->id }}" aria-expanded="false" aria-controls="collapseProgram{{ $program->id }}">
                            {{ '[ ' . $program->kode_rekening_program . ' ] '  . $program->nama_program . '' }}
                        </button>
                    </h2>
                    <div id="collapseProgram{{ $program->id }}" class="accordion-collapse collapse" aria-labelledby="headingProgram{{ $program->id }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            @foreach($program->subPrograms as $indexSubProgram => $subprogram)
                            <div class="accordion mb-2" id="accordionSubProgram{{ $subprogram->id }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSubProgram{{ $subprogram->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubProgram{{ $subprogram->id }}" aria-expanded="false" aria-controls="collapseSubProgram{{ $subprogram->id }}">
                                            {{ '[ ' . $subprogram->kode_rekening_subprogram . ' ] ' . '' . $subprogram->nama_subprogram . ' ' }}
                                        </button>
                                    </h2>
                                    <div id="collapseSubProgram{{ $subprogram->id }}" class="accordion-collapse collapse" aria-labelledby="headingSubProgram{{ $subprogram->id }}" data-bs-parent="#accordionSubProgram{{ $subprogram->id }}">
                                        <div class="accordion-body">
                                            @foreach($subprogram->kegiatans as $indexKegiatan => $kegiatan)
                                            <div class="accordion mb-2" id="accordionKegiatan{{ $kegiatan->id }}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingKegiatan{{ $kegiatan->id }}">

                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKegiatan{{ $kegiatan->id }}" aria-expanded="false" aria-controls="collapseKegiatan{{ $kegiatan->id }}">
                                                            {{ '[ ' . $kegiatan->kode_rekening_kegiatan . ' ] ' . $kegiatan->nama_kegiatan . '' }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapseKegiatan{{ $kegiatan->id }}" class="accordion-collapse collapse" aria-labelledby="headingKegiatan{{ $kegiatan->id }}" data-bs-parent="#accordionKegiatan{{ $kegiatan->id }}">
                                                        <div class="accordion-body">
                                                            @foreach($kegiatan->subKegiatans as $indexSubKegiatan => $subkegiatan)
                                                            <div class="accordion mb-2" id="accordionSubKegiatan{{ $subkegiatan->id }}">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingSubKegiatan{{ $subkegiatan->id }}">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubKegiatan{{ $subkegiatan->id }}" aria-expanded="false" aria-controls="collapseSubKegiatan{{ $subkegiatan->id }}">
                                                                            <div class="d-flex justify-content-between w-100">
                                                                                <span class="d-flex align-items-start">
                                                                                    <span style="margin-right: 5px;">
                                                                                        [{{ $subkegiatan->kode_rekening_subkegiatan }}]
                                                                                    </span>
                                                                                    <span title="{{ $subkegiatan->nama_subkegiatan }}">{{ Str::limit($subkegiatan->nama_subkegiatan, 60) }}</span>
                                                                                </span>
                                                                                <span class="d-flex align-items-center">
                                                                                    <span class="badge bg-info">
                                                                                        &nbsp;Anggaran: Rp. {{ number_format($subkegiatan->anggaran, 0, ',', '.') }}
                                                                                    </span>
                                                                                    &nbsp;
                                                                                    <span class="badge bg-success">
                                                                                        &nbsp;Realisasi: Rp. {{ number_format($subkegiatan->aktivitas->sum('nominal'), 0, ',', '.') }}
                                                                                    </span>
                                                                                    &nbsp;
                                                                                    <span class="badge bg-danger">
                                                                                        &nbsp;Sisa: Rp. {{ number_format($subkegiatan->anggaran - $subkegiatan->aktivitas->sum('nominal'), 0, ',', '.') }}
                                                                                    </span>
                                                                                </span>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseSubKegiatan{{ $subkegiatan->id }}" class="accordion-collapse collapse" aria-labelledby="headingSubKegiatan{{ $subkegiatan->id }}" data-bs-parent="#accordionSubKegiatan{{ $subkegiatan->id }}">
                                                                        <div class="accordion-body">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#subkegiatan').select2();
            $('#subkegiatan').on('change', function(e) {
                var data = $('#subkegiatan').select2("val");
                @this.set('selectedSubkegiatan', data);
            });
        });
    </script>

    @endpush

    @livewire('alert')
</div>
