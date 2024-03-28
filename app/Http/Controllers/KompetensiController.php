<?php

namespace App\Http\Controllers;

use App\Imports\KompetensisImport;
use App\Models\Jobsite;
use App\Models\Kompetensi;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class KompetensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kompetensis = Kompetensi::orderBy("id", "DESC")->get();
        $jobsites = Jobsite::get();
        return view("dashboard.kompetensi.index", compact('kompetensis', 'jobsites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobsites = Jobsite::get();
        return view("dashboard.kompetensi.tambah", compact("jobsites"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nama'          => 'unique:kompetensis',
                'kode'          => 'unique:kompetensis',
            ],
            [
                'nama.unique'    => 'Nama kompetensi sudah ada!',
                'kode.unique'    => 'Kode kompetensi sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            $status = Kompetensi::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'jenis' => $request->jenis,
                'sertifikasi' => $request->sertifikasi,
            ]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil menyimpan kompetensi!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal menyimpan kompetensi!');
        }
        return redirect('komp');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Kompetensi $kompetensi)
    {
        $jobsites = Jobsite::get();
        return view("dashboard.kompetensi.edit", compact("kompetensi", "jobsites"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Kompetensi $kompetensi, Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nama' => [
                    Rule::unique('kompetensis')->ignore($request->id),
                ],
                'kode' => [
                    Rule::unique('kompetensis')->ignore($request->id),
                ],
            ],
            [
                'nama.unique'   => 'Nama kompetensi sudah ada!',
                'kode.unique'   => 'Kode kompetensi sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            $status = $kompetensi->update([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'jenis' => $request->jenis,
                'sertifikasi' => $request->sertifikasi,
            ]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil edit Kompetensi!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal edit Kompetensi!');
        }
        return redirect('komp');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kompetensi $kompetensi)
    {
        try {
            $status = $kompetensi->delete();
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil hapus kompetensi!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal hapus kompetensi!');
        }
        return redirect('komp');
    }

    public function getKompetensi($nama)
    {
        return Kompetensi::select("kode")->where('nama', $nama)->first();
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();

        // simpan file sementara
        $file->move(public_path('storage/export/'), $nama_file);

        $filePath = public_path('storage/export/' . $nama_file);

        // import data
        $import = Excel::import(new KompetensisImport(), $filePath);

        // hapus file
        unlink($filePath);

        if ($import) {
            session()->flash("berhasil", "Berhasil import data kompetensi!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal import data kompetensi!");
        }
        return redirect("komp");
    }
}
