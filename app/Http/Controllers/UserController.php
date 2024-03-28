<?php

namespace App\Http\Controllers;

use App\Models\Jobsite;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy("id", "DESC")->get();
        $jobsites = Jobsite::get();
        return view("dashboard.user.index", compact('users', 'jobsites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobsites = Jobsite::get();
        $karyawans = Karyawan::get();
        return view("dashboard.user.tambah", compact("jobsites", "karyawans"));
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
                'nrp'          => 'unique:users',
            ],
            [
                'nrp.unique'    => 'NRP sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            if ($request->hasFile('foto')) {

                $imgname = $request->foto->hashName();
                $request->foto->move('storage/foto_profil/', $imgname);

                $status = User::create([
                    'nrp' => $request->nrp,
                    'nama' => $request->nama,
                    'jobsite' => $request->jobsite,
                    'password' => Hash::make(trim($request->password)),
                    'foto' => $imgname,
                ]);
            } else {
                $status = User::create([
                    'nrp' => $request->nrp,
                    'nama' => $request->nama,
                    'jobsite' => $request->jobsite,
                    'password' => Hash::make(trim($request->password)),
                    'foto' => "default.png",
                ]);
            }
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil menyimpan user!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal menyimpan user!');
        }
        return redirect('user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $jobsites = Jobsite::get();
        return view("dashboard.user.edit", compact(["user", "jobsites"]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nrp' => [
                    Rule::unique('users')->ignore($request->id),
                ],
            ],
            [
                'nrp.unique'   => 'NRP sudah ada!',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->with('errors', $validation->errors());
        }

        try {
            if ($request->password_lama != null && $request->password_baru != null) {

                if (Hash::check($request->password_lama, $user->password)) {
                    $status = $user->update([
                        'nrp' => $request->nrp,
                        'nama' => $request->nama,
                        'jobsite' => $request->jobsite,
                        'password' => Hash::make(trim($request->password_baru)),
                    ]);
                } else {
                    return redirect()->back()->with('gagal', "Terjadi kesalahan, password lama salah");
                }
            } else {
                $status = $user->update([
                    'nrp' => $request->nrp,
                    'nama' => $request->nama,
                    'jobsite' => $request->jobsite,
                ]);
            }

            if ($request->hasFile('foto')) {

                // Hapus foto
                if ($user->foto && $user->foto != "default.png") {
                    $filePath = 'storage/foto_profil/' . $user->foto;
                    unlink($filePath);
                }

                // Ubah foto
                $imgname = time() . $request->foto->getClientOriginalName();
                $request->foto->move('storage/foto_profil/', $imgname);

                $status = $user->update([
                    'foto' => $imgname,
                ]);
            }
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil edit user!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal edit user!');
        }
        return redirect('user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        try {
            // Hapus foto
            if ($user->foto && $user->foto != "default.png") {
                $filePath = 'storage/foto_profil/' . $user->foto;
                unlink($filePath);
            }

            $status = $user->delete();
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash('berhasil', 'Berhasil hapus user!');
        } else {
            session()->flash('gagal', 'Terjadi kesalahan, Gagal hapus user!');
        }
        return redirect('user');
    }

    public function profile(User $user)
    {
        $jobsites = Jobsite::get();
        return view('dashboard.user.profile', compact('user', 'jobsites'));
    }

    public function ubahLevel(User $user)
    {
        try {
            $level = ($user->level == "user") ? "admin" : "user";
            $status = $user->update(["level" => $level]);
        } catch (QueryException $err) {
            return redirect()->back()->with('gagal', "Terjadi kesalahan");
        }

        if ($status) {
            session()->flash("berhasil", "Berhasil ubah level user!");
        } else {
            session()->flash("gagal", "Terjadi kesalahan, Gagal ubah level user!");
        }
        return redirect("/user");
    }
}
