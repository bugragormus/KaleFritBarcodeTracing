<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicStockQuantity extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'quantity_1',
        'quantity_2',
        'total_quantity',
        'description_1',
        'description_2'
    ];

    protected $casts = [
        'quantity_1' => 'decimal:2',
        'quantity_2' => 'decimal:2',
        'total_quantity' => 'decimal:2'
    ];

    /**
     * Toplam miktarı hesapla ve güncelle
     */
    public function calculateTotal()
    {
        $this->total_quantity = $this->quantity_1 + $this->quantity_2;
        return $this;
    }

    /**
     * Tek kayıt tutacağız, bu yüzden singleton pattern
     */
    public static function getInstance()
    {
        $instance = self::first();
        if (!$instance) {
            $instance = self::create([
                'quantity_1' => 0,
                'quantity_2' => 0,
                'total_quantity' => 0,
                'description_1' => 'Dinamik Stok 1',
                'description_2' => 'Dinamik Stok 2'
            ]);
        }
        return $instance;
    }
}
