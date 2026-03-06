<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GranilyaCrusher extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'code', 'is_active'];

    public function granilyaProductions()
    {
        return $this->hasMany(GranilyaProduction::class, 'crusher_id');
    }
}
