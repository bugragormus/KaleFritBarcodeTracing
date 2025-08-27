<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// SoftDeletes kaldırıldı - hard delete için

class BarcodeHistory extends Model
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'barcode_id',
        'status',
        'user_id',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function barcode(): BelongsTo
    {
        return $this->belongsTo(Barcode::class)->withDefault();
    }
}