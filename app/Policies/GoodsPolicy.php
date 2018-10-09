<?php

namespace App\Policies;

use App\User;
use App\Models\Goods;
use Illuminate\Auth\Access\HandlesAuthorization;
use Jcove\Admin\Models\AdminUser;

class GoodsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the goods.
     *
     * @param  \App\User  $user
     * @param  \App\Goods  $goods
     * @return mixed
     */
    public function view(User $user, Goods $goods)
    {
        //
    }

    /**
     * Determine whether the user can create goods.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(AdminUser $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the goods.
     *
     * @param  \App\User  $user
     * @param  \App\Goods  $goods
     * @return mixed
     */
    public function update(AdminUser $user, Goods $goods)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the goods.
     *
     * @param  \App\User  $user
     * @param  \App\Goods  $goods
     * @return mixed
     */
    public function delete(AdminUser $user, Goods $goods)
    {
        return true;
    }
}
