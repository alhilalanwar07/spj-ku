<div>
    <h5 class="text-center">REALISASI BELANJA S/D <span class="text-primary">{{ strtoupper(\Carbon\Carbon::create()->month((int)$bulan_akhir)->translatedFormat('F')) }} {{ $tahun }}</span></h5>
    <h6 class="text-center">BAGIAN PERENCANAAN DAN KEUANGAN<br>TAHUN ANGGARAN {{ $tahun }}</h6>
    <div class="row mb-3">
        <div class="col-md-2">
            <label>Bulan Awal</label>
            <select wire:model="bulan_awal" class="form-control">
                @for($i=1;$i<=12;$i++)
                    <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month((int)$i)->translatedFormat('F') }}</option>
                    @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label>Bulan Akhir</label>
            <select wire:model.live="bulan_akhir" class="form-control">
                @for($i=1;$i<=12;$i++)
                    <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month((int)$i)->translatedFormat('F') }}</option>
                    @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label>Tahun</label>
            <input type="number" wire:model.live="tahun" class="form-control" min="2020" max="2100">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-success" wire:click="exportExcel"><i class="fa fa-file-excel"></i> Export Excel</button>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-success" wire:click="exportExcel1"><i class="fa fa-file-excel"></i> Export II</button>
        </div>
    </div>
    <div class="table-responsive">
        <table border="1" cellpadding="4" cellspacing="0" width="100%">
            <thead style="border:1px solid black;">
                <tr style="border:1px solid black;">
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">No.</th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">PROGRAM/KEGIATAN/SUB KEGIATAN</th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">ANGGARAN APBD 2025<br>Rp.</th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">ANGGARAN KAS s/d MARET 2025<br>Rp.</th>
                    <th colspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">REALISASI</th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">KEGIATAN YANG DILAKSANAKAN<br>NAMUN BELUM SPJ s/d MARET 2025</th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">
                        JUMLAH REALISASI s/d MARET 2025<br>Rp.
                    </th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">
                        SISA ANGGARAN KAS s/d MARET 2025<br>Rp.
                    </th>
                    <th rowspan="2" style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">
                        SISA PAGU<br>Rp.
                    </th>
                </tr>
                <tr>
                    <th style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">SPJ GAJI s/d MARET 2025<br>Rp.</th>
                    <th style="background:#4EB2FFFF;text-align:center;font-weight:bold;border:1px solid black;">SPJ BELANJA DI LUAR GAJI s/d MARET 2025<br>Rp.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programs as $program)
                @php
                $subprograms = $program->subprograms ?? collect([]);
                // Jumlahkan anggaran dan kas seluruh subkegiatan di bawah program
                $total_apbd = $subprograms->flatMap(fn($subprog) =>
                $subprog->kegiatans ?? collect([])
                )->flatMap(fn($keg) =>
                $keg->subkegiatans ?? collect([])
                )->sum('anggaran');
                $total_kas = $subprograms->flatMap(fn($subprog) =>
                $subprog->kegiatans ?? collect([])
                )->flatMap(fn($keg) =>
                $keg->subkegiatans ?? collect([])
                )->flatMap(fn($subkeg) =>
                $subkeg->aktivitas ?? collect([])
                )->sum('nominal');
                @endphp
                <tr style="background:#e3f0fa;font-weight:bold;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $program->nama_program }}</td>
                    <td>{{$total_apbd}}</td>
                    <td>{{ $total_kas }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($total_kas,0,',','.') }}</td>
                    <td>{{ number_format($total_apbd - $total_kas,0,',','.') }}</td>
                    <td>{{ number_format($total_apbd - $total_kas,0,',','.') }}</td>
                </tr>
                @foreach($subprograms as $subprogram)
                @php
                $kegiatans = $subprogram->kegiatans ?? collect([]);
                $subprogram_apbd = $kegiatans->flatMap(fn($keg) =>
                $keg->subkegiatans ?? collect([])
                )->sum('anggaran');
                $subprogram_kas = $kegiatans->flatMap(fn($keg) =>
                $keg->subkegiatans ?? collect([])
                )->flatMap(fn($subkeg) =>
                $subkeg->aktivitas ?? collect([])
                )->sum('nominal');
                @endphp
                <tr style="background:#d0e6f7;font-weight:bold;">
                    <td></td>
                    <td>{{ $subprogram->nama_subprogram }}</td>
                    <td>{{ $subprogram_apbd}}</td>
                    <td>{{ number_format($subprogram_kas,0,',','.') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($subprogram_kas,0,',','.') }}</td>
                    <td>{{ number_format($subprogram_apbd - $subprogram_kas,0,',','.') }}</td>
                    <td>{{ number_format($subprogram_apbd - $subprogram_kas,0,',','.') }}</td>
                </tr>
                @foreach($kegiatans as $kegiatan)
                @php
                $subkegiatans = $kegiatan->subkegiatans ?? collect([]);
                $kegiatan_apbd = $subkegiatans->sum('anggaran');
                $kegiatan_kas = $subkegiatans->flatMap(fn($subkeg) =>
                $subkeg->aktivitas ?? collect([])
                )->sum('nominal');
                @endphp
                <tr style="background:#d9eaf7;">
                    <td></td>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{$kegiatan_apbd}}</td>
                    <td>{{ number_format($kegiatan_kas,0,',','.') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($kegiatan_kas,0,',','.') }}</td>
                    <td>{{ number_format($kegiatan_apbd - $kegiatan_kas,0,',','.') }}</td>
                    <td>{{ number_format($kegiatan_apbd - $kegiatan_kas,0,',','.') }}</td>
                </tr>
                @foreach($subkegiatans as $subkegiatan)
                @php
                $subkegiatan_kas = $subkegiatan->aktivitas ? $subkegiatan->aktivitas->sum('nominal') : 0;
                @endphp
                <tr>
                    <td></td>
                    <td>{{ $subkegiatan->nama_subkegiatan }}</td>
                    <td>{{ number_format($subkegiatan->anggaran,0,',','.') }}</td>
                    <td>{{ number_format($subkegiatan_kas,0,',','.') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($subkegiatan_kas,0,',','.') }}</td>
                    <td>{{ number_format($subkegiatan->anggaran - $subkegiatan_kas,0,',','.') }}</td>
                    <td>{{ number_format($subkegiatan->anggaran - $subkegiatan_kas,0,',','.') }}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <p></p>
    <p></p>
    <p></p>
    <div class="table-responsive">
        <table border="1" cellpadding="4" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid black;">
            <thead>
                <tr style="border: 1px solid black;">
                    <th colspan="11" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">
                        EVALUASI RENCANA KERJA SAMPAI DENGAN TRIWULAN I TAHUN ANGGARAN {{ $tahun }}
                    </th>
                </tr>
                <tr style="border: 1px solid black;">
                    <th rowspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">No</th>
                    <th rowspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Program, Kegiatan, Sub Kegiatan</th>
                    <th rowspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Indikator Kinerja Program (outcome) / Kegiatan (Output) / Sub Kegiatan (Output)</th>
                    <th colspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Target Akhir Tahun {{ $tahun }}</th>
                    <th colspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Realisasi s/d TW I</th>
                    <th colspan="2" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Capaian Kinerja dan Realisasi Anggaran (%)</th>
                    <th rowspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Ket.</th>
                </tr>
                <tr style="border: 1px solid black;">
                    <th colspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Target Kinerja</th>
                    <th colspan="3" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Realisasi Kinerja</th>
                    <th rowspan="2" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Kinerja</th>
                    <th rowspan="2" style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Keuangan</th>
                </tr>
                <tr style="border: 1px solid black;">
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Volume</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Satuan</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Pagu Anggaran<br>(Rp)</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Volume</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Satuan</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">Realisasi Anggaran<br>(Rp)</th>
                </tr>
                <tr style="border: 1px solid black;">
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">1</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">2</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">3</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">4</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">5</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">6</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">7</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">8</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">9</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">10</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">11</th>
                    <th style="background:#e3f0fa;text-align:center;font-weight:bold; border: 1px solid black;">12</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programs as $program)
                <tr style="background:#90C7F1FF;font-weight:bold;">
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:bold; border: 1px solid black;">{{ $program->nama_program }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->indikator_kinerja ?? '-' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->target_volume ?? '100' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->target_satuan ?? '%' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($program->pagu_anggaran ?? 0,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->realisasi_volume ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->realisasi_satuan ?? '%' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($program->realisasi_anggaran ?? 0,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->capaian_kinerja ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $program->capaian_keuangan ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;"></td>
                </tr>
                @foreach($program->subprograms as $subprogram)
                <tr style="background:#D3E6F4FF;">
                    <td></td>
                    <td style="font-weight:bold;border: 1px solid black;">{{ $subprogram->nama_subprogram }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->indikator_kinerja ?? '-' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->target_volume ?? '100' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->target_satuan ?? '%' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($subprogram->pagu_anggaran ?? 0,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->realisasi_volume ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->realisasi_satuan ?? '%' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($subprogram->realisasi_anggaran ?? 0,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->capaian_kinerja ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $subprogram->capaian_keuangan ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;"></td>
                </tr>
                @foreach($subprogram->kegiatans as $kegiatan)
                @php
                $jumlah_subkegiatan = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->count() : 0;
                $realisasi_anggaran = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->flatMap(fn($subkeg) => $subkeg->aktivitas ?? collect([]))->sum('nominal') : 0;
                $pagu_anggaran = $kegiatan->subkegiatans ? $kegiatan->subkegiatans->sum('anggaran') : 0;
                $capaian_keuangan = $pagu_anggaran ? round($realisasi_anggaran / $pagu_anggaran * 100) : 0;
                @endphp
                <tr>
                    <td></td>
                    <td style="border: 1px solid black;">{{ $kegiatan->nama_kegiatan }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $kegiatan->indikator_kinerja ?? '-' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $jumlah_subkegiatan }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">Dokumen</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($pagu_anggaran,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $kegiatan->realisasi_volume ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ $kegiatan->realisasi_satuan ?? '' }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">{{ number_format($realisasi_anggaran,0,',','.') }}</td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">
                        {{-- Capaian Kinerja: contoh rumus --}}
                        {{ $jumlah_subkegiatan && $kegiatan->realisasi_volume ? round($kegiatan->realisasi_volume / $jumlah_subkegiatan * 100) : '' }}
                    </td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;">
                        {{-- Capaian Keuangan --}}
                        {{ $capaian_keuangan }}
                    </td>
                    <td style="text-align:center;font-weight:bold; border: 1px solid black;"></td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>