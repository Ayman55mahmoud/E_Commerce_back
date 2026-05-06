<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'items' => $this->items->map(function ($item) {
                return [
                    'product_id' => $item->product->id,
                    'name'       => $item->product->name,
                    'price'      => $item->product->price,
                    'quantity'   => $item->quantity,
                    'total'      => $item->quantity * $item->product->price,
                ];
            }),

            'total_price' => $this->items->sum(
                fn($i) => $i->quantity * $i->product->price
            ),
        ];
    }
}
