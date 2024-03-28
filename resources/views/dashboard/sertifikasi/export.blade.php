<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="15" style="text-align: center;">
                <h1>SERTIFIKASI KARYAWAN {{ $sertifikasis[0]->jobsite }}</h1>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align: center; border: 1px solid black;">No.</th>
            <th style="text-align: center; border: 1px solid black;">NRP</th>
            <th style="text-align: center; border: 1px solid black;">Nama Karyawan</th>
            <th style="text-align: center; border: 1px solid black;">Nama Kompetensi</th>
            <th style="text-align: center; border: 1px solid black;">Kode</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Terbit</th>
            <th style="text-align: center; border: 1px solid black;">Lama Berlaku (hari)</th>
            <th style="text-align: center; border: 1px solid black;">Sisa Hari Berlaku</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Expired</th>
            <th style="text-align: center; border: 1px solid black;">Harga</th>
            <th style="text-align: center; border: 1px solid black;">ID Ikatan Dinas</th>
            <th style="text-align: center; border: 1px solid black;">Lama Ikatan Dinas (tahun)</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Akhir Ikatan Dinas</th>
            <th style="text-align: center; border: 1px solid black;">Status Ikatan Dinas</th>
            <th style="text-align: center; border: 1px solid black;">User Input</th>
            <th style="text-align: center; border: 1px solid black;">Sertifikat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sertifikasis as $sertifikasi)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->nrp }}</td>
                <td style="border: 1px solid black;">{{ $sertifikasi->nama_karyawan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->nama_kompetensi }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->kode }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->tgl_terbit }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->lama_berlaku }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ selisihHari($sertifikasi->tgl_exp) }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->tgl_exp }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->harga }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->ikatanDinas->id ?? null }}
                </td>
                <td style="text-align: center; border: 1px solid black;">{{ $sertifikasi->lama_ikatan_dinas }}</td>
                <td style="text-align: center; border: 1px solid black;">
                    @if ($sertifikasi->ikatanDinas)
                        {{ $sertifikasi->ikatanDinas->tgl_akhir }}
                    @endif
                </td>
                <td style="text-align: center; border: 1px solid black;">
                    @if ($sertifikasi->ikatanDinas)
                        @if ($sertifikasi->ikatanDinas->status == 0)
                            Belum Selesai
                        @else
                            Selesai
                        @endif
                    @endif
                </td>
                <td style="border: 1px solid black;">{{ $sertifikasi->pic_input }}</td>
                <td style="border: 1px solid black;">{{ $sertifikasi->sertifikat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
