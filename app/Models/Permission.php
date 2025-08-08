<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    // Yeni yetki sabitleri
    public const BARCODE_CREATE = 1;      // Barkod oluşturma izni
    public const LAB_PROCESSES = 2;       // Lab işlemleri
    public const CUSTOMER_TRANSFER = 3;   // Müşteri transfer izni
    public const USER_LIST = 4;           // Kullanıcı listeleme
    public const USER_MANAGE = 5;         // Kullanıcı silme/düzenleme
    public const MANAGEMENT = 6;          // Depo fırın adet firma yönetim izni

    public $fillable = [
      'name',
      'order'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}
