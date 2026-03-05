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

    public static function getStatusList()
    {
        return [
            self::STATUS_WAITING => 'Beklemede',
            self::STATUS_PRE_APPROVED => 'Ön Onaylı',
            self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
            self::STATUS_REJECTED => 'Reddedildi',
            self::STATUS_SHIPPED => 'Sevk Edildi',
            self::STATUS_CORRECTED => 'Düzeltme Faaliyeti',
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
        'user_id',
        'general_note',
        'sieve_test_result',
        'sieve_reject_reason',
        'surface_test_result',
        'surface_reject_reason',
        'arge_test_result',
        'system_note',
        'is_exceptionally_approved'
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

    /**
     * Test sonuçlarına göre paletin genel durumunu hesaplar.
     */
    public function evaluateTestStatus()
    {
        $currentStatus = $this->status;

        // Sevk Onaylı durumu artık değişemez (terminal state)
        if ($currentStatus == self::STATUS_SHIPMENT_APPROVED) {
            return;
        }

        // 1. BEKLEMEDE DURUMU:
        // Beklemeden SADECE Ön Onaylı veya Reddedildi durumuna geçebilir.
        if ($currentStatus == self::STATUS_WAITING) {
            // Elek veya Yüzey testlerinden biri reddedilirse -> Reddedildi
            if ($this->sieve_test_result == 'Red' || $this->surface_test_result == 'Red') {
                $this->status = self::STATUS_REJECTED;
                return;
            }
            // Elek ve Yüzey ikisi de onaylanırsa -> Ön Onaylı
            if ($this->sieve_test_result == 'Onay' && $this->surface_test_result == 'Onay') {
                $this->status = self::STATUS_PRE_APPROVED;
                return;
            }
        }

        // 2. ÖN ONAYLI DURUMU:
        // Ön Onaylıdan SADECE Sevk Onaylı veya Reddedildi durumuna geçebilir.
        if ($currentStatus == self::STATUS_PRE_APPROVED) {
            // Arge reddedilirse -> Reddedildi
            if ($this->arge_test_result == 'Red') {
                $this->status = self::STATUS_REJECTED;
                return;
            }
            // Arge onaylanırsa -> Sevk Onaylı
            if ($this->arge_test_result == 'Onay') {
                $this->status = self::STATUS_SHIPMENT_APPROVED;
                return;
            }
        }

        // 3. REDDEDİLDİ DURUMU:
        // Reddedildi durumundan sadece "İstisnai Onay" işlemi ile Sevk Onaylı durumuna geçilebilir.
        // Bu işlem evaluateTestStatus içinde değil, doğrudan controller üzerinden tetiklenmelidir.
    }

    /**
     * İstisnai onay verilip verilemeyeceğini kontrol eder.
     * Sadece Arge reddi durumunda (Elek ve Yüzey onaylıyken) izin verilir.
     */
    public function isExceptionalAllowed()
    {
        // Palet reddedilmiş olmalı
        if ($this->status != self::STATUS_REJECTED) return false;

        // Elek veya Yüzey reddi varsa istisnai onay OLAMAZ
        if ($this->sieve_test_result == 'Red' || $this->surface_test_result == 'Red') return false;

        // Arge reddi varsa istisnai onay olabilir
        if ($this->arge_test_result == 'Red') return true;

        return false;
    }

    /**
     * Mevcut duruma göre geçilebilecek durumları döner.
     */
    public function getAvailableStatuses()
    {
        $all = self::getStatusList();
        $available = [$this->status => $all[$this->status]]; // Mevcut durum her zaman olmalı

        // Terminal durumlar için başka seçenek yok
        if ($this->status == self::STATUS_SHIPMENT_APPROVED) {
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
}
