<?php

namespace App\Exports;

use App\Models\IkatanDinas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IkatanDinasExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $ikatan = IkatanDinas::get();
        return view('dashboard.ikatan_dinas.export', compact('ikatan'));
    }
}
