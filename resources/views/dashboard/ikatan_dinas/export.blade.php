<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h1>Ikatan Dinas</h1>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align: center; border: 1px solid black;">No.</th>
            <th style="text-align: center; border: 1px solid black;">NRP</th>
            <th style="text-align: center; border: 1px solid black;">Nama</th>
            <th style="text-align: center; border: 1px solid black;">Nama Kompetensi</th>
            <th style="text-align: center; border: 1px solid black;">Kode</th>
            <th style="text-align: center; border: 1px solid black;">Tanggal Akhir</th>
            <th style="text-align: center; border: 1px solid black;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ikatan as $mutasi)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->nrp }}</td>
                <td style="border: 1px solid black;">{{ $mutasi->nama_karyawan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->nama_kompetensi }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->kode }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ formatDate($mutasi->tgl_akhir) }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $mutasi->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
