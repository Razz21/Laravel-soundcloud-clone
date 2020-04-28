<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPreviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "screen_name" => $this->screen_name,
            "thumbnail" => $this->thumbnail,
            'is_subscribed' => $this->when(auth()->check(), function () {
                return $this->subscribedBy(auth()->user()->id);
            }, false),
            'tracks' => [
                "total" => $this->tracks()->count(),
            ],
            'subscribers' => [
                "total" => $this->subscribers()->count(),
            ],
        ];
    }
}
