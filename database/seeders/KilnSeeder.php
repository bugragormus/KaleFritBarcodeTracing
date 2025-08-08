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
        Kiln::updateOrCreate(['name' => 'DF']);
    }
}
