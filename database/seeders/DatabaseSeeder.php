<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\Unit::factory(3)->create();

        \App\Models\Jabatan::factory(10)->create();

        \App\Models\User::factory(10)->create();

        \App\Models\UserAccess::factory(200)->create();

        $this->userJabatan();
    }

    private function userJabatan(){
        $users = User::all();
        $jabatan = Jabatan::all();

        foreach ($users as $user) {
            $user->jabatan()->sync([$jabatan->random()->id]);
        }
    }
}
