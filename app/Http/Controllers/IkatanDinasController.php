<?php

namespace App\Http\Controllers;

use App\Exports\IkatanDinasExport;
use App\Models\IkatanDinas;
use App\Models\Jobsite;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IkatanDinasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Yajra DataTables
        if (request()->ajax()) {
            $jobsite = request()->input('jobsite');
            $ikatan = IkatanDinas::orderBy("id", "DESC")->get();
            return DataTables::of($ikatan)
                ->make(true);
        }

        $jobsites = Jobsite::get();
        $users = User::get();
        return view("dashboard.ikatan_dinas.index", compact(['jobsites', "users"]));
    }

    public function export()
    {
        return Excel::download(new IkatanDinasExport(), date("d-m-Y") . "ikatan_dinas.xlsx");
    }
}
