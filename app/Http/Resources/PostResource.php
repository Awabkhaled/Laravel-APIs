<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'cover_image' => $this->cover_image,
            'pinned' => $this->pinned,
            'tags' => $this->tags->map(function ($tag) {
                                        return [
                                            'id' => $tag->id,
                                            'name' => $tag->name,
                                        ];
                                    }),
        ];
    }
}
