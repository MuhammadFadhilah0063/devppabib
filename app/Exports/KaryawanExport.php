<?php

namespace App\Exports;

use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KaryawanExport implements FromView, ShouldAutoSize
{
    protected $jobsite;

    public function __construct(string $jobsite)
    {
        $this->jobsite = $jobsite;
    }

    public function view(): View
    {
        $karyawans = Karyawan::where("jobsite", $this->jobsite)->get();
        return view('dashboard.karyawan.export', compact('karyawans'));
    }
}
