<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    use DynamicFieldsTrait;

    protected static function getCollectionClass()
    {
        return 'App\Http\Resources\TrackResourceCollection';
    }

    /**
     * Return the resource array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArrayFilter($request)
    {
        return [
            "id" => $this->id,
            "user" => UserResource::make($this->user)->hide(['email', 'created_at', "username"]),
            "cover" => $this->cover,
            "slug" => $this->slug,
            "url" => $this->url,
            'title' => $this->title,
            "audio_length" => $this->audio_length,
            'genre' => new GenreResource($this->genre),
            'description' => $this->whenLoaded('description'),
            "created_at" => $this->created_at,
            'tags' => $this->whenLoaded('tags', function () {
                return TagResource::collection($this->tags);
            }, []),
            'wave' => $this->wave,
            'isLiked' => $this->is_liked,
            'likes' => [
                "total" => $this->likes()->count(),
            ],
            "plays" => $this->listeners->count()
        ];
    }
}
