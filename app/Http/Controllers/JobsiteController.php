<?php

namespace App\Http\Controllers;

use App\Models\Jobsite;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobsites =  Jobsite::orderBy("id", "DESC")->get();
        return view("dashboard.jobsite.index", compact('jobsites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobsites = Jobsite::get();
        return view("dashboard.jobsite.tambah", compact('jobsites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Jobsite::where("nama_jobsite", strtoupper($request->nama_jobsite))->first() == null) {
            try {
                $status = Jobsite::create([
                    "nama_jobsite" => strtoupper($request->nama_jobsite),
                    "alamat" => $request->alamat,
                ]);
            } catch (QueryException $err) {
                return redirect()->back()->with('gagal', "Terjadi kesalahan");
            }
        } else {
            return redirect()->back()->with('gagal', "Terjadi kesalahan, jobsite $request->nama_jobsite sudah ada");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil menyimpan jobsite!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal menyimpan jobsite!');
        }
        return redirect('jobsite');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Jobsite $jobsite)
    {
        $jobsites = Jobsite::get();
        return view("dashboard.jobsite.edit", compact("jobsite", "jobsites"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Jobsite $jobsite, Request $request)
    {
        // Validasi nama_jobsite
        $validator = Validator::make($request->all(), [
            'nama_jobsite' => [Rule::unique('jobsites', 'nama_jobsite')->ignore($request->nama_jobsite, 'nama_jobsite')]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan, jobsite $request->nama_jobsite sudah ada");
        }

        try {
            $status = $jobsite->update([
                "nama_jobsite" => strtoupper($request->nama_jobsite),
                "alamat" => $request->alamat,
            ]);
        } catch (QueryException $err) {
            if (strpos($err, 'SQLSTATE[23000]')) {
                return redirect()->back()->with('gagal', "Terjadi kesalahan, jobsite $request->nama_jobsite sudah ada");
            } else {
                return redirect()->back()->with('gagal', "Terjadi kesalahan");
            }
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil edit jobsite!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal edit jobsite!');
        }
        return redirect('jobsite');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jobsite $jobsite)
    {
        try {
            $status = $jobsite->delete();
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil hapus jobsite!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal hapus jobsite!');
        }
        return redirect('jobsite');
    }
}
