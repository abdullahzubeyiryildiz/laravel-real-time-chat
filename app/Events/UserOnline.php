<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
class UserOnline implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   /**
     * User that sent the message
     *
     * @var User
     */
    public $user;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function broadcastOn()
    {
        return new Channel('online-user-event');
    }

    public function broadcastAs()
    {
        return 'OnlineUserEvent';
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->user,
        ];
    }
}
