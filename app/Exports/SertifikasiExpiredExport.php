<?php

namespace App\Exports;

use App\Models\Sertifikasi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SertifikasiExpiredExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $sertifikasis = Sertifikasi::whereDate('tgl_exp', '<', Carbon::now())->get();
        return view('dashboard.sertifikasi_expired.export', compact('sertifikasis'));
    }
}
