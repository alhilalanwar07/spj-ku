<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Kalender as Kalenderkerja;

class Kalender extends Component
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

    public function mount()
    {
        Carbon::setLocale('id');

        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->loadHolidays();
        $this->generateCalendar();
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
        $hariLibur = Kalenderkerja::whereYear('tanggal_libur', $this->year)->whereMonth('tanggal_libur', $this->month)->whereDay('tanggal_libur',$day)->first();
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
                    'isHoliday' =>$this->isHoliday($currentDay->format('Y-m-d')),
                    'nextMonth' => $currentDay->month != $firstDayOfMonth->month,
                    'isPrevMonth' => $currentDay->month != $firstDayOfMonth->month,
                    'isNextMonth' => $currentDay->month != $firstDayOfMonth->month,
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

        // $this->generateCalendar();
        $this->loadHolidays();
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
    }
    public function render()
    {
        Carbon::setLocale('id');

        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->isoFormat('MMMM');
        $this->listHariLibur = Kalenderkerja::whereYear('tanggal_libur', $this->year)
            ->whereMonth('tanggal_libur', $this->month)
            ->orderBy('tanggal_libur','asc')
            ->get();

        return view('livewire.admin.kalender',[
            'monthName' => $monthName,
            'listHariLibur' => $this->listHariLibur
        ])->layout('components.layouts.app', ['title' => 'Kalender']);
    }

    public function simpanHariLibur()
    {
        Carbon::setLocale('id');

        $this->validate([
            'selectedDate' => 'required',
            'keterangan' => 'required',
        ]);

        $tanggal = Carbon::createFromDate($this->year, $this->month, $this->selectedDate)->format('Y-m-d');
        $libur = new Kalenderkerja;
        $libur->tanggal_libur       = $tanggal;
        $libur->tahun               = $this->year;
        $libur->bulan               = $this->month;
        $libur->keterangan_libur    = $this->keterangan;
        $libur->save();

        $this->loadHolidays();

        $this->dispatch('tambahAlert', [
            'type' => 'success',
            'title' => 'Simpan data berhasil',
            'text' => 'Data Hari Libur Berhasil Ditambahkan',
            'timeout' => 1000
        ]);

        $this->dispatch('closeModal');

        $this->keterangan = ''; // Reset inputan
    }
}
