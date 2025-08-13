<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kiln extends Model
{
    use HasFactory;

    public const KILN_DF = 1;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'load_number',
        'daily_production_average'
    ];

    /**
     * Get the barcodes for this kiln.
     */
    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
