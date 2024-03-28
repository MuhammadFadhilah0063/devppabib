<?php

namespace App\Exports;

use App\Models\Sertifikasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SertifikasiExport implements FromView, ShouldAutoSize
{
    protected $jobsite;

    public function __construct(string $jobsite)
    {
        $this->jobsite = $jobsite;
    }

    public function view(): View
    {
        $sertifikasis = Sertifikasi::where("jobsite", $this->jobsite)->get();
        return view('dashboard.sertifikasi.export', compact('sertifikasis'));
    }
}
