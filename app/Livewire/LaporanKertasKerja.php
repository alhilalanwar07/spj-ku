<?php

namespace App\Livewire;

use App\Exports\EvaluasiRencanaKerjaExport;
use App\Exports\LaporanKertasKerjaExport;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use App\Models\Program;
use App\Models\Subprogram;
use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use App\Models\Aktivitas;
use Maatwebsite\Excel\Excel;

class LaporanKertasKerja extends Component
{
    public $bulan_awal;
    public $bulan_akhir;
    public $tahun;
    public $dataLaporan = [];
    public $programs = [];

    public function mount()
    {
        $this->tahun = date('Y');
        $this->bulan_awal = 1;
        $this->bulan_akhir = date('n');
        $this->getDataLaporan();
    }

    public function updatedBulanAwal()
    {
        $this->getDataLaporan();
    }

    public function updatedBulanAkhir()
    {
        $this->getDataLaporan();
    }

    public function updatedTahun()
    {
        $this->getDataLaporan();
    }

    public function getDataLaporan()
    {
        $data = [];
        $no = 1;

        $programs = Program::with(['subprograms.kegiatans.subkegiatans.aktivitas' => function ($query) {
            $query->whereMonth('tanggal_mulai', '>=', $this->bulan_awal)
                ->whereMonth('tanggal_mulai', '<=', $this->bulan_akhir)
                ->whereYear('tanggal_mulai', $this->tahun);
        }])->get();

        foreach ($programs as $program) {
            $total_anggaran = 0;
            $total_kas = 0;
            $total_gaji = 0;
            $total_belanja = 0;
            $total_belum_spj = 0;

            foreach ($program->subprograms as $subprogram) {
                foreach ($subprogram->kegiatans as $kegiatan) {
                    foreach ($kegiatan->subkegiatans as $subkegiatan) {
                        $total_anggaran += $subkegiatan->anggaran;

                        // Hitung total kas (misalnya 80% dari anggaran)
                        $kas_per_bulan = $subkegiatan->anggaran / 12;
                        $total_kas += $kas_per_bulan * ($this->bulan_akhir - $this->bulan_awal + 1);

                        foreach ($subkegiatan->aktivitas as $aktivitas) {
                            if ($aktivitas->acc_pptk == 'Dikonfirmasi' && $aktivitas->acc_kabag == 'Dikonfirmasi') {
                                // Asumsikan 30% untuk gaji dan 70% untuk belanja
                                $total_gaji += $aktivitas->nominal * 0.3;
                                $total_belanja += $aktivitas->nominal * 0.7;
                            } else {
                                $total_belum_spj += $aktivitas->nominal;
                            }
                        }
                    }
                }
            }

            $jumlah_realisasi = $total_gaji + $total_belanja + $total_belum_spj;
            $sisa_kas = $total_kas - $jumlah_realisasi;
            $sisa_pagu = $total_anggaran - $jumlah_realisasi;

            $data[] = [
                'no' => $no++,
                'nama_program' => $program->nama_program,
                'apbd' => $total_anggaran,
                'kas' => $total_kas,
                'gaji' => $total_gaji,
                'belanja' => $total_belanja,
                'belum_spj' => $total_belum_spj,
                'jumlah_realisasi' => $jumlah_realisasi,
                'sisa_kas' => $sisa_kas,
                'sisa_pagu' => $sisa_pagu,
                'kegiatans' => isset($program->subprograms) ? $program->subprograms->flatMap(function ($subprogram) {
                    return $subprogram->kegiatans ?? [];
                })->values()->all() : [],
            ];
        }

        $this->dataLaporan = $data;
    }

