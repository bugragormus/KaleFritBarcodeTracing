<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kiln;

class UpdateKilnsDailyProductionAverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mevcut fırınlar için varsayılan günlük üretim ortalaması değerleri
        $kilns = Kiln::all();
        
        foreach ($kilns as $kiln) {
            // Eğer daily_production_average değeri 0 ise, varsayılan bir değer ata
            if ($kiln->daily_production_average == 0) {
                // Fırın adına göre varsayılan değerler
                $defaultValue = 13.00; // Varsayılan 13 ton/gün
                
                // Fırın adına göre özel değerler
                if (stripos($kiln->name, 'C1') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C2') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C3') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C4') !== false) { 
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C5') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C6') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C7') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C8') !== false) {
                    $defaultValue = 13.00;
                } elseif (stripos($kiln->name, 'C9') !== false) {
                    $defaultValue = 23.00;
                } elseif (stripos($kiln->name, 'C10') !== false) {
                    $defaultValue = 23.00;
                } elseif (stripos($kiln->name, 'Tecrübe') !== false) {
                    $defaultValue = 6.00;
                }
                
                $kiln->update(['daily_production_average' => $defaultValue]);
            }
        }
        
        $this->command->info('Fırınların günlük üretim ortalaması değerleri güncellendi.');
    }
}
