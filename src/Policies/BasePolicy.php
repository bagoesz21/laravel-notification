<?php

namespace Bagoesz21\LaravelNotification\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /** @var string */
    protected $roleGroup = '';

    /** @var string */
    protected $model;

    /**
     * Check access user
     *
     * @param  string  $ability
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    protected function checkAccess($ability, User $user, $model = null)
    {
        return $user->can("$ability $this->roleGroup");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, $model = null)
    {
        return $this->checkAccess('view', $user, $model);
    }

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->view($user);
    }

    /**
     * Determine whether the user can entry (Create/Add, Update/Edit) models.
     *
     * @param  \App\Models\User|null  $model
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function entry(User $user, $model = null)
    {
        return $this->checkAccess('entry', $user, $model);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->entry($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User|null  $model
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, $model = null)
    {
        return $this->entry($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User|null  $model
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, $model = null)
    {
        return $this->checkAccess('delete', $user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, $model = null)
    {
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, $model = null)
    {
        return $this->delete($user, $model);
    }
}
