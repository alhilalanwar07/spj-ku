<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class EvaluasiRencanaKerjaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $programs;
    protected $tahun;

    public function __construct($programs, $tahun)
    {
        $this->programs = $programs;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = [];
        
        foreach($this->programs as $program) {
            // Data Program
            $data[] = [
                $program->id,
                $program->nama_program,
                $program->indikator_kinerja ?? '-',
                $program->target_volume ?? '100',
                $program->target_satuan ?? '%',
                $program->pagu_anggaran ?? 0,
                $program->realisasi_volume ?? '',
                $program->realisasi_satuan ?? '%',
                $program->realisasi_anggaran ?? 0,
                $program->capaian_kinerja ?? '',
                $program->capaian_keuangan ?? '',
                '' // Keterangan
            ];
            
            // Data Subprogram
            foreach($program->subprograms as $subprogram) {
                $data[] = [
                    '',
                    $subprogram->nama_subprogram,
                    $subprogram->indikator_kinerja ?? '-',
                    $subprogram->target_volume ?? '100',
                    $subprogram->target_satuan ?? '%',
                    $subprogram->pagu_anggaran ?? 0,
                    $subprogram->realisasi_volume ?? '',
                    $subprogram->realisasi_satuan ?? '%',
                    $subprogram->realisasi_anggaran ?? 0,
                    $subprogram->capaian_kinerja ?? '',
                    $subprogram->capaian_keuangan ?? '',
                    '' // Keterangan
                ];
                
                // Data Kegiatan
                foreach($subprogram->kegiatans as $kegiatan) {
                    $jumlah_subkegiatan = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->count() : 0;
                    $realisasi_anggaran = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->flatMap(fn($subkeg) => $subkeg->aktivitas ?? collect([]))->sum('nominal') : 0;
                    $pagu_anggaran = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->sum('anggaran') : 0;
                    $capaian_keuangan = $pagu_anggaran ? round($realisasi_anggaran / $pagu_anggaran * 100) : 0;
                    
                    $data[] = [
                        '',
                        $kegiatan->nama_kegiatan,
                        $kegiatan->indikator_kinerja ?? '-',
                        $jumlah_subkegiatan,
                        'Dokumen',
                        $pagu_anggaran,
                        $kegiatan->realisasi_volume ?? '',
                        $kegiatan->realisasi_satuan ?? '',
                        $realisasi_anggaran,
                        $jumlah_subkegiatan && $kegiatan->realisasi_volume ? round($kegiatan->realisasi_volume / $jumlah_subkegiatan * 100) : '',
                        $capaian_keuangan,
                        '' // Keterangan
                    ];
                }
            }
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['EVALUASI RENCANA KERJA SAMPAI DENGAN TRIWULAN I TAHUN ANGGARAN ' . $this->tahun],
            ['BAGIAN PERENCANAAN DAN KEUANGAN'],
            ['TAHUN ANGGARAN ' . $this->tahun],
            [],
            [
                'No',
                'Program, Kegiatan, Sub Kegiatan',
                'Indikator Kinerja Program (outcome) / Kegiatan (Output) / Sub Kegiatan (Output)',
                'Target Akhir Tahun ' . $this->tahun,
                '',
                '',
                'Realisasi s/d TW I',
                '',
                '',
                'Capaian Kinerja dan Realisasi Anggaran (%)',
                '',
                'Ket.'
            ],
            [
                '',
                '',
                '',
                'Volume',
                'Satuan',
                'Pagu Anggaran (Rp)',
                'Volume',
                'Satuan',
                'Realisasi Anggaran (Rp)',
                'Kinerja',
                'Keuangan',
                ''
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells untuk judul utama
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $sheet->mergeCells('A5:L5');
        $sheet->mergeCells('D5:F5');
        $sheet->mergeCells('G5:I5');
        $sheet->mergeCells('J5:K5');
        
        // Style untuk seluruh tabel
        $sheet->getStyle('A1:L' . ($sheet->getHighestRow()))->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 10
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        
        // Style khusus untuk header
        $sheet->getStyle('A1:L6')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFE3F0FA',
                ],
            ],
        ]);
        
        // Style untuk program
        $sheet->getStyle('A7:L' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ]);
        
        // Style untuk angka
        $sheet->getStyle('F7:F' . $sheet->getHighestRow())->applyFromArray([
            'numberFormat' => [
                'formatCode' => '#,##0'
            ]
        ]);
        $sheet->getStyle('I7:I' . $sheet->getHighestRow())->applyFromArray([
            'numberFormat' => [
                'formatCode' => '#,##0'
            ]
        ]);
        
        // Warna background untuk program
        $sheet->getStyle('A7:L7')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF90C7F1',
                ],
            ],
        ]);
        
        // Warna background untuk subprogram
        $sheet->getStyle('A8:L8')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFD3E6F4',
                ],
            ],
        ]);
        
        return [
            1 => ['font' => ['size' => 16]],
            2 => ['font' => ['size' => 12]],
            3 => ['font' => ['size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 40, // Program/Kegiatan
            'C' => 40, // Indikator Kinerja
            'D' => 10, // Target Volume
            'E' => 10, // Target Satuan
            'F' => 15, // Pagu Anggaran
            'G' => 10, // Realisasi Volume
            'H' => 10, // Realisasi Satuan
            'I' => 15, // Realisasi Anggaran
            'J' => 10, // Capaian Kinerja
            'K' => 10, // Capaian Keuangan
            'L' => 15, // Keterangan
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_PERCENTAGE_00,
            'K' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
    }
}