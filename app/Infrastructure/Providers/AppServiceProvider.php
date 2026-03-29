<?php

namespace App\Infrastructure\Providers;

use App\Application\Security\PasswordHasherInterface;
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
use App\Infrastructure\Auth\LaravelPasswordHasher;
use App\Infrastructure\Logging\MDCContext;
use App\Infrastructure\Logging\LoggerInterface;
use App\Infrastructure\Logging\Logger;
use App\Infrastructure\Persistence\Repositories\EloquentAdminRepository;
use App\Infrastructure\Persistence\Repositories\EloquentHotelRepository;
use App\Infrastructure\Persistence\Repositories\EloquentItemRepository;
use App\Infrastructure\Persistence\Repositories\EloquentOperativeRepository;
use App\Infrastructure\Persistence\Repositories\EloquentOrderAssignmentRepository;
use App\Infrastructure\Persistence\Repositories\EloquentOrderItemRepository;
use App\Infrastructure\Persistence\Repositories\EloquentOrderRepository;
use App\Infrastructure\Persistence\Repositories\EloquentRoleRepository;
use App\Infrastructure\Persistence\Repositories\EloquentUserRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register MDCContext as singleton
        $this->app->singleton(MDCContext::class, function () {
            return MDCContext::getInstance();
        });

        // Register Logger interface
        $this->app->singleton(LoggerInterface::class, Logger::class);

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
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
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
