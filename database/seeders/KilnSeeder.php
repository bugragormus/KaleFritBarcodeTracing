<?php

namespace Database\Seeders;

use App\Models\Kiln;
use Illuminate\Database\Seeder;

class KilnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kiln::updateOrCreate(['name' => 'C1']);
        Kiln::updateOrCreate(['name' => 'C2']);
        Kiln::updateOrCreate(['name' => 'C3']);
        Kiln::updateOrCreate(['name' => 'C4']);
        Kiln::updateOrCreate(['name' => 'C5']);
        Kiln::updateOrCreate(['name' => 'C6']);
        Kiln::updateOrCreate(['name' => 'C7']);
        Kiln::updateOrCreate(['name' => 'C8']);
        Kiln::updateOrCreate(['name' => 'C9']);
        Kiln::updateOrCreate(['name' => 'C10']);
        Kiln::updateOrCreate(['name' => 'Tecrübe']);

    }
}
