<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewUserRegisterEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('user-registrations');
    }

    public function broadcastAs()
    {
        return 'new-user.registered';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'New user registered: ' . $this->user->name,
            "type" => "user"
        ];
    }
}
