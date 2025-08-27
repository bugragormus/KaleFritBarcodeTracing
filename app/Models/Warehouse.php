<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address'
    ];

    /**
     * Get the barcodes for this warehouse.
     */
    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
