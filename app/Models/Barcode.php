<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Barcode extends Model 
{
    use HasFactory, SoftDeletes;

    // Status constants
    public const STATUS_WAITING = 0;
    public const STATUS_CONTROL_REPEAT = 1;
    public const STATUS_PRE_APPROVED = 2;
    public const STATUS_SHIPMENT_APPROVED = 3;
    public const STATUS_REJECTED = 4;
    public const STATUS_CUSTOMER_TRANSFER = 5;
    public const STATUS_DELIVERED = 6;
    public const STATUS_MERGED = 7;

    public static $statuses = [
        self::STATUS_WAITING => 'Beklemede',
        self::STATUS_CONTROL_REPEAT => 'Kontrol Tekrarı',
        self::STATUS_PRE_APPROVED => 'Ön Onaylı',
        self::STATUS_SHIPMENT_APPROVED => 'Sevk Onaylı',
        self::STATUS_REJECTED => 'Reddedildi',
        self::STATUS_CUSTOMER_TRANSFER => 'Müşteri Transfer',
        self::STATUS_DELIVERED => 'Teslim Edildi',
        self::STATUS_MERGED => 'Birleştirildi',
    ];

    // Event constants
    public const EVENT_CREATED = 'Barkod oluşturuldu';
    public const EVENT_STATUS_CHANGED = 'Durum değiştirildi';
    public const EVENT_LAB_PROCESSED = 'Laboratuvar işlemi';
    public const EVENT_WAREHOUSE_TRANSFERRED = 'Depo transferi';
    public const EVENT_CUSTOMER_TRANSFERRED = 'Müşteri transferi';
    public const EVENT_DELIVERED = 'Teslim edildi';
    public const EVENT_MERGED = 'Barkod birleştirildi';

    public static $events = [
        self::EVENT_CREATED,
        self::EVENT_STATUS_CHANGED,
        self::EVENT_LAB_PROCESSED,
        self::EVENT_WAREHOUSE_TRANSFERRED,
        self::EVENT_CUSTOMER_TRANSFERRED,
        self::EVENT_DELIVERED,
        self::EVENT_MERGED,
    ];

    protected $fillable = [
        'stock_id',
        'kiln_id',
        'quantity_id',
        'party_number',
        'load_number',
        'rejected_load_number',
        'status',
        'transfer_status',
        'lab_at',
        'lab_by',
        'warehouse_transferred_at',
        'warehouse_transferred_by',
        'warehouse_id',
        'company_id',
        'company_transferred_at',
        'created_by',
        'delivered_at',
        'delivered_by',
        'note',
        'lab_note',
        'merged_barcode_id',
    ];

    protected $casts = [
        'lab_at' => 'datetime',
        'warehouse_transferred_at' => 'datetime',
        'company_transferred_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Before creating a barcode
        static::creating(function ($barcode) {
            // Validate load number uniqueness
            if ($barcode->load_number && $barcode->kiln_id) {
                $exists = static::where('kiln_id', $barcode->kiln_id)
                    ->where('load_number', $barcode->load_number)
                    ->exists();
                
                if ($exists) {
                    throw new \Exception("Bu fırın için şarj numarası zaten kullanılmış: {$barcode->load_number}");
                }
            }

            // Set default status
            if (!isset($barcode->status)) {
                $barcode->status = self::STATUS_WAITING;
            }

            // Validate required relationships
            if (!$barcode->stock_id || !$barcode->kiln_id || !$barcode->quantity_id) {
                throw new \Exception("Stok, fırın ve miktar bilgileri zorunludur.");
            }
        });

        // After creating a barcode
        static::created(function ($barcode) {
            // Log the creation
            Log::info('Barcode created', [
                'barcode_id' => $barcode->id,
                'user_id' => auth()->id(),
                'stock_id' => $barcode->stock_id,
                'kiln_id' => $barcode->kiln_id,
                'load_number' => $barcode->load_number
            ]);

            // Create history record
            BarcodeHistory::create([
                'barcode_id' => $barcode->id,
                'status' => $barcode->status,
                'user_id' => auth()->id(),
                'description' => self::EVENT_CREATED,
            ]);
        });

        // Before updating a barcode
        static::updating(function ($barcode) {
            // Validate status transitions
            if ($barcode->isDirty('status')) {
                $oldStatus = $barcode->getOriginal('status');
                $newStatus = $barcode->status;
                
                if (!self::isValidStatusTransition($oldStatus, $newStatus)) {
                    throw new \Exception("Geçersiz durum geçişi: {$oldStatus} -> {$newStatus}");
                }
            }

            // Validate load number changes
            if ($barcode->isDirty('load_number') && $barcode->load_number) {
                $exists = static::where('kiln_id', $barcode->kiln_id)
                    ->where('load_number', $barcode->load_number)
                    ->where('id', '!=', $barcode->id)
                    ->exists();
                
                if ($exists) {
                    throw new \Exception("Bu fırın için şarj numarası zaten kullanılmış: {$barcode->load_number}");
                }
            }
        });

        // After updating a barcode
        static::updated(function ($barcode) {
            // Log status changes
            if ($barcode->wasChanged('status')) {
                Log::info('Barcode status changed', [
                    'barcode_id' => $barcode->id,
                    'user_id' => auth()->id(),
                    'old_status' => $barcode->getOriginal('status'),
                    'new_status' => $barcode->status
                ]);

                // Create history record for status change
                BarcodeHistory::create([
                    'barcode_id' => $barcode->id,
                    'status' => $barcode->status,
                    'user_id' => auth()->id(),
                    'description' => self::EVENT_STATUS_CHANGED,
                ]);
            }
        });

        // Before deleting a barcode
        static::deleting(function ($barcode) {
            // Check if barcode can be deleted
            if ($barcode->status !== self::STATUS_WAITING) {
                throw new \Exception("Sadece bekleyen durumdaki barkodlar silinebilir.");
            }

            // Check if barcode is referenced by other barcodes
            $referencedBy = static::where('merged_barcode_id', $barcode->id)->count();
            if ($referencedBy > 0) {
                throw new \Exception("Bu barkod başka barkodlar tarafından referans ediliyor, silinemez.");
            }
        });
    }

    /**
     * Validate status transition
     */
    public static function isValidStatusTransition($fromStatus, $toStatus): bool
    {
        $validTransitions = [
            self::STATUS_WAITING => [
                self::STATUS_CONTROL_REPEAT,
                self::STATUS_PRE_APPROVED,
                self::STATUS_REJECTED,
                self::STATUS_MERGED
            ],
            self::STATUS_CONTROL_REPEAT => [
                self::STATUS_PRE_APPROVED,
                self::STATUS_REJECTED,
                self::STATUS_MERGED
            ],
            self::STATUS_PRE_APPROVED => [
                self::STATUS_SHIPMENT_APPROVED,
                self::STATUS_REJECTED,
                self::STATUS_MERGED
            ],
            self::STATUS_SHIPMENT_APPROVED => [
                self::STATUS_CUSTOMER_TRANSFER,
                self::STATUS_REJECTED
            ],
            self::STATUS_CUSTOMER_TRANSFER => [
                self::STATUS_DELIVERED,
                self::STATUS_REJECTED
            ],
            self::STATUS_REJECTED => [
                self::STATUS_MERGED
            ]
        ];

        return in_array($toStatus, $validTransitions[$fromStatus] ?? []);
    }

    /**
     * Get status name
     */
    public function getStatusNameAttribute(): string
    {
        return self::$statuses[$this->status] ?? 'Bilinmiyor';
    }

    /**
     * Check if barcode is in final state
     */
    public function isInFinalState(): bool
    {
        return in_array($this->status, [
            self::STATUS_DELIVERED,
            self::STATUS_MERGED
        ]);
    }

    /**
     * Check if barcode can be merged
     */
    public function canBeMerged(): bool
    {
        return in_array($this->status, [
            self::STATUS_REJECTED,
            self::STATUS_WAITING
        ]);
    }

    /**
     * Get barcode history
     */
    public function history()
    {
        return $this->hasMany(BarcodeHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get stock information
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get kiln information
     */
    public function kiln()
    {
        return $this->belongsTo(Kiln::class);
    }

    /**
     * Get quantity information
     */
    public function quantity()
    {
        return $this->belongsTo(Quantity::class);
    }

    /**
     * Get warehouse information
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get company information
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get lab processed by user
     */
    public function labBy()
    {
        return $this->belongsTo(User::class, 'lab_by');
    }

    /**
     * Get warehouse transferred by user
     */
    public function warehouseTransferredBy()
    {
        return $this->belongsTo(User::class, 'warehouse_transferred_by');
    }

    /**
     * Get delivered by user
     */
    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    /**
     * Get merged barcode
     */
    public function mergedBarcode()
    {
        return $this->belongsTo(Barcode::class, 'merged_barcode_id');
    }

    /**
     * Get barcodes that reference this barcode
     */
    public function referencedBy()
    {
        return $this->hasMany(Barcode::class, 'merged_barcode_id');
    }

    /**
     * Scope for waiting barcodes
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    /**
     * Scope for rejected barcodes
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for approved barcodes
     */
    public function scopeApproved($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PRE_APPROVED,
            self::STATUS_SHIPMENT_APPROVED
        ]);
    }

    /**
     * Scope for delivered barcodes
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Scope for today's barcodes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this month's barcodes
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Get barcode statistics
     */
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'waiting' => self::waiting()->count(),
            'rejected' => self::rejected()->count(),
            'approved' => self::approved()->count(),
            'delivered' => self::delivered()->count(),
            'today' => self::today()->count(),
            'this_month' => self::thisMonth()->count(),
        ];
    }

    /**
     * Check for data integrity issues
     */
    public static function checkDataIntegrity(): array
    {
        $issues = [];

        // Check for duplicate load numbers
        $duplicates = DB::select('
            SELECT kiln_id, load_number, COUNT(*) as count 
            FROM barcodes 
            WHERE load_number IS NOT NULL 
            GROUP BY kiln_id, load_number 
            HAVING count > 1
        ');

        if (!empty($duplicates)) {
            $issues[] = [
                'type' => 'duplicate_load_numbers',
                'message' => count($duplicates) . ' adet duplicate şarj numarası bulundu',
                'data' => $duplicates
            ];
        }

        // Check for orphaned records
        $orphanedBarcodes = self::whereDoesntHave('stock')
            ->orWhereDoesntHave('kiln')
            ->orWhereDoesntHave('quantity')
            ->count();

        if ($orphanedBarcodes > 0) {
            $issues[] = [
                'type' => 'orphaned_barcodes',
                'message' => "{$orphanedBarcodes} adet orphaned barkod bulundu",
                'count' => $orphanedBarcodes
            ];
        }

        // Check for invalid status transitions
        $invalidTransitions = self::where('status', '>', 7)->count();
        if ($invalidTransitions > 0) {
            $issues[] = [
                'type' => 'invalid_status',
                'message' => "{$invalidTransitions} adet geçersiz durum bulundu",
                'count' => $invalidTransitions
            ];
        }

        return $issues;
    }
}
