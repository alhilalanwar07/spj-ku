<?php

namespace App\Livewire\User;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kalender as Kalenderkerja;

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
    public $isPerjalananDinas = 0;

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

    public $viewStateAktif;

    public $dinasLuar;

    protected function rules() {
        $rules = [
            'selectedSubkegiatan' => 'required',
            'tempat' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
        ];

        if ($this->subViewState === 'view1') {
            $rules['tanggal_selesai'] = 'required|date|after_or_equal:tanggal_mulai';
            $rules['selectedPegawai.*'] = 'exists:pegawai,id';
        }

        return $rules;
    }

    // public $newActivity;
    public $activities = [];

    public $viewState = '';

    public $subViewState = '';

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


    public function changeView($view)
    {
        $this->viewState = $view;
    }

    public function changeSubView($view)
    {
        $this->subViewState = $view;

        // kosongkan tanggal mulai dan selesai
        $this->tanggal_mulai = null;
        $this->tanggal_selesai = null;
    }

    public function simpanAktivitas()
    {
        // dd($this->subViewState);
        // dd($this->selectedPegawai);
        $this->validate();

        // cek anggaran sebelum menyimpan aktivitas
        $subkegiatan = \App\Models\Subkegiatan::findOrFail($this->selectedSubkegiatan);
        $anggaran = $subkegiatan->anggaran;
        $anggaran = $anggaran - $this->nominal;
        if ($anggaran < 0) {
            $this->dispatch('updateAlertToast', [
                'title' => 'Gagal menyimpan aktivitas',
                'text' => 'Anggaran tidak mencukupi',
                'type' => 'error',
                'timeout' => 2000,
            ]);

            return;
        }

        DB::transaction(function () {
            $aktivitas = \App\Models\Aktivitas::create([
                'subkegiatan_id' => $this->selectedSubkegiatan,
                'tempat' => $this->tempat,
                'penyelenggara' => $this->penyelenggara,
                'keterangan' => $this->keterangan,
                'nominal' => $this->nominal,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->isPerjalananDinas ? $this->tanggal_selesai : null
            ]);

            // dapatkan semua tanggal

            if ($this->isPerjalananDinas == 1) {
                foreach ($this->selectedPegawai as $pegawaiId) {
                    \App\Models\AktivitasPegawai::create([
                        'aktivitas_id' => $aktivitas->id,
                        'pegawai_id' => $pegawaiId,
                    ]);

                    // simpan ke tabel dinasluar

                    for($date = Carbon::parse($this->tanggal_mulai); $date->lte(Carbon::parse($this->tanggal_selesai)); $date->addDay()) {
                        \App\Models\Dinasluar::create([
                            'aktivitas_id' => $aktivitas->id,
                            'pegawai_id' => $pegawaiId,
                            'tanggal' => $date->format('Y-m-d'),
                            'bulan' => $date->format('m'),
                            'tahun' => $date->format('Y'),
                            'catatan' => 'DL',
                        ]);
                    }
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
        $this->resetInputAktivitas();
    }


    public function resetInputAktivitas()
    {
        $this->selectedSubkegiatan = '';
        // $this->isPerjalananDinas = '';
        $this->tempat = '';
        $this->penyelenggara = '';
        $this->keterangan = '';
        $this->nominal = '';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->selectedPegawai = [];
    }

    public function updatedIsPerjalananDinas($value)
    {
        if (!$value) {
            $this->tanggal_mulai = null;
            $this->tanggal_selesai = null;
            $this->selectedPegawai = [];
        }

        if($value) {
            $this->isPerjalananDinas = 1;
            $this->viewStateAktif = 'view1';
        } else {
            $this->isPerjalananDinas = 0;
            $this->viewStateAktif = 'view2';
        }
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
                    'dinasLuar' => \App\Models\Dinasluar::where('tanggal', $currentDay->format('Y-m-d'))->get(),
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
        return \App\Models\Aktivitas::whereMonth('tanggal_mulai', $month)
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

    public function konfirmasi($id)
    {
        $this->selectedAktivitasId = $id;

        // if pptk, update acc_pptk
        if(Auth::user()->role == 'pptk') {
            $aktivitas = \App\Models\Aktivitas::find($id);
            $aktivitas->acc_pptk = 'Dikonfirmasi';
            $aktivitas->save();
        }
        if(Auth::user()->role == 'kabag') {
            $aktivitas = \App\Models\Aktivitas::find($id);
            $aktivitas->acc_kabag = 'Dikonfirmasi';
            $aktivitas->save();
        }

        $this->dispatch('updateAlert', [
            'title' => 'Berhasil',
            'text' => 'Aktivitas konfirmasi.',
            'type' => 'success',
            'timeout' => 1500,
        ]);


    }
}
