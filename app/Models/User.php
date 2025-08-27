<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'registration_number',
        'widget_settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'widget_settings' => 'array'
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting a user, handle related records
        static::deleting(function ($user) {
            // Update barcodes where this user is referenced
            \App\Models\Barcode::where('created_by', $user->id)->update(['created_by' => null]);
            \App\Models\Barcode::where('lab_by', $user->id)->update(['lab_by' => null]);
            \App\Models\Barcode::where('warehouse_transferred_by', $user->id)->update(['warehouse_transferred_by' => null]);
            \App\Models\Barcode::where('delivered_by', $user->id)->update(['delivered_by' => null]);

            // Update barcode histories where this user is referenced
            \App\Models\BarcodeHistory::where('user_id', $user->id)->update(['user_id' => null]);

            // Detach permissions (this will remove records from user_permissions table)
            $user->permissions()->detach();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * @param $permissionId
     * @return bool
     */
    public function hasPermission($permissionId): bool
    {
        return $this->permissions()
            ->where('id', $permissionId)->exists();
    }

    /**
     * Oluşturulan barkodlar
     */
    public function barcodesCreated()
    {
        return $this->hasMany(Barcode::class, 'created_by');
    }

    /**
     * İşlenen barkodlar (lab personeli)
     */
    public function barcodesProcessed()
    {
        return $this->hasMany(Barcode::class, 'lab_by');
    }

    /**
     * Widget ayarlarını al
     */
    public function getWidgetSettings()
    {
        return $this->widget_settings ?? [
            'disabled' => [],
            'order' => [
                'production_trend',
                'quality_metrics',
                'recent_activities',
                'stock_alerts',
                'user_performance',
                'system_health',
                'weather_widget',
                'quick_actions'
            ]
        ];
    }

    /**
     * Widget ayarlarını kaydet
     */
    public function saveWidgetSettings($settings)
    {
        $this->widget_settings = $settings;
        return $this->save();
    }
}
