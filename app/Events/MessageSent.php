<?php

namespace App\Events;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var User
     */
    //public $user;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
       // $this->user = $user;
        $this->message = $message;
    }



    public function broadcastOn()
    {
      return new Channel('chat.'. $this->message->user->uuid.'-'. $this->message->sent_to_id);
    }

    public function broadcastAs()
        {
            return 'ChatEvent';
        }

        public function broadcastWith()
        {
            return [
                'data' => [
                    $this->message
                ],
            ];
        }
}
