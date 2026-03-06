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
    public function getUniquePalletCount()
    {
        // Palet numarası '1-1', '1-2' formatında olduğu için '-' karakterinden öncesine göre grupluyoruz
        return $this->deliveredProductions()
            ->where('status', GranilyaProduction::STATUS_DELIVERED)
            ->selectRaw('COUNT(DISTINCT SUBSTRING_INDEX(pallet_number, "-", 1)) as unique_pallets')
            ->value('unique_pallets') ?? 0;
    }

    /**
     * Firmaya teslim edilmiş üretimlerin toplam miktarını döner
     */
    public function getTotalDeliveredWeight()
    {
        return $this->deliveredProductions()
            ->where('status', GranilyaProduction::STATUS_DELIVERED)
            ->sum('used_quantity');
    }

    /**
     * Son 30 gündeki teslimat miktarını döner
     */
    public function getLast30DaysWeight()
    {
        return $this->deliveredProductions()
            ->where('status', GranilyaProduction::STATUS_DELIVERED)
            ->where('delivered_at', '>=', now()->subDays(30))
            ->sum('used_quantity');
    }
}
