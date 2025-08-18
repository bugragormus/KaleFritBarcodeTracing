<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get barcodes that have this rejection reason
     */
    public function barcodes(): BelongsToMany
    {
        return $this->belongsToMany(Barcode::class, 'barcode_rejection_reason')
                    ->withTimestamps();
    }

    /**
     * Scope for active reasons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all rejection reasons as options for forms
     */
    public static function getOptions()
    {
        return static::active()->pluck('name', 'id')->toArray();
    }
}
