<?php

namespace App\Policies;

use App\Models\PageView;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PageViewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_page_views');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PageView $pageView): bool
    {
        return $user->can('view_page_views');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Analytics data is auto-generated, not manually created
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PageView $pageView): bool
    {
        // Analytics data should not be manually edited
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PageView $pageView): bool
    {
        return $user->can('delete_page_views');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PageView $pageView): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PageView $pageView): bool
    {
        return $user->can('delete_page_views');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_page_views');
    }
}
