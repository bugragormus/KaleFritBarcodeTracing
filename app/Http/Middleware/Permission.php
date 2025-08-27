<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Permission adlarını ID'lere çeviren mapping
     */
    protected $permissionMap = [
        'barcode_create' => 1,      // Barkod oluşturma izni
        'lab_processes' => 2,       // Lab işlemleri
        'customer_transfer' => 3,   // Müşteri transfer izni
        'user_list' => 4,           // Kullanıcı listeleme
        'user_manage' => 5,         // Kullanıcı silme/düzenleme
        'management' => 6,          // Depo fırın adet firma yönetim izni
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Giriş yapmanız gerekiyor.');
        }

        // Permission adını ID'ye çevir
        $permissionId = $this->permissionMap[$permission] ?? null;

        if (!$permissionId) {
            abort(403, 'Geçersiz yetki: ' . $permission);
        }

        // Kullanıcının bu yetkiye sahip olup olmadığını kontrol et
        if (!$user->hasPermission($permissionId)) {
            // AJAX istekleri için JSON yanıt döndür
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu işlemi yapmak için yetkiniz yok.',
                    'error' => 'permission_denied'
                ], 403);
            }
            
            // Normal istekler için 403 hatası
            abort(403, 'Bu işlemi yapmak için yetkiniz yok.');
        }

        return $next($request);
    }
}
