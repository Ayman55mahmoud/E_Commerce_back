<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $repo
    ) {}

    public function pay($data)
    {
        $payment = $this->repo->create($data);

        Log::info('Payment completed', [
            'payment_id' => $payment->id
        ]);

        return $payment;
    }
}