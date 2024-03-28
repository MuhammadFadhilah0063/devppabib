<?php

namespace App\Exports;

use App\Models\MutasiSertifikasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MutasiSertifikasiExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $mutasiSertifikasis = MutasiSertifikasi::get();
        return view('dashboard.mutasi_sertifikat.export', compact('mutasiSertifikasis'));
    }
}
