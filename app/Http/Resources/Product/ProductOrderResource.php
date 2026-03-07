<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Sticker\StickerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->preview_image,
            'price' => $this->pivot->price,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
