<?php

namespace App\Policies;

use App\Models\Country;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Jcove\Admin\Models\AdminUser;

class CountryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        //
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(AdminUser $user)
    {
        //
    }

    /**
     * Determine whether the user can update the country.
     *
     * @param  \App\User  $user

     * @return mixed
     */
    public function update(AdminUser $user, Country $country)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the country.
     *
     * @param  \App\User  $user

     * @return mixed
     */
    public function delete(AdminUser $user, Country $country)
    {
        return true;
    }
}
