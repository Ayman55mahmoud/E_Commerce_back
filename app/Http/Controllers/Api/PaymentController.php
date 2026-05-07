<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $service
    ) {}

    public function store(StorePaymentRequest $request)
    {
        $payment = $this->service->pay(
            $request->validated()
        );

        return new PaymentResource($payment);
    }
}
