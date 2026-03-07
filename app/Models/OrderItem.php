<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stock_code',
        'granilya_size',
        'quantity_kg',
    ];

    protected $casts = [
        'quantity_kg' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Bu kalemin kullandığı Frit stoğunu döndürür.
     */
    public function getStockModel()
    {
        if (!$this->stock_code) {
            return null;
        }
        return Stock::where('code', $this->stock_code)->first();
    }

    /**
     * Bu sipariş kalemi için mevcut stok analizini döndürür.
     */
    public function analyzeStock(): array
    {
        $type      = $this->order->type;
        $required  = (float) $this->quantity_kg;

        if ($type === Order::TYPE_FRIT) {
            $available = Order::getFritStock($this->stock_code ?: null);
        } else {
            $available = Order::getGranilyaStock($this->stock_code ?: null, $this->granilya_size ?: null);
        }

        $deficit      = max(0, $required - $available);
        $isSufficient = $available >= $required;

        return [
            'available_kg'  => $available,
            'required_kg'   => $required,
            'deficit_kg'    => $deficit,
            'is_sufficient' => $isSufficient,
        ];
    }
}
