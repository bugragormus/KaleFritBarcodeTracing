<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class Permissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Yeni yetki yapısına göre permission'ları güncelle
        Permission::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Barkod Yönetimi',
                'order' => 1
            ]
        );
        
        Permission::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Laboratuvar Yönetimi',
                'order' => 2
            ]
        );
        
        Permission::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Müşteri İşlemleri',
                'order' => 3
            ]
        );
        
        Permission::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Kullanıcı Görüntüleme',
                'order' => 4
            ]
        );
        
        Permission::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Kullanıcı Yönetimi',
                'order' => 5
            ]
        );
        
        Permission::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Sistem Yönetimi',
                'order' => 6
            ]
        );
    }
}
