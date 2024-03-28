<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Karyawan::factory(5000)->create();

        // \App\Models\User::create([
        //     'nrp' => '12345',
        //     'nama' => 'Egi Farhan N.',
        //     'password' => Hash::make('12345'),
        //     'id_jobsite' => 1,
        //     'foto' => "profile-img copy.png",
        // ]);
    }
}
