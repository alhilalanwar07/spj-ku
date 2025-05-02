<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class LaporanKertasKerjaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $data;
    protected $bulanAkhir;
    protected $tahun;

    public function __construct($data, $bulanAkhir, $tahun)
    {
        $this->data = $data;
        $this->bulanAkhir = $bulanAkhir;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        $bulanAkhirName = \Carbon\Carbon::create()->month((int)$this->bulanAkhir)->translatedFormat('F');
        
        return [
            ['REALISASI BELANJA S/D '.strtoupper($bulanAkhirName).' '.$this->tahun],
            ['BAGIAN PERENCANAAN DAN KEUANGAN'],
            ['TAHUN ANGGARAN '.$this->tahun],
            [],
            [
                'No.',
                'PROGRAM/KEGIATAN/SUB KEGIATAN',
                'ANGGARAN APBD '.$this->tahun.' Rp.',
                'ANGGARAN KAS s/d '.strtoupper($bulanAkhirName).' '.$this->tahun.' Rp.',
                'SPJ GAJI s/d '.strtoupper($bulanAkhirName).' '.$this->tahun.' Rp.',
                'SPJ BELANJA DI LUAR GAJI s/d '.strtoupper($bulanAkhirName).' '.$this->tahun.' Rp.',
                'KEGIATAN YANG DILAKSANAKAN NAMUN BELUM SPJ s/d '.strtoupper($bulanAkhirName).' '.$this->tahun,
                'JUMLAH REALISASI s/d '.strtoupper($bulanAkhirName).' '.$this->tahun.' Rp.',
                'SISA ANGGARAN KAS s/d '.strtoupper($bulanAkhirName).' '.$this->tahun.' Rp.',
                'SISA PAGU Rp.'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header utama
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            
            // Style untuk header tabel
            5 => ['font' => ['bold' => true]],
            
            // Style untuk program
            'A6:J1000' => ['font' => ['size' => 10]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 50,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    }
}