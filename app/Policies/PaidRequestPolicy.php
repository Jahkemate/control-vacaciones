<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PaidRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaidRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PaidRequest');
    }

    public function view(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('View:PaidRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PaidRequest');
    }

    public function update(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('Update:PaidRequest');
    }

    public function delete(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('Delete:PaidRequest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PaidRequest');
    }

    public function restore(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('Restore:PaidRequest');
    }

    public function forceDelete(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('ForceDelete:PaidRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PaidRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PaidRequest');
    }

    public function replicate(AuthUser $authUser, PaidRequest $paidRequest): bool
    {
        return $authUser->can('Replicate:PaidRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PaidRequest');
    }

}