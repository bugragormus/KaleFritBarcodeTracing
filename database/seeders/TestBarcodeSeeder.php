<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stock;
use App\Models\Kiln;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\Barcode;
use App\Models\Quantity;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestBarcodeSeeder extends Seeder
{
    public function run()
    {
        // 1. Create permissions if not exist
        $permissions = [
            1 => 'Barkod Yönetimi',
            2 => 'Laboratuvar Yönetimi',
            3 => 'Müşteri İşlemleri',
            4 => 'Kullanıcı Görüntüleme',
            5 => 'Kullanıcı Yönetimi',
            6 => 'Sistem Yönetimi',
        ];

        foreach ($permissions as $id => $name) {
            Permission::updateOrCreate(['id' => $id], ['name' => $name, 'order' => $id]);
        }

        // 2. Create User
        $user = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Admin',
                'registration_number' => 'ADM01',
                'password' => Hash::make('password'),
                'phone' => '1234567890'
            ]
        );
        $user->permissions()->sync(array_keys($permissions));

        // 3. Create Stocks
        $stocks = [];
        $stockNames = ['Frit 101', 'Frit 202', 'Frit 303', 'Compound A', 'Compound B'];
        foreach ($stockNames as $index => $name) {
            $stocks[] = Stock::updateOrCreate(
                ['code' => 'STK' . ($index + 1)],
                ['name' => $name]
            );
        }

        // 4. Create Kilns
        $kilns = [];
        for ($i = 1; $i <= 3; $i++) {
            $kilns[] = Kiln::updateOrCreate(
                ['name' => 'Kiln ' . $i],
                ['daily_production_average' => 1000]
            );
        }

        // 5. Create Warehouses
        $warehouses = [];
        $warehouseNames = ['Main Warehouse', 'QA Warehouse', 'Rejected Warehouse'];
        foreach ($warehouseNames as $name) {
            $warehouses[] = Warehouse::updateOrCreate(['name' => $name]);
        }

        // 6. Create Companies
        $companies = [];
        $companyNames = ['Customer Alpha', 'Customer Beta', 'Customer Gamma'];
        foreach ($companyNames as $name) {
            $companies[] = Company::updateOrCreate(['name' => $name]);
        }

        // 7. Create Barcodes in various statuses
        $statuses = [
            Barcode::STATUS_WAITING,
            Barcode::STATUS_CONTROL_REPEAT,
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_CUSTOMER_TRANSFER,
            Barcode::STATUS_DELIVERED,
        ];

        foreach ($stocks as $stock) {
            foreach ($statuses as $index => $status) {
                // Create a couple of barcodes for each status
                for ($i = 1; $i <= 2; $i++) {
                    $quantity = Quantity::create(['quantity' => rand(500, 1500)]);
                    
                    $barcodeData = [
                        'stock_id' => $stock->id,
                        'kiln_id' => $kilns[array_rand($kilns)]->id,
                        'quantity_id' => $quantity->id,
                        'status' => $status,
                        'party_number' => 'P-' . date('Ymd') . '-' . rand(10, 99),
                        'load_number' => rand(1000, 9999),
                        'created_by' => $user->id,
                    ];

                    // Status specifics
                    if (in_array($status, [Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_REJECTED])) {
                        $barcodeData['warehouse_id'] = $warehouses[0]->id;
                    }
                    
                    if (in_array($status, [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])) {
                        $barcodeData['company_id'] = $companies[array_rand($companies)]->id;
                    }

                    if ($status != Barcode::STATUS_WAITING) {
                        $barcodeData['lab_at'] = now();
                        $barcodeData['lab_by'] = $user->id;
                    }

                    Barcode::create($barcodeData);
                }
            }
        }
    }
}
