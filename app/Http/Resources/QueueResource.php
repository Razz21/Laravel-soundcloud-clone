<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QueueResource extends JsonResource
{
    /**
     * Return the resource array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user" => UserResource::make($this->user)->only(['url', 'screen_name']),
            "thumbnail" => $this->thumbnail,
            "slug" => $this->slug,
            "url" => $this->url,
            'title' => $this->title,
            "audio_length" => $this->audio_length,
            'isLiked' => $this->is_liked,
        ];
    }
}
