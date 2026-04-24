<?php

namespace App\Policies;

use App\Models\BarangKeluar;
use App\Models\User;

class BarangKeluarPolicy
{
    public function update(User $user, BarangKeluar $barangKeluar): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, BarangKeluar $barangKeluar): bool
    {
        return $user->isOwner();
    }

    public function requestVoid(User $user, BarangKeluar $barangKeluar): bool
    {
        if ($user->isOwner()) {
            return false;
        }

        return $user->isKaryawan() && $barangKeluar->void_status !== 'pending';
    }

    public function approveVoid(User $user, BarangKeluar $barangKeluar): bool
    {
        return $user->isOwner() && $barangKeluar->void_status === 'pending';
    }
}
