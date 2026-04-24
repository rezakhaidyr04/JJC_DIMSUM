<?php

namespace App\Policies;

use App\Models\BarangMasuk;
use App\Models\User;

class BarangMasukPolicy
{
    public function update(User $user, BarangMasuk $barangMasuk): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, BarangMasuk $barangMasuk): bool
    {
        return $user->isOwner();
    }

    public function requestVoid(User $user, BarangMasuk $barangMasuk): bool
    {
        if ($user->isOwner()) {
            return false;
        }

        return $user->isKaryawan() && $barangMasuk->void_status !== 'pending';
    }

    public function approveVoid(User $user, BarangMasuk $barangMasuk): bool
    {
        return $user->isOwner() && $barangMasuk->void_status === 'pending';
    }
}
