<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GranilyaCompany extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'code', 'is_active'];

    /**
     * Firmaya teslim edilmiş (Satılmış) üretimler
     */
    public function deliveredProductions()
    {
        return $this->hasMany(GranilyaProduction::class, 'delivery_company_id');
    }

    /**
     * Firmaya teslim edilmiş tekil palet sayısını döner
     */
    public function getUniquePalletCount($startDate = null, $endDate = null)
    {
        $query = $this->deliveredProductions()
            ->where('status', GranilyaProduction::STATUS_DELIVERED);

        if ($startDate && $endDate) {
            $query->whereBetween('delivered_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('COUNT(DISTINCT SUBSTRING_INDEX(pallet_number, "-", 1)) as unique_pallets')
            ->value('unique_pallets') ?? 0;
    }

    /**
     * Firmaya teslim edilmiş üretimlerin toplam miktarını döner
     */
    public function getTotalDeliveredWeight($startDate = null, $endDate = null)
    {
        $query = $this->deliveredProductions()
            ->where('status', GranilyaProduction::STATUS_DELIVERED);

        if ($startDate && $endDate) {
            $query->whereBetween('delivered_at', [$startDate, $endDate]);
        }

        return $query->sum('used_quantity');
    }

    /**
     * Son 30 gündeki teslimat miktarını döner (Filtrelenmiş toplam varsa ona göre değişebilir ama genelde statik bir gösterge olur)
     */
    public function getLast30DaysWeight()
    {
        return $this->getTotalDeliveredWeight(now()->subDays(30), now());
    }
}
