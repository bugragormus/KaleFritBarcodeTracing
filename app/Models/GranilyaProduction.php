<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GranilyaProduction extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_WAITING = 1; //'Beklemede'
    const STATUS_PRE_APPROVED = 3; //'Ön Onaylı'
    const STATUS_SHIPMENT_APPROVED = 4; //'Sevk Onaylı'
    const STATUS_REJECTED = 5; //'Reddedildi'
    const STATUS_SHIPPED = 6; //'Sevk Edildi'
    const STATUS_CORRECTED = 8; //'Düzeltme Faaliyetinde Kullanıldı'
    const STATUS_CUSTOMER_TRANSFER = 9; //'Müşteri Transfer'
    const STATUS_DELIVERED = 10; //'Teslim Edildi'

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public static function getStatusList()
    {
        return [
            self::STATUS_WAITING => 'Beklemede',
            self::STATUS_PRE_APPROVED => 'Ön Onaylı',
            self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
            self::STATUS_REJECTED => 'Reddedildi',
            self::STATUS_SHIPPED => 'Sevk Edildi',
            self::STATUS_CORRECTED => 'Düzeltme Faaliyeti',
            self::STATUS_CUSTOMER_TRANSFER => 'Müşteri Transfer',
            self::STATUS_DELIVERED => 'Teslim Edildi',
        ];
    }

    public static function getSieveRejectionReasons()
    {
        return ['Dirilik', 'Tozama'];
    }

    public static function getSurfaceRejectionReasons()
    {
        return ['Renk', 'Parlaklık'];
    }

    public function getStatusLabelAttribute()
    {
        $list = self::getStatusList();
        return $list[$this->status] ?? 'Bilinmiyor';
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_WAITING:
                return '<span class="status-badge status-waiting">Beklemede</span>';
            case self::STATUS_PRE_APPROVED:
                return '<span class="status-badge status-pre-approved">Ön Onaylı</span>';
            case self::STATUS_SHIPMENT_APPROVED:
                return '<span class="status-badge status-shipment-approved">Sevk Onaylı</span>';
            case self::STATUS_REJECTED:
                return '<span class="status-badge status-rejected">Reddedildi</span>';
            case self::STATUS_SHIPPED:
                return '<span class="status-badge status-shipped">Sevk Edildi</span>';
            case self::STATUS_CORRECTED:
                return '<span class="status-badge status-control-repeat">Düzeltme Faaliyeti</span>';
            case self::STATUS_CUSTOMER_TRANSFER:
                return '<span class="status-badge" style="background:#e0e7ff; color:#3730a3;">Müşteri Transfer</span>';
            case self::STATUS_DELIVERED:
                return '<span class="status-badge" style="background:#065f46; color:#ffffff;">Teslim Edildi</span>';
            default:
                return '<span class="status-badge">Bilinmiyor</span>';
        }
    }

    protected $fillable = [
        'status',
        'stock_id',
        'load_number',
        'size_id',
        'crusher_id',
        'quantity_id',
        'custom_quantity',
        'used_quantity',
        'company_id',
        'customer_type',
        'pallet_number',
        'is_sieve_residue',
        'sieve_residue_quantity',
        'is_correction',
        'correction_source_id',
        'trigger_production_id',
        'user_id',
        'general_note',
        'sieve_test_result',
        'sieve_reject_reason',
        'surface_test_result',
        'surface_reject_reason',
        'arge_test_result',
        'system_note',
        'is_exceptionally_approved',
        'delivery_company_id',
        'delivered_at'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function size()
    {
        return $this->belongsTo(GranilyaSize::class, 'size_id');
    }

    public function crusher()
    {
        return $this->belongsTo(GranilyaCrusher::class, 'crusher_id');
    }

    public function quantity()
    {
        return $this->belongsTo(GranilyaQuantity::class, 'quantity_id');
    }

    public function company()
    {
        return $this->belongsTo(GranilyaCompany::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function histories()
    {
        return $this->hasMany(GranilyaProductionHistory::class, 'production_id');
    }

    public function correctionSource()
    {
        return $this->belongsTo(self::class, 'correction_source_id');
    }

    public function correctionChildren()
    {
        return $this->hasMany(self::class, 'correction_source_id');
    }

    public function triggerProduction()
    {
        return $this->belongsTo(self::class, 'trigger_production_id');
    }

    public function deliveryCompany()
    {
        return $this->belongsTo(GranilyaCompany::class, 'delivery_company_id');
    }

    public function triggeredCorrections()
    {
        return $this->hasMany(self::class, 'trigger_production_id');
    }

    /**
     * Palet numarasından taban numarayı çıkarır. (örn: 1-1 den 1 döner)
     */
    public function getBasePalletNumberAttribute()
    {
        $parts = explode('-', $this->pallet_number);
        return $parts[0] ?? $this->pallet_number;
    }

    /**
     * Test sonuçlarına göre paletin genel durumunu hesaplar.
     */
    public function evaluateTestStatus()
    {
        // Terminal states cannot be changed by tests
        if (in_array($this->status, [
            self::STATUS_SHIPMENT_APPROVED, 
            self::STATUS_CUSTOMER_TRANSFER, 
            self::STATUS_DELIVERED,
            self::STATUS_CORRECTED,
            self::STATUS_SHIPPED
        ])) {
            return;
        }

        // Check if ALL tests have results (not 'Bekliyor')
        $allTestsCompleted = ($this->sieve_test_result !== 'Bekliyor' && $this->surface_test_result !== 'Bekliyor' && $this->arge_test_result !== 'Bekliyor');
        
        $hasRejection = ($this->sieve_test_result === 'Red' || $this->surface_test_result === 'Red' || $this->arge_test_result === 'Red');

        if ($allTestsCompleted) {
            // If all tests are done, status is final based on rejection presence
            if ($hasRejection) {
                $this->status = self::STATUS_REJECTED;
            } else {
                $this->status = self::STATUS_SHIPMENT_APPROVED;
            }
        } else {
            // Not all tests are done yet. Determine intermediate status.
            if ($hasRejection) {
                // Show as rejected in UI, but tests can still be entered because not all are done
                $this->status = self::STATUS_REJECTED; 
            } elseif ($this->sieve_test_result === 'Onay' && $this->surface_test_result === 'Onay') {
                $this->status = self::STATUS_PRE_APPROVED; // Ready for Arge
            } else {
                $this->status = self::STATUS_WAITING; // Still waiting for sieve/surface
            }
        }
    }

    /**
     * İstisnai onay verilip verilemeyeceğini kontrol eder.
     * Sadece Arge reddi durumunda (Elek ve Yüzey onaylıyken) izin verilir.
     */
    public function isExceptionalAllowed()
    {
        // Palet reddedilmiş olmalı
        if ($this->status != self::STATUS_REJECTED) return false;

        // Tüm testlerin (Elek, Yüzey, Arge) sonucunun girilmiş olması şartı
        $allTestsCompleted = ($this->sieve_test_result !== 'Bekliyor' && $this->surface_test_result !== 'Bekliyor' && $this->arge_test_result !== 'Bekliyor');

        if (!$allTestsCompleted) return false;

        // Red sebebi ne olursa olsun istisnai onay verilebilir
        return true;
    }

    /**
     * Mevcut duruma göre geçilebilecek durumları döner.
     */
    public function getAvailableStatuses()
    {
        $all = self::getStatusList();
        $available = [$this->status => $all[$this->status]]; // Mevcut durum her zaman olmalı

        // Terminal durumlar için başka seçenek yok
        if ($this->status == self::STATUS_SHIPMENT_APPROVED || $this->status == self::STATUS_CUSTOMER_TRANSFER || $this->status == self::STATUS_DELIVERED) {
            return $available;
        }

        // 1. BEKLEMEDE -> Ön Onay veya Red
        if ($this->status == self::STATUS_WAITING) {
            $available[self::STATUS_PRE_APPROVED] = $all[self::STATUS_PRE_APPROVED];
            $available[self::STATUS_REJECTED] = $all[self::STATUS_REJECTED];
        }
        // 2. ÖN ONAYLI -> Sevk Onaylı veya Red
        elseif ($this->status == self::STATUS_PRE_APPROVED) {
            $available[self::STATUS_SHIPMENT_APPROVED] = $all[self::STATUS_SHIPMENT_APPROVED];
            $available[self::STATUS_REJECTED] = $all[self::STATUS_REJECTED];
        }
        // 3. REDDEDİLDİ -> İstisnai Onay (Koşullu)
        elseif ($this->status == self::STATUS_REJECTED) {
            if ($this->isExceptionalAllowed()) {
                $available[self::STATUS_SHIPMENT_APPROVED] = $all[self::STATUS_SHIPMENT_APPROVED];
            }
        }

        return $available;
    }

    /**
     * Palet grubunun(1000 KG kuralı) tamamlanıp tamamlanmadığını kontrol eder.
     * Eğer tamamlandıysa (hepsi Sevk Onaylı ve toplam 1000KG), tüm grubun durumunu "Müşteri Transfer" yapar.
     */
    public static function checkAndCompleteGroup($basePalletNumber, $userId = null)
    {
        // Reddedilmiş ve Düzeltme faaliyetinde kullanılmış (Status 5 ve 8) iptal edilmiş çuvalları dahil etmiyoruz.
        $group = self::where('pallet_number', 'LIKE', $basePalletNumber . '-%')
            ->whereNotIn('status', [self::STATUS_REJECTED, self::STATUS_CORRECTED])
            ->get();
            
        $totalWeight = $group->sum('used_quantity');
        
        // 1000 KG değilse tamamlanmamıştır
        if (round($totalWeight, 4) != 1000) {
            return false;
        }
        
        // Hepsi Sevk Onaylı mı? (veya zaten Müşteri Transfer mi)
        $allApproved = $group->every(function ($p) {
            return in_array($p->status, [self::STATUS_SHIPMENT_APPROVED, self::STATUS_CUSTOMER_TRANSFER]);
        });
        
        // Hepside STATUS_SHIPMENT_APPROVED (Sevk Onaylı) varsa
        // onları Müşteri Transfer e taşıyalım
        if ($allApproved) {
            $hasUpdates = false;
            foreach ($group as $p) {
                if ($p->status == self::STATUS_SHIPMENT_APPROVED) {
                    $p->status = self::STATUS_CUSTOMER_TRANSFER;
                    $p->save();
                    
                    \App\Models\GranilyaProductionHistory::create([
                        'production_id' => $p->id,
                        'status'        => $p->status,
                        'user_id'       => $userId ?? (auth()->id() ?? 1),
                        'description'   => 'Palet grubu 1000 KG\'a ulaştı ve tamamlandı. Müşteri Transfer (Satışa Hazır) durumuna geçti.',
                    ]);
                    $hasUpdates = true;
                }
            }
            return $hasUpdates;
        }
        
        return false;
    }
}
