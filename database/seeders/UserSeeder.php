<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = User::updateOrCreate([
            'name' => 'Buğra GÖRMÜŞ',
            'email' => 'bugra.gormus@hotmail.com',
            'registration_number' => '1',
            'password' => Hash::make('123123'),
            'phone' => '05455949339'
        ]);

        $developer->permissions()->attach([1,2,3,4,5,6]);
    }
}
