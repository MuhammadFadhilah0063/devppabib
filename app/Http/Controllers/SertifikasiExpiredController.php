<?php

namespace App\Http\Controllers;

use App\Exports\SertifikasiExpiredExport;
use App\Models\Jobsite;
use App\Models\Sertifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SertifikasiExpiredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->level == "admin") {
            $sertifikasis = Sertifikasi::whereDate('tgl_exp', '<', Carbon::now())->get();
        } else {
            $sertifikasis = Sertifikasi::where("jobsite", Auth::user()->jobsite)->whereDate('tgl_exp', '<', Carbon::now())->get();
        }
        $jobsites = Jobsite::get();
        return view("dashboard.sertifikasi_expired.index", compact("sertifikasis", "jobsites"));
    }

    public function export()
    {
        return Excel::download(new SertifikasiExpiredExport(), date("d-m-Y") . "sertifikasi_expired.xlsx");
    }
}
