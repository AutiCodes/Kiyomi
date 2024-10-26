<?php

namespace Modules\Members\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Members\Events\NewMember;
use Modules\Members\Listeners\CreateWPAccount;
use Modules\Members\Listeners\SendNewMemberEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        NewMember::class => [
            SendNewMemberEmail::class,
            CreateWPAccount::class,
        ]
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;
}
