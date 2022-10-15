<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\UserOnline;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;



class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {

      $loginuser = User::where('id', $event->user->id)->with('lastmessage')->first();

       event(new UserOnline($loginuser));
    }
}
