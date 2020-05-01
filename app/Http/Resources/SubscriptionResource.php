<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    use DynamicFieldsTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArrayFilter($request)
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "screen_name" => $this->screen_name,
            "thumbnail" => $this->thumbnail,
            "profile" => ProfileResource::make($this->profile)->hide(['description']),
            'is_subscribed' => $this->when(auth()->check(), function () {
                return $this->subscribedBy(auth()->user()->id);
            }, false),
            'subscribers' => [
                "meta" => [
                    "total" => $this->subscribers()->count(),
                ],
            ],
        ];
    }
}
