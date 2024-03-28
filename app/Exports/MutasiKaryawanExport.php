<?php

namespace App\Exports;

use App\Models\MutasiKaryawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MutasiKaryawanExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $mutasiKaryawans = MutasiKaryawan::get();
        return view('dashboard.mutasi_karyawan.export', compact('mutasiKaryawans'));
    }
}
