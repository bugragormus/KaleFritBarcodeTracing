<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GranilyaProduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_id',
        'load_number',
        'size_id',
        'crusher_id',
        'quantity_id',
        'custom_quantity',
        'used_quantity',
        'company_id',
        'pallet_number',
        'is_sieve_residue',
        'sieve_residue_quantity',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function size()
    {
        return $this->belongsTo(GranilyaSize::class, 'size_id');
    }

    public function crusher()
    {
        return $this->belongsTo(GranilyaCrusher::class, 'crusher_id');
    }

    public function quantity()
    {
        return $this->belongsTo(GranilyaQuantity::class, 'quantity_id');
    }

    public function company()
    {
        return $this->belongsTo(GranilyaCompany::class, 'company_id');
    }
}
