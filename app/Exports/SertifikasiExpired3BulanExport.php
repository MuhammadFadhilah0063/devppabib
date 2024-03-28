<?php

namespace App\Exports;

use App\Models\Sertifikasi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SertifikasiExpired3BulanExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        if (Auth::user()->level == "admin") {
            $sertifikasis = Sertifikasi::where(function ($query) {
                $query->where('tgl_exp', '<=', Carbon::now()->addMonths(3)->toDateString())
                    ->where('tgl_exp', '>', Carbon::now()->toDateString());
            })
                ->orWhere(function ($query) {
                    $query->where('tgl_exp', '=', Carbon::now()->toDateString());
                })
                ->orderBy('tgl_exp')
                ->get();
        } else {
            $sertifikasis = Sertifikasi::where(function ($query) {
                $query->where("jobsite", Auth::user()->jobsite)
                    ->where('tgl_exp', '<=', Carbon::now()->addMonths(3)->toDateString())
                    ->where('tgl_exp', '>', Carbon::now()->toDateString());
            })
                ->orWhere(function ($query) {
                    $query->where("jobsite", Auth::user()->jobsite)
                        ->where('tgl_exp', '=', Carbon::now()->toDateString());
                })
                ->orderBy('tgl_exp')
                ->get();
        }

        return view('dashboard.sertifikasi_expired_3_bulan_export', compact('sertifikasis'));
    }
}
