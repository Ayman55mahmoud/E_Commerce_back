<?php

namespace App\Services;

use App\Repositories\CouponRepository;

class CouponService
{
    public function __construct(
        protected CouponRepository $repo
    ) {}

    public function apply($code, $total)
    {
        $coupon = $this->repo->findValidCoupon($code);

        if (!$coupon) {
            abort(400, 'Invalid coupon');
        }

        // usage limit
        if (
            $coupon->usage_limit &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            abort(400, 'Coupon usage limit reached');
        }

        $discount = 0;

        // مبلغ ثابت
        if ($coupon->type === 'fixed') {

            $discount = $coupon->value;

        } else {

            // نسبة %
            $discount = ($total * $coupon->value) / 100;
        }

        $finalTotal = max(0, $total - $discount);

        return [

            'coupon' => $coupon,

            'discount' => $discount,

            'final_total' => $finalTotal
        ];
    }
}