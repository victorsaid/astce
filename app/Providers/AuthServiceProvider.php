<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Agreements;
use App\Models\Associate;
use App\Models\Employee;
use App\Models\Meeting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\AgreementPolicy;
use App\Policies\AssociatePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\MeetingPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Associate::class => AssociatePolicy::class,
        Employee::class => EmployeePolicy::class,
        Meeting::class => MeetingPolicy::class,
        Agreements::class => AgreementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
