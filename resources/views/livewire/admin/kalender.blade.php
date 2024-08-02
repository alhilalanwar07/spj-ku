<div>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/mycalendar.css') }}">
    @endsection
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
                    <li class="breadcrumb-item active" aria-current="page">Kalender</li>
                </ol>
            </nav>
            <h2 class="h4">Kalender</h2>
            <p class="mb-0"></p>
        </div>
    </div>

    <div class="card card-body border-0 shadow table-wrapper table-responsive">

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
                    @endphp
                    <td class="{{ $isPrevMonth ? 'prev-month' : '' }}  {{ isset($day['today']) ? 'today' : '' }} {{ $isHoliday ? 'holiday' : '' }} {{ $isNextMonth ? 'next-month' : '' }}" @if ($isCurrentMonth && !$isHoliday) wire:click="selectDate('{{ $day['day'] }}')" @elseif ($isCurrentMonth && $isHoliday) wire:click="$dispatch('hapusLibur', {{ $day['day'] }})" @endif>
                        <div>
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
            <span>Keterangan :</span>
            @foreach($listHariLibur as $holiday)
            <div class="my-2">
                <span class="text-danger">{{ date('d-m-Y', strtotime($holiday->tanggal_libur))}}</span> : {{$holiday->keterangan_libur}}
            </div>
            @endforeach
        </div>
        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Masukkan Event Untuk Tanggal Ini</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-">
                            <label for="">Keterangan Hari Libur</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" wire:model="keterangan" placeholder="Masukkan Keterangan Hari Libur">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="simpanHariLibur">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="text/javascript">
        document.addEventListener('livewire:init', function() {
            Livewire.on('hapusLibur', id => {
                Swal.fire({
                    title: 'Rubah hari libur?'
                    , text: 'Hari Libur ini akan dibatalkan!'
                    , icon: "warning"
                    , showCancelButton: true
                    , confirmButtonColor: '#3085d6'
                    , cancelButtonColor: '#aaa'
                    , confirmButtonText: 'Ya'
                    , cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.value) {
                        @this.call('hapusHariLibur', id);
                        Swal.fire({
                            title: 'Hari libur dibatalkan!'
                            , icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Batal!'
                            , icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
    @endpush
    @livewire('alert')
</div>
