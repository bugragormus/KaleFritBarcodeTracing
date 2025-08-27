<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// SoftDeletes kaldırıldı - hard delete için
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Barcode extends Model 
{
    use HasFactory;

    // Yeni durum sabitleri
    const STATUS_WAITING = 1; //'Beklemede';
    const STATUS_CONTROL_REPEAT = 2; //'Kontrol Tekrarı';
    const STATUS_PRE_APPROVED = 3; //'Ön Onaylı';
    const STATUS_SHIPMENT_APPROVED = 4; //'Sevk Onaylı';
    const STATUS_REJECTED = 5; //'Reddedildi';
    const STATUS_CORRECTED = 8; //'Düzeltme Faaliyetinde Kullanıldı';
    const STATUS_CUSTOMER_TRANSFER = 9; //'Müşteri Transfer';
    const STATUS_DELIVERED = 10; //'Teslim Edildi';

    // Eski durumlar (geriye uyumluluk için)
    const STATUS_ACCEPTED = 3; //'Ön Onaylı' - eski STATUS_ACCEPTED ile aynı
    const STATUS_IN_WAREHOUSE = 4; //'Sevk Onaylı' - eski STATUS_IN_WAREHOUSE ile aynı
    const STATUS_ON_DELIVERY = 6; //'Müşteri Transfer' - eski STATUS_ON_DELIVERY ile aynı
    const STATUS_ON_DELIVERY_IN_WAREHOUSE = 6; //'Müşteri Transfer' - eski STATUS_ON_DELIVERY_IN_WAREHOUSE ile aynı
    const STATUS_MERGED = 8; //'Birleştirildi' - değişmedi

    const STATUSES = [
        self::STATUS_WAITING => 'Beklemede',
        self::STATUS_CONTROL_REPEAT => 'Kontrol Tekrarı',
        self::STATUS_PRE_APPROVED => 'Ön Onaylı',
        self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
        self::STATUS_REJECTED => 'Reddedildi',
        self::STATUS_CORRECTED => 'Düzeltme Faaliyetinde Kullanıldı',
        self::STATUS_CUSTOMER_TRANSFER => 'Müşteri Transfer',
        self::STATUS_DELIVERED => 'Teslim Edildi',
        self::STATUS_MERGED => 'Birleştirildi',
    ];

    // Laboratuvar durumları (LAB işlemleri için)
    const LAB_STATUSES = [
        self::STATUS_WAITING => 'Beklemede',
        self::STATUS_CONTROL_REPEAT => 'Kontrol Tekrarı',
        self::STATUS_PRE_APPROVED => 'Ön Onaylı',
        self::STATUS_REJECTED => 'Reddedildi',
    ];

    // Transfer durumları artık kullanılmıyor - sadece ana durum kullanılıyor
    const TRANSFER_STATUSES = [
        self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
        self::STATUS_CUSTOMER_TRANSFER => 'Müşteri Transfer',
        self::STATUS_DELIVERED => 'Teslim Edildi',
    ];

    // İş akışı durumları (sıralı durumlar)
    const WORKFLOW_STATUSES = [
        self::STATUS_WAITING => 'Beklemede',
        self::STATUS_CONTROL_REPEAT => 'Kontrol Tekrarı',
        self::STATUS_PRE_APPROVED => 'Ön Onaylı',
        self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
        self::STATUS_CUSTOMER_TRANSFER => 'Müşteri Transfer',
        self::STATUS_DELIVERED => 'Teslim Edildi',
    ];

    const EVENT_CREATED = 1;
    const EVENT_UPDATED = 2;
    const EVENT_DELETED = 3;
    const EVENT_MERGED = 4;

    const EVENTS = [
        self::EVENT_CREATED => 'Oluşturuldu',
        self::EVENT_UPDATED => 'Düzenlendi',
        self::EVENT_DELETED => 'Silindi',
        self::EVENT_MERGED  => 'Birleştirildi',
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'stock_id',
        'kiln_id',
        'party_number',
        'load_number',
        'rejected_load_number',
        'quantity_id',
        'status',
        // 'transfer_status', // Artık kullanılmıyor
        'lab_at',
        'lab_by',
        'warehouse_id',
        'warehouse_transferred_at',
        'warehouse_transferred_by',
        'company_id',
        'company_transferred_at',
        'created_by',
        'delivered_at',
        'delivered_by',
        'note',
        'lab_note',
        'merged_barcode_id', // Merge olunan barcodlar hasMany
        'old_barcode_id', // Merge olunduğunda, eski barcod id si
        'is_merged', // Başka barkoda merge edildi mi?
        // Düzeltme faaliyeti alanları
        'is_correction',
        'correction_source_barcode_id',
        'correction_quantity',
        'correction_note',
        'is_exceptionally_approved'
        , 'is_returned'
        , 'returned_at'
        , 'returned_by'
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'lab_at' => 'datetime',
        'warehouse_transferred_at' => 'datetime',
        'company_transferred_at' => 'datetime',
        'delivered_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'status' => self::STATUS_WAITING,
        'is_correction' => false,
        'is_merged' => false,
        'is_exceptionally_approved' => false,
        'is_returned' => false,
    ];

    /**
     * Durum geçişlerini kontrol eden metod
     */
    public function canTransitionTo($newStatus)
    {
        // Aynı duruma geçiş her zaman mümkün (not ekleme gibi işlemler için)
        if ($this->status == $newStatus) {
            return true;
        }
        
        $allowedTransitions = [
            self::STATUS_WAITING => [
                self::STATUS_CONTROL_REPEAT,
                self::STATUS_PRE_APPROVED,
                self::STATUS_REJECTED
            ],
            self::STATUS_CONTROL_REPEAT => [
                self::STATUS_PRE_APPROVED,
                self::STATUS_REJECTED
            ],
            self::STATUS_PRE_APPROVED => [
                self::STATUS_SHIPMENT_APPROVED,
                self::STATUS_CONTROL_REPEAT,
                self::STATUS_REJECTED
            ],
            self::STATUS_SHIPMENT_APPROVED => [
                self::STATUS_CUSTOMER_TRANSFER,
                self::STATUS_DELIVERED
            ],
            self::STATUS_CUSTOMER_TRANSFER => [
                self::STATUS_DELIVERED
            ],
            self::STATUS_REJECTED => [
                self::STATUS_CUSTOMER_TRANSFER,
                self::STATUS_DELIVERED
            ],
            self::STATUS_DELIVERED => [
                // Teslim edildi durumundan Ön Onaylı'ya dönüşe izin ver (iade)
                self::STATUS_PRE_APPROVED
            ]
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Durum geçişi yapan metod
     */
    public function transitionTo($newStatus, $userId = null)
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \Exception('Geçersiz durum geçişi');
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;

        // Durum bazlı otomatik alan güncellemeleri
        switch ($newStatus) {
            case self::STATUS_CONTROL_REPEAT:
            case self::STATUS_PRE_APPROVED:
            case self::STATUS_REJECTED:
                $this->lab_at = now();
                $this->lab_by = $userId ?? auth()->id();
                // Teslim edildi -> Ön Onaylı geçişinde iade olarak işaretle
                if ($newStatus === self::STATUS_PRE_APPROVED && $oldStatus == self::STATUS_DELIVERED) {
                    $this->is_returned = true;
                    $this->returned_at = now();
                    $this->returned_by = $userId ?? auth()->id();
                }
                break;
            
            case self::STATUS_SHIPMENT_APPROVED:
                $this->warehouse_transferred_at = now();
                $this->warehouse_transferred_by = $userId ?? auth()->id();
                break;
            
            case self::STATUS_CUSTOMER_TRANSFER:
                $this->company_transferred_at = now();
                // Müşteri transfer durumunda depo bilgisini temizle
                $this->warehouse_id = null;
                $this->warehouse_transferred_at = null;
                $this->warehouse_transferred_by = null;
                
                // Reddedildi durumundan geçiş yapıldıysa istisnai onaylı olarak işaretle
                if ($oldStatus == self::STATUS_REJECTED) {
                    $this->is_exceptionally_approved = true;
                }
                break;
            
            case self::STATUS_DELIVERED:
                $this->delivered_at = now();
                $this->delivered_by = $userId ?? auth()->id();
                // Teslim edildi durumunda depo bilgisini temizle
                $this->warehouse_id = null;
                $this->warehouse_transferred_at = null;
                $this->warehouse_transferred_by = null;
                
                // Reddedildi durumundan geçiş yapıldıysa istisnai onaylı olarak işaretle
                if ($oldStatus == self::STATUS_REJECTED) {
                    $this->is_exceptionally_approved = true;
                }
                break;
        }

        $this->save();

        // Geçmiş kaydı oluştur
        \App\Models\BarcodeHistory::create([
            'barcode_id' => $this->id,
            'status' => $newStatus,
            'user_id' => $userId ?? auth()->id(),
            'description' => self::EVENT_UPDATED,
            'changes' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ],
        ]);

        return $this;
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class)->withDefault();
    }

    public function kiln(): BelongsTo
    {
        return $this->belongsTo(Kiln::class)->withDefault();
    }

    public function quantity(): BelongsTo
    {
        return $this->belongsTo(Quantity::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class)->withDefault();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function labBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lab_by')->withDefault();
    }

    public function warehouseTransferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'warehouse_transferred_by')->withDefault();
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by')->withDefault();
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by')->withDefault();
    }

    /**
     * Düzeltme kaynağı barkod (eğer bu barkod düzeltme faaliyeti ise)
     */
    public function correctionSource(): BelongsTo
    {
        return $this->belongsTo(Barcode::class, 'correction_source_barcode_id')->withDefault();
    }

    /**
     * Bu barkoddan yapılan düzeltmeler
     */
    public function corrections(): HasMany
    {
        return $this->hasMany(Barcode::class, 'correction_source_barcode_id');
    }

    /**
     * Düzeltme faaliyeti mi kontrol et
     */
    public function isCorrection(): bool
    {
        return $this->is_correction === true;
    }

    /**
     * Düzeltme faaliyeti için kullanılan miktar
     */
    public function getCorrectionQuantity(): ?int
    {
        return $this->correction_quantity;
    }

    /**
     * Get rejection reasons for this barcode
     */
    public function rejectionReasons(): BelongsToMany
    {
        return $this->belongsToMany(RejectionReason::class, 'barcode_rejection_reason')
                    ->withTimestamps();
    }

    /**
     * Get rejection reason names as array
     */
    public function getRejectionReasonNames(): array
    {
        return $this->rejectionReasons->pluck('name')->toArray();
    }

    /**
     * Get rejection reason names as comma-separated string
     */
    public function getRejectionReasonNamesString(): string
    {
        return implode(', ', $this->getRejectionReasonNames());
    }

    /**
     * İstisnai onaylı mı kontrol et
     */
    public function isExceptionallyApproved(): bool
    {
        return $this->is_exceptionally_approved === true;
    }

    /**
     * İade edilmiş mi kontrol et
     */
    public function isReturned(): bool
    {
        return $this->is_returned === true;
    }

    /**
     * İstisnai onaylı ürünleri getir
     */
    public static function getExceptionallyApproved(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_exceptionally_approved', true)->get();
    }

    /**
     * İstisnai onaylı ürün sayısını getir
     */
    public static function getExceptionallyApprovedCount(): int
    {
        return static::where('is_exceptionally_approved', true)->count();
    }
}