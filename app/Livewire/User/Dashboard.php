<?php

namespace App\Livewire\User;

use App\Models\Kalender as Kalenderkerja;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $month;
    public $year;
    public $daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    public $calendar = [];
    public $selectedDate;
    public $keterangan;
    public $holidays = [];
    public $listHariLibur;
    public $isPrevMonth = false;
    public $isNextMonth = false;

    public $listProgram;
    public $newActivity;
    public $selectedAktivitasId;
    public $selectedPegawaiId;
    public $listAktivitasBulanan;

    public $subkegiatans;
    public $pegawais;
    public $isPerjalananDinas = false;

    public $selectedSubkegiatan;
    // public $isPerjalananDinas = false;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $tempat;
    public $penyelenggara;
    // public $keterangan;
    public $nominal;
    public $selectedPegawai = [];
    public $subkegiatan;
    public $pegawai;

    protected $rules = [
        'selectedSubkegiatan' => 'required',
        'tempat' => 'required|string|max:255',
        'penyelenggara' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'nominal' => 'required|numeric|min:0',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required_if:isPerjalananDinas,true|date|after_or_equal:tanggal_mulai',
        'selectedPegawai' => 'required_if:isPerjalananDinas,true|array|min:1',
    ];

    // public $newActivity;
    public $activities = [];


    public function mount()
    {
        Carbon::setLocale('id');

        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->loadHolidays();
        $this->generateCalendar();

        $this->listProgram = \App\Models\Program::all();
        $this->newActivity = '';
        $this->selectedAktivitasId = null;
        $this->selectedPegawaiId = null;
        $this->listAktivitasBulanan = $this->getAktivitasBulanan(Carbon::now()->month, Carbon::now()->year);

        $this->pegawais = \App\Models\Pegawai::orderBy('nama', 'asc')->get();
        $this->subkegiatans = \App\Models\Subkegiatan::orderBy('kode_rekening_subkegiatan', 'asc')->get();

    }

    public function simpanAktivitas()
    {
        $this->validate();

        DB::transaction(function () {
            $aktivitas = \App\Models\Aktivitas::create([
                'subkegiatan_id' => $this->selectedSubkegiatan,
                'tempat' => $this->tempat,
                'penyelenggara' => $this->penyelenggara,
                'keterangan' => $this->keterangan,
                'nominal' => $this->nominal,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->isPerjalananDinas ? $this->tanggal_selesai : null,
                'is_perjalanan_dinas' => $this->isPerjalananDinas,
            ]);

            if ($this->isPerjalananDinas) {
                foreach ($this->selectedPegawai as $pegawaiId) {
                    \App\Models\AktivitasPegawai::create([
                        'aktivitas_id' => $aktivitas->id,
                        'pegawai_id' => $pegawaiId,
                    ]);
                }
            }
        });

        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Pegawai Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1500
        ]);

        // Optionally, reset form fields
        $this->reset([
            'selectedSubkegiatan',
            'isPerjalananDinas',
            'tempat',
            'penyelenggara',
            'keterangan',
            'nominal',
            'tanggal_mulai',
            'tanggal_selesai',
            'selectedPegawai',
        ]);


    }

    public function updatedIsPerjalananDinas($value)
    {
        if (!$value) {
            $this->tanggal_mulai = null;
            $this->tanggal_selesai = null;
            $this->selectedPegawai = [];
        }

        $this->isPerjalananDinas = $value;
    }

    public function loadHolidays()
    {
        $this->holidays = Kalenderkerja::whereYear('tanggal_libur', $this->year)->whereMonth('tanggal_libur', $this->month)->pluck('tanggal_libur')->toArray();
        $this->generateCalendar();

    }

    // Metode untuk menangani klik tanggal
    public function selectDate($day)
    {
        $this->selectedDate = $day;
        $this->dispatch('openModal'); // Emit event untuk membuka modal
    }

    public function hapusHariLibur($day)
    {
        $hariLibur = Kalenderkerja::whereYear('tanggal_libur', $this->year)->whereMonth('tanggal_libur', $this->month)->whereDay('tanggal_libur', $day)->first();
        $hariLibur->delete();
        $this->loadHolidays();

    }

    public function generateCalendar()
    {
        // locales indonesia
        Carbon::setLocale('id');

        $firstDayOfMonth = Carbon::createFromDate($this->year, $this->month, 1);
        $lastDayOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        $startOfWeek = $firstDayOfMonth->dayOfWeek;

        $this->calendar = [];

        $currentDay = $firstDayOfMonth->copy()->subDays($startOfWeek);

        while ($currentDay->lte($lastDayOfMonth)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $day = [
                    'day' => $currentDay->day,
                    'events' => [], // Placeholder for events, you can replace this with your event data
                    'isHoliday' => $this->isHoliday($currentDay->format('Y-m-d')),
                    'nextMonth' => $currentDay->month != $firstDayOfMonth->month,
                    'isPrevMonth' => $currentDay->month != $firstDayOfMonth->month,
                    'isNextMonth' => $currentDay->month != $firstDayOfMonth->month,
                    'holidayDescription' => Kalenderkerja::where('tanggal_libur', $currentDay->format('Y-m-d'))->value('keterangan_libur'),
                ];

                //Tandai bulan sebelumnya
                // if ($currentDay->lt($firstDayOfMonth)) {
                //     $day['prevMonth'] = true;
                // }

                // Tandai tanggal hari ini
                if ($currentDay->isToday()) {
                    $day['today'] = true;
                }

                $week[] = $day;

                $currentDay->addDay();
            }

            $this->calendar[] = $week;
        }
    }

    public function getAktivitasBulanan($month, $year)
    {
        // Query to get monthly activities based on the selected month and year
        return \App\Models\Aktivitas::with('kegiatan.subprogram.program', 'kegiatan.subkegiatan')
            ->whereMonth('tanggal_mulai', $month)
            ->whereYear('tanggal_mulai', $year)
            ->get();
    }

    private function isHoliday($date)
    {
        return in_array($date, $this->holidays);
    }
    public function previousMonth()
    {
        $this->month--;
        if ($this->month < 1) {
            $this->month = 12;
            $this->year--;
        }

        // get this month's activities
        $this->listAktivitasBulanan = $this->getAktivitasBulanan($this->month, $this->year);

        // $this->generateCalendar();
        $this->loadHolidays();
        // $this->listAktivitasBulanan = $this->getAktivitasBulanan($
    }

    public function nextMonth()
    {
        $this->month++;
        if ($this->month > 12) {
            $this->month = 1;
            $this->year++;
        }

        // $this->generateCalendar();
        $this->loadHolidays();
        $this->listAktivitasBulanan = $this->getAktivitasBulanan($this->month, $this->year);
    }
    public function render()
    {
        Carbon::setLocale('id');

        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->isoFormat('MMMM');
        $this->listHariLibur = Kalenderkerja::whereYear('tanggal_libur', $this->year)
            ->whereMonth('tanggal_libur', $this->month)
            ->orderBy('tanggal_libur', 'asc')
            ->get();
        return view('livewire.user.dashboard', [
            'monthName' => $monthName,
            'listHariLibur' => $this->listHariLibur,
        ])->layout('components.layouts.template');
    }
}
