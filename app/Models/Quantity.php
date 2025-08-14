<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quantity extends Model
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'quantity'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get barcodes associated with this quantity
     */
    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }

    /**
     * Ensure quantity never goes below 0
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = max(0, (int) $value);
    }
}
