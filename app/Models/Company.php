<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'address'
    ];

    /**
     * Get the barcodes for this company.
     */
    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
