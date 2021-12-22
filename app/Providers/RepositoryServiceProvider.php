<?php

namespace App\Providers;

use App\Models\Balance;
use App\Models\Car;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Balance\IBalanceRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Car\CarRepository;
use App\Repositories\Car\ICarRepository;
use App\Repositories\IRepository;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Service\IServiceRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IRepository::class, BaseRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ICarRepository::class, CarRepository::class);
        $this->app->bind(IServiceRepository::class, ServiceRepository::class);
        $this->app->bind(IBalanceRepository::class, BalanceRepository::class);

        $this->app->singleton(OrderRepository::class, function () {
            return new OrderRepository(new Order());
        });

        $this->app->singleton(UserRepository::class, function () {
            return new UserRepository(new User());
        });

        $this->app->singleton(CarRepository::class, function () {
            return new CarRepository(new Car());
        });

        $this->app->singleton(ServiceRepository::class, function () {
            return new ServiceRepository(new Service());
        });

        $this->app->singleton(BalanceRepository::class, function () {
            return new BalanceRepository(new Balance());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
