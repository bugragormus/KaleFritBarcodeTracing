<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarcodeHistory extends Model
{
    use HasFactory, SoftDeletes;

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
