<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\SendOrderToAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        // مهم جدًا: نضمن إن العلاقات متحمّلة
        $order = $this->order->load('user');

        Mail::to(env('ADMIN_EMAIL', 'admin@gmail.com'))
            ->send(new SendOrderToAdmin($order));
    }
}