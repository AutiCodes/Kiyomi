<?php

namespace Modules\Members\Listeners;

use Modules\Members\Events\NewMember;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateWPAccount
{
    /**
     * Create the event listener.
     *
     * @author AutiCodes
     * @param Collection $member
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @author AutiCodes
     * @param NewMember $event
     * @return void
     */
    public function handle(NewMember $event): void
    {
        //
    }
}
