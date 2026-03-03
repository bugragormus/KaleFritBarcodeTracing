<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GranilyaQuantity extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['quantity', 'is_active'];
}
