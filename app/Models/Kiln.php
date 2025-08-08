<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kiln extends Model
{
    use HasFactory, SoftDeletes;

    public const KILN_DF = 1;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'load_number'
    ];

    /**
     * Get the barcodes for this kiln.
     */
    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
