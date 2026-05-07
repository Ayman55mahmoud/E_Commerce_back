<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\CouponService;

class CouponController extends Controller
{
    public function apply(
        ApplyCouponRequest $request,
        CouponService $service
    ) {

        $result = $service->apply(

            $request->code,

            $request->total
        );

        return response()->json($result);
    }
}