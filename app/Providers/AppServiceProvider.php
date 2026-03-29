<?php

namespace App\Providers;

use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\HotelRepositoryInterface;
use App\Domain\Repositories\ItemRepositoryInterface;
use App\Domain\Repositories\OperativeRepositoryInterface;
use App\Domain\Repositories\OrderAssignmentRepositoryInterface;
use App\Domain\Repositories\OrderItemRepositoryInterface;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\SanctumAuthProvider;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;
use App\Infrastructure\Repositories\EloquentAdminRepository;
use App\Infrastructure\Repositories\EloquentHotelRepository;
use App\Infrastructure\Repositories\EloquentItemRepository;
use App\Infrastructure\Repositories\EloquentOperativeRepository;
use App\Infrastructure\Repositories\EloquentOrderAssignmentRepository;
use App\Infrastructure\Repositories\EloquentOrderItemRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, EloquentAdminRepository::class);
        $this->app->bind(OperativeRepositoryInterface::class, EloquentOperativeRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, EloquentHotelRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, EloquentItemRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, EloquentOrderItemRepository::class);
        $this->app->bind(OrderAssignmentRepositoryInterface::class, EloquentOrderAssignmentRepository::class);
        $this->app->bind(SanctumAuthProviderInterface::class, SanctumAuthProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
