<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_FRIT     = 'frit';
    const TYPE_GRANILYA = 'granilya';

    const STATUS_OPEN      = 'open';
    const STATUS_FULFILLED = 'fulfilled';
    const STATUS_CANCELLED = 'cancelled';

    const STATUS_LABELS = [
        self::STATUS_OPEN      => 'Açık',
        self::STATUS_FULFILLED => 'Karşılandı',
        self::STATUS_CANCELLED => 'İptal',
    ];

    protected $fillable = [
        'type',
        'company_id',
        'company_type', // 'frit' | 'granilya'
        'notes',
        'status',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')
                    ->withDefault(['name' => 'Bilinmiyor']);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Firma adını döndürür (hem Frit hem Granilya tablolarından)
     */
    public function getCompanyNameAttribute(): string
    {
        if (!$this->company_id) return '—';
        if ($this->company_type === self::TYPE_GRANILYA) {
            $c = GranilyaCompany::find($this->company_id);
        } else {
            $c = Company::find($this->company_id);
        }
        return $c ? $c->name : '—';
    }

    /**
     * Frit satışa hazır stok (STATUS_SHIPMENT_APPROVED = 4)
     */
    public static function getFritStock(?string $stockCode = null): float
    {
        $query = Barcode::where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                        ->with('quantity');

        if ($stockCode) {
            $query->whereHas('stock', function ($q) use ($stockCode) {
                $q->where('code', $stockCode);
            });
        }

        return $query->get()->sum(function ($b) {
            return $b->quantity ? $b->quantity->quantity : 0;
        });
    }

    /**
     * Granilya satışa hazır stok (STATUS_CUSTOMER_TRANSFER = 9)
     * stock_code = hangi fritten üretildi (stocks.code)
     * sizeName   = tane boyutu (granilya_sizes.name)
     */
    public static function getGranilyaStock(?string $stockCode = null, ?string $sizeName = null): float
    {
        $query = GranilyaProduction::where('status', GranilyaProduction::STATUS_CUSTOMER_TRANSFER);

        if ($stockCode) {
            $query->whereHas('stock', function ($q) use ($stockCode) {
                $q->where('code', $stockCode);
            });
        }

        if ($sizeName) {
            $query->whereHas('size', function ($q) use ($sizeName) {
                $q->where('name', $sizeName);
            });
        }

        return (float) $query->sum('used_quantity');
    }

    /**
     * Tüm kalemlerin toplam stok analizini döndürür.
     */
    public function analyzeStock(): array
    {
        $items = $this->items;

        if ($items->isEmpty()) {
            return [
                'available_kg'  => 0,
                'required_kg'   => 0,
                'deficit_kg'    => 0,
                'is_sufficient' => true,
                'items'         => [],
            ];
        }

        $totalRequired  = 0;
        $totalDeficit   = 0;
        $isSufficient   = true;
        $itemResults    = [];

        foreach ($items as $item) {
            $a = $item->analyzeStock();
            $totalRequired += $a['required_kg'];
            $totalDeficit  += $a['deficit_kg'];
            if (!$a['is_sufficient']) {
                $isSufficient = false;
            }
            $itemResults[] = array_merge($a, [
                'stock_code'    => $item->stock_code,
                'granilya_size' => $item->granilya_size,
            ]);
        }

        return [
            'available_kg'  => self::getFritStock() ?: self::getGranilyaStock(),
            'required_kg'   => $totalRequired,
            'deficit_kg'    => $totalDeficit,
            'is_sufficient' => $isSufficient,
            'items'         => $itemResults,
        ];
    }

    /**
     * Sipariş durumu için ilk eksik kalemin analizini döndürür (list view için)
     */
    public function getStockAnalysisAttribute(): array
    {
        return $this->analyzeStock();
    }

    public function getStatusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_FRIT ? 'Frit' : 'Granilya';
    }
}
