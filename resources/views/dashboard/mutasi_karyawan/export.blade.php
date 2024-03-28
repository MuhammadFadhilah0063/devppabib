<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h1>MUTASI KARYAWAN</h1>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align: center; border: 1px solid black;">No.</th>
            <th style="text-align: center; border: 1px solid black;">NRP</th>
            <th style="text-align: center; border: 1px solid black;">Nama</th>
            <th style="text-align: center; border: 1px solid black;">Jobsite Lama</th>
            <th style="text-align: center; border: 1px solid black;">Jobsite Tujuan</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Mutasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mutasiKaryawans as $mutasi)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->nrp }}</td>
                <td style="border: 1px solid black;">{{ $mutasi->nama }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->jobsite_lama }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->jobsite_tujuan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ formatDate($mutasi->tgl_mutasi) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
