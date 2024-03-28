<?php

namespace App\Http\Controllers;

use App\Exports\SertifikasiExpired3BulanExport;
use App\Models\IkatanDinas;
use App\Models\Jobsite;
use App\Models\Karyawan;
use App\Models\Sertifikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        // Ubah status ikatan dinas menjadi selesai (1), ketika tanggal akhir melebihi tanggal sekarang
        IkatanDinas::whereDate('tgl_akhir', '<', Carbon::now())->update([
            "status" => 1
        ]);

        $jobsites = Jobsite::get();
        $nama_jobsites = Jobsite::pluck("nama_jobsite")->toArray();

        $jumlahKaryawanPerJobsite = [];
        foreach ($nama_jobsites as $jobsite) {
            $jumlahKaryawan = Karyawan::where('jobsite', $jobsite)->count();
            $jumlahKaryawanPerJobsite[$jobsite] = $jumlahKaryawan;
        }

        $jumlahKaryawanTidakAktifPerJobsite = [];
        foreach ($nama_jobsites as $jobsite) {
            $jumlahKaryawanTidakAktif = Karyawan::where('status', "Tidak Aktif")->where('jobsite', $jobsite)->count();
            $jumlahKaryawanTidakAktifPerJobsite[$jobsite] = $jumlahKaryawanTidakAktif;
        }

        $jumlahSertifikasiPerJobsite = [];
        foreach ($nama_jobsites as $jobsite) {
            $jumlahSertifikasi = Sertifikasi::where('jobsite', $jobsite)->count();
            $jumlahSertifikasiPerJobsite[$jobsite] = $jumlahSertifikasi;
        }

        $jumlahSertifikasiExpiredPerJobsite = [];
        foreach ($nama_jobsites as $jobsite) {
            $jumlahSertifikasiExpired = Sertifikasi::whereDate('tgl_exp', '<', Carbon::now())->where('jobsite', $jobsite)->count();
            $jumlahSertifikasiExpiredPerJobsite[$jobsite] = $jumlahSertifikasiExpired;
        }

        if (Auth::user()->level == "admin") {
            $sertifikasi3BulanExpired = Sertifikasi::where(function ($query) {
                $query->where('tgl_exp', '<=', Carbon::now()->addMonths(3)->toDateString())
                    ->where('tgl_exp', '>', Carbon::now()->toDateString());
            })
                ->orWhere(function ($query) {
                    $query->where('tgl_exp', '=', Carbon::now()->toDateString());
                })
                ->orderBy('tgl_exp')
                ->get();
        } else {
            $sertifikasi3BulanExpired = Sertifikasi::where(function ($query) {
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

        $karyawans = Karyawan::count();
        $karyawanTidakAktif = Karyawan::where("status", "Tidak Aktif")->count();
        $sertifikasis = Sertifikasi::count();
        $sertifikasiExpired = Sertifikasi::whereDate('tgl_exp', '<', Carbon::now())->count();

        return view("dashboard.index", compact("jobsites", "karyawans", "jumlahKaryawanPerJobsite", "sertifikasis", "jumlahSertifikasiPerJobsite", "sertifikasiExpired", "jumlahSertifikasiExpiredPerJobsite", "karyawanTidakAktif", "jumlahKaryawanTidakAktifPerJobsite", "sertifikasi3BulanExpired"));
    }

    public function login()
    {
        $jobsites = Jobsite::select("nama_jobsite")->get();
        return view("login.index", compact("jobsites"));
    }

    public function export()
    {
        return Excel::download(new SertifikasiExpired3BulanExport(), date("d-m-Y") . "sertifikasi_3_bulan_expired.xlsx");
    }

    public function loginAdmin()
    {
        return view("login.index_admin");
    }

    public function auth(Request $request)
    {
        // Ubah status ikatan dinas menjadi selesai (1), ketika tanggal akhir melebihi tanggal sekarang
        IkatanDinas::whereDate('tgl_akhir', '<', Carbon::now())->update([
            "status" => 1
        ]);

        $nrp = $request->nrp;
        $password = $request->password;
        $jobsite = $request->jobsite;
        $user = User::select("jobsite", "level")->where("nrp", $nrp)->first();

        if ($user != null && $user["jobsite"] == $jobsite) {
            if ($user["level"] == "user") {
                if (Auth::attempt(['nrp' => $nrp, 'password' => $password])) {
                    session()->flash('login', true);
                    return redirect('/');
                } else {
                    session()->flash('gagal', 'NRP atau Password anda salah!');
                    return redirect('/login');
                }
            } else {
                session()->flash('gagal', 'Tolong login sebagai admin!');
                return redirect('/login');
            }
        } else {
            session()->flash('gagal', 'NRP atau jobsite salah!');
            return redirect('/login');
        }
    }

    public function authAdmin(Request $request)
    {
        // Ubah status ikatan dinas menjadi selesai (1), ketika tanggal akhir melebihi tanggal sekarang
        IkatanDinas::whereDate('tgl_akhir', '<', Carbon::now())->update([
            "status" => 1
        ]);

        $nrp = $request->nrp;
        $password = $request->password;
        $user = User::select("jobsite", "level")->where("nrp", $nrp)->first();

        if ($user != null) {
            if ($user["level"] == "admin") {
                if (Auth::attempt(['nrp' => $nrp, 'password' => $password])) {
                    session()->flash('login', true);
                    return redirect('/');
                } else {
                    session()->flash('gagal', 'NRP atau Password anda salah!');
                    return redirect('/login/admin');
                }
            } else {
                session()->flash('gagal', 'Tolong login sebagai user!');
                return redirect('/login/admin');
            }
        } else {
            session()->flash('gagal', 'NRP atau Password anda salah!');
            return redirect('/login/admin');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
