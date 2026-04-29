<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RequestForCompensation;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestForCompensationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RequestForCompensation');
    }

    public function view(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('View:RequestForCompensation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RequestForCompensation');
    }

    public function update(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('Update:RequestForCompensation');
    }

    public function delete(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('Delete:RequestForCompensation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RequestForCompensation');
    }

    public function restore(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('Restore:RequestForCompensation');
    }

    public function forceDelete(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('ForceDelete:RequestForCompensation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RequestForCompensation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RequestForCompensation');
    }

    public function replicate(AuthUser $authUser, RequestForCompensation $requestForCompensation): bool
    {
        return $authUser->can('Replicate:RequestForCompensation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RequestForCompensation');
    }

}