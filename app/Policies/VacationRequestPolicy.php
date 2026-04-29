<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VacationRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacationRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VacationRequest');
    }

    public function view(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('View:VacationRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VacationRequest');
    }

    public function update(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('Update:VacationRequest');
    }

    public function delete(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('Delete:VacationRequest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VacationRequest');
    }

    public function restore(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('Restore:VacationRequest');
    }

    public function forceDelete(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('ForceDelete:VacationRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VacationRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VacationRequest');
    }

    public function replicate(AuthUser $authUser, VacationRequest $vacationRequest): bool
    {
        return $authUser->can('Replicate:VacationRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VacationRequest');
    }

}