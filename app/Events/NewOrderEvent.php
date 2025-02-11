<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NewOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('new-order');
    }

    public function broadcastAs()
    {
        return 'new-order.created';
    }

    public function broadcastWith()
    {
        return [
            'message' => '<p> Conguration You have New Order: ' . $this->order->order_number . "</p><br> 
                <p> Total Price is : " . $this->order->total_price  . "</p>".
                " <p>View Detail In <a href=/admin/orders/".$this->order->id."> Here </p> <a> " .
                "<hr>",
            'type' => 'order'
        ];
    }
}
