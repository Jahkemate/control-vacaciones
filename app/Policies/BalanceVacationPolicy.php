<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BalanceVacation;
use Illuminate\Auth\Access\HandlesAuthorization;

class BalanceVacationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BalanceVacation');
    }

    public function view(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('View:BalanceVacation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BalanceVacation');
    }

    public function update(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('Update:BalanceVacation');
    }

    public function delete(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('Delete:BalanceVacation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BalanceVacation');
    }

    public function restore(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('Restore:BalanceVacation');
    }

    public function forceDelete(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('ForceDelete:BalanceVacation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BalanceVacation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BalanceVacation');
    }

    public function replicate(AuthUser $authUser, BalanceVacation $balanceVacation): bool
    {
        return $authUser->can('Replicate:BalanceVacation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BalanceVacation');
    }

}