<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Goods;
use App\Models\UserAddress;
use App\Policies\CartPolicy;
use App\Policies\CollectionPolicy;
use App\Policies\CountryPolicy;
use App\Policies\GoodsPolicy;
use App\Policies\UserAddressPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserAddress::class  => UserAddressPolicy::class,
        Goods::class        =>  GoodsPolicy::class,
        Cart::class         =>  CartPolicy::class,
        Collection::class   =>  CollectionPolicy::class,
        Country::Class      =>  CountryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
