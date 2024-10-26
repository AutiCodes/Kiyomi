<?php

namespace Modules\Members\Listeners;

use Modules\Members\Events\NewMember;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Members\Enums\ClubStatus;
use App\Mail\NewMemberWelcomeMail;

class SendNewMemberEmail
{
    /**
     * Create the event listener.
     *
     * @author AutiCodes
     */
    public function __construct()
    {
        //
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
        if ($event->member->club_status == ClubStatus::REMOVED_MEMBER->value) {
            return;
        }

        Mail::to($event->member->email)->send(new MembersContact($event->member));
    }
}
