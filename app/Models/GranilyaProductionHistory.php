<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GranilyaProductionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'status',
        'user_id',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function production()
    {
        return $this->belongsTo(GranilyaProduction::class, 'production_id')->withDefault();
    }
}
