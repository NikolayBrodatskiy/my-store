<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Sticker\StickerResource;
use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->preview_image,
            'wished' => $this->when($this->wishedBy()->wherePivot('user_id', auth()->user()?->id)->first(), 'true'),
            'price' => $this->price,
            'rating' => $this->rating,
            'is_published' => $this->is_published,
            'category' => new CategoryResource($this->category),
            'sticker' => new StickerResource($this->sticker),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