    public function exportExcel()
    {
        $data = [];

        foreach ($this->programs as $program) {
            $subprograms = $program->subprograms ?? collect([]);
            $total_apbd = $subprograms->flatMap(fn($subprog) => $subprog->kegiatans ?? collect([]))
                ->flatMap(fn($keg) => $keg->subkegiatans ?? collect([]))
                ->sum('anggaran');
            $total_kas = $subprograms->flatMap(fn($subprog) => $subprog->kegiatans ?? collect([]))
                ->flatMap(fn($keg) => $keg->subkegiatans ?? collect([]))
                ->flatMap(fn($subkeg) => $subkeg->aktivitas ?? collect([]))
                ->sum('nominal');

            // Tambahkan data program
            $data[] = [
                $program->id,
                $program->nama_program,
                $total_apbd,
                $total_kas,
                '', // SPJ Gaji
                '', // SPJ Belanja luar gaji
                '', // Kegiatan belum SPJ
                $total_kas,
                $total_apbd - $total_kas,
                $total_apbd - $total_kas,
            ];

            // Tambahkan data subprogram
            foreach ($subprograms as $subprogram) {
                $kegiatans = $subprogram->kegiatans ?? collect([]);
                $subprogram_apbd = $kegiatans->flatMap(fn($keg) => $keg->subkegiatans ?? collect([]))->sum('anggaran');
                $subprogram_kas = $kegiatans->flatMap(fn($keg) => $keg->subkegiatans ?? collect([]))
                    ->flatMap(fn($subkeg) => $subkeg->aktivitas ?? collect([]))
                    ->sum('nominal');

                $data[] = [
                    '',
                    $subprogram->nama_subprogram,
                    $subprogram_apbd,
                    $subprogram_kas,
                    '',
                    '',
                    '',
                    $subprogram_kas,
                    $subprogram_apbd - $subprogram_kas,
                    $subprogram_apbd - $subprogram_kas,
                ];

                // Tambahkan data kegiatan
                foreach ($kegiatans as $kegiatan) {
                    $subkegiatans = $kegiatan->subkegiatans ?? collect([]);
                    $kegiatan_apbd = $subkegiatans->sum('anggaran');
                    $kegiatan_kas = $subkegiatans->flatMap(fn($subkeg) => $subkeg->aktivitas ?? collect([]))->sum('nominal');

                    $data[] = [
                        '',
                        $kegiatan->nama_kegiatan,
                        $kegiatan_apbd,
                        $kegiatan_kas,
                        '',
                        '',
                        '',
                        $kegiatan_kas,
                        $kegiatan_apbd - $kegiatan_kas,
                        $kegiatan_apbd - $kegiatan_kas,
                    ];

                    // Tambahkan data subkegiatan
                    foreach ($subkegiatans as $subkegiatan) {
                        $subkegiatan_kas = $subkegiatan->aktivitas ? $subkegiatan->aktivitas->sum('nominal') : 0;

                        $data[] = [
                            '',
                            $subkegiatan->nama_subkegiatan,
                            $subkegiatan->anggaran,
                            $subkegiatan_kas,
                            '',
                            '',
                            '',
                            $subkegiatan_kas,
                            $subkegiatan->anggaran - $subkegiatan_kas,
                            $subkegiatan->anggaran - $subkegiatan_kas,
                        ];
                    }
                }
            }
        }

        return FacadesExcel::download(new LaporanKertasKerjaExport($data, $this->bulan_akhir, $this->tahun), 'realisasi_belanja.xlsx', Excel::XLSX);
    }

    public function exportExcel1()
    {
        return FacadesExcel::download(new EvaluasiRencanaKerjaExport($this->programs, $this->tahun), 'evaluasi_rencana_kerja.xlsx', Excel::XLSX);
    }


    public function render()
    {
        $this->programs = Program::with(['subprograms.kegiatans'])->get();

        // Dummy data anggaran, sesuaikan dengan field di database Anda
        foreach ($this->programs as $program) {
            $program->anggaran_apbd = 0; // Ganti dengan field sebenarnya
            $program->anggaran_kas = 0;
            foreach ($program->subprograms as $subprogram) {
                $subprogram->anggaran_apbd = 0;
                $subprogram->anggaran_kas = 0;
                foreach ($subprogram->kegiatans as $kegiatan) {
                    $kegiatan->anggaran_apbd = 0;
                    $kegiatan->anggaran_kas = 0;
                }
            }
        }
        return view('livewire.laporan-kertas-kerja', [
            'programs' => $this->programs,
            'dataLaporan' => $this->dataLaporan,
        ]);
    }
}
