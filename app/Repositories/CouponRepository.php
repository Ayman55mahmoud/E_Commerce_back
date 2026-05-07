<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    public function findValidCoupon($code)
    {
        return Coupon::where('code', $code)

            ->where('is_active', true)

            ->where(function ($q) {

                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());

            })

            ->first();
    }

    public function incrementUsage($coupon)
    {
        $coupon->increment('used_count');
    }
}