<?php

namespace App\Services;

use App\Models\Barcode;
use App\Models\Permission;
use App\Models\User;

class BarcodeStatusManager
{
    /**
     * Tüm statülerin listesini getirir.
     *
     * @return array
     */
    public function getAllStatuses(): array
    {
        return Barcode::STATUSES;
    }

    /**
     * Verilen statü kodunun ismini güvenli bir şekilde döner.
     *
     * @param int|null $status
     * @return string
     */
    public function getStatusName(?int $status): string
    {
        if ($status === null) {
            return 'Belirtilmedi';
        }

        return Barcode::STATUSES[$status] ?? "Bilinmiyor ({$status})";
    }

    /**
     * Kullanıcının yetkilerine göre seçebileceği statüleri getirir.
     *
     * @param User $user
     * @return array
     */
    public function getAvailableStatusesForUser(User $user): array
    {
        $permissions = $user->permissions;
        $statuses = Barcode::STATUSES;

        // Laboratuvar işlemleri yetkisine sahip değilse
        if ($permissions->where('id', Permission::LAB_PROCESSES)->isEmpty()) {
            unset(
                $statuses[Barcode::STATUS_CONTROL_REPEAT],
                $statuses[Barcode::STATUS_PRE_APPROVED],
                $statuses[Barcode::STATUS_REJECTED]
            );
        }

        // Yönetim işlemleri yetkisine sahip değilse
        if ($permissions->where('id', Permission::MANAGEMENT)->isEmpty()) {
            unset($statuses[Barcode::STATUS_SHIPMENT_APPROVED]);
        }

        // Müşteri transfer işlemleri yetkisine sahip değilse
        if ($permissions->where('id', Permission::CUSTOMER_TRANSFER)->isEmpty()) {
            unset(
                $statuses[Barcode::STATUS_CUSTOMER_TRANSFER],
                $statuses[Barcode::STATUS_DELIVERED]
            );
        }

        // Birleştirildi ve Düzeltme Faaliyetinde Kullanıldı durumları manuel seçilemez
        unset(
            $statuses[Barcode::STATUS_MERGED],
            $statuses[Barcode::STATUS_CORRECTED]
        );

        return $statuses;
    }

    /**
     * Belirli bir barkodun yeni bir statüye geçip geçemeyeceğini kontrol eder.
     *
     * @param Barcode $barcode
     * @param int $newStatus
     * @return bool
     */
    public function canTransition(Barcode $barcode, int $newStatus): bool
    {
        $currentStatus = (int) $barcode->status;

        // Aynı duruma geçiş her zaman mümkün
        if ($currentStatus === $newStatus) {
            return true;
        }

        // Birleştirilmiş barkodun durumu değiştirilemez
        if ($currentStatus === Barcode::STATUS_MERGED) {
            return false;
        }

        // State machine logic
        switch ($currentStatus) {
            case Barcode::STATUS_WAITING:
                return in_array($newStatus, [
                    Barcode::STATUS_CONTROL_REPEAT,
                    Barcode::STATUS_PRE_APPROVED,
                    Barcode::STATUS_REJECTED,
                    Barcode::STATUS_SHIPMENT_APPROVED,
                    Barcode::STATUS_MERGED
                ]);

            case Barcode::STATUS_CONTROL_REPEAT:
                return in_array($newStatus, [
                    Barcode::STATUS_PRE_APPROVED,
                    Barcode::STATUS_REJECTED,
                    Barcode::STATUS_SHIPMENT_APPROVED
                ]);

            case Barcode::STATUS_PRE_APPROVED:
                return in_array($newStatus, [
                    Barcode::STATUS_SHIPMENT_APPROVED,
                    Barcode::STATUS_REJECTED
                ]);

            case Barcode::STATUS_SHIPMENT_APPROVED:
                return in_array($newStatus, [
                    Barcode::STATUS_CUSTOMER_TRANSFER,
                    Barcode::STATUS_DELIVERED
                ]);

            case Barcode::STATUS_REJECTED:
                return in_array($newStatus, [
                    Barcode::STATUS_CORRECTED,
                    Barcode::STATUS_CUSTOMER_TRANSFER,
                    Barcode::STATUS_DELIVERED
                ]);

            case Barcode::STATUS_CUSTOMER_TRANSFER:
                return in_array($newStatus, [
                    Barcode::STATUS_DELIVERED
                ]);

            case Barcode::STATUS_DELIVERED:
                return in_array($newStatus, [
                    Barcode::STATUS_PRE_APPROVED
                ]);

            default:
                return false;
        }
    }

    /**
     * Laboratuvar yetkisi olan kullanıcının göreceği statüleri döner.
     *
     * @return array
     */
    public function getLabStatuses(): array
    {
        return Barcode::LAB_STATUSES;
    }

    /**
     * Yönetim/İş akışı statülerini döner.
     *
     * @return array
     */
    public function getWorkflowStatuses(): array
    {
        return Barcode::WORKFLOW_STATUSES;
    }
}
