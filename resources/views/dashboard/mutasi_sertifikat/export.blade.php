<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="7" style="text-align: center;">
                <h1>MUTASI SERTIFIKASI KARYAWAN</h1>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align: center; border: 1px solid black;">No.</th>
            <th style="text-align: center; border: 1px solid black;">NRP</th>
            <th style="text-align: center; border: 1px solid black;">Nama Karyawan</th>
            <th style="text-align: center; border: 1px solid black;">Jobsite Lama</th>
            <th style="text-align: center; border: 1px solid black;">Jobsite Tujuan</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Mutasi</th>
            <th style="text-align: center; border: 1px solid black;">Sertifikat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mutasiSertifikasis as $mutasi)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->nrp }}</td>
                <td style="border: 1px solid black;">{{ $mutasi->nama_karyawan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->jobsite_lama }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->jobsite_tujuan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ formatDate($mutasi->tgl_mutasi) }}</td>
                <td style="border: 1px solid black;">{{ $mutasi->sertifikat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
