<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Request $request, WishlistService $service)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        return response()->json([
            'status' => $service->toggle(
                auth()->user(),
                $request->product_id
            )
        ]);
    }

    public function index(WishlistService $service)
    {
        return response()->json(
            $service->list(auth()->user())
        );
    }
}