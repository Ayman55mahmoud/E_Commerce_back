<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // هنا هتعمل إرسال الإيميل الحقيقي

        Log::info('Sending Order Email', [
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'total' => $this->order->total_price,
        ]);

        // مثال لاحقًا:
        // Mail::to($this->order->user->email)->send(new OrderMail($this->order));
    }
}
