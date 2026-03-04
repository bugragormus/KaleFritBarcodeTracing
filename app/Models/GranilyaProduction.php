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
    const STATUS_CORRECTED = 8; //'Düzeltme Faaliyetinde Kullanıldı'
    const STATUS_EXCEPTIONAL = 12; //'İstisnai Onaylı'

    public static function getStatusList()
    {
        return [
            self::STATUS_WAITING => 'Beklemede',
            self::STATUS_PRE_APPROVED => 'Ön Onaylı',
            self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
            self::STATUS_REJECTED => 'Reddedildi',
            self::STATUS_CORRECTED => 'Düzeltme Faaliyeti',
            self::STATUS_EXCEPTIONAL => 'İstisnai Onaylı',
        ];
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
            case self::STATUS_CORRECTED:
                return '<span class="status-badge status-control-repeat">Düzeltme Faaliyeti</span>';
            case self::STATUS_EXCEPTIONAL:
                return '<span class="status-badge status-exceptional">İstisnai Onay</span>';
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
        'pallet_number',
        'is_sieve_residue',
        'sieve_residue_quantity',
        'user_id',
        'general_note'
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
}
