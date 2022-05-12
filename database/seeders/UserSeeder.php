<?php

namespace Database\Seeders;

use App\Models\SecretKey;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Wilo Pump Admin',
            'email' => 'admin@wilopump.com',
            'password' => bcrypt('admin')
        ]);

        SecretKey::create(['key' => 'wilopump2022']);
    }
}
