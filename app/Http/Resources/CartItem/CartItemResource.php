<?php

namespace App\Http\Resources\CartItem;

use App\Http\Resources\Sticker\StickerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product->id,
            'title' => $this->product->title,
            'image' => $this->preview_image,
            'description' => $this->product->description,
            'price' => $this->price,
            'count' => $this->count,
            'sticker' => new StickerResource($this->product->sticker),
        ];
    }
}
