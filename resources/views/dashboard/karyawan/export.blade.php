<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h1>KARYAWAN {{ $karyawans[0]->jobsite }}</h1>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align: center; border: 1px solid black;">No.</th>
            <th style="text-align: center; border: 1px solid black;">NRP</th>
            <th style="text-align: center; border: 1px solid black;">Nama</th>
            <th style="text-align: center; border: 1px solid black;">Departemen</th>
            <th style="text-align: center; border: 1px solid black;">Posisi</th>
            <th style="text-align: center; border: 1px solid black;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($karyawans as $karyawan)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $karyawan->nrp }}</td>
                <td style="border: 1px solid black;">{{ $karyawan->nama }}</td>
                <td style="border: 1px solid black;">{{ $karyawan->departemen }}</td>
                <td style="border: 1px solid black;">{{ $karyawan->posisi }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $karyawan->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
