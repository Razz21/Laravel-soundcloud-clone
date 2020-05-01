<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $subscribed = $this->subscribed()->paginate(1);
        // $subscribers = $this->subscribers()->paginate(5);
        return [
            'id' => $this->id,
            'screen_name' => $this->screen_name,
            'is_subscribed' => $this->is_subscribed,
            // 'is_subscribed' => $this->when(auth()->check(), function () {
            //     return (bool) $this->subscribedBy(auth()->user()->id);
            // }, false),
            'url' => $this->url,
            'avatar' => $this->avatar,
            'profile' => new ProfileResource($this->profile),
            'subscribed' => new SubscriptionSampleCollection($this->subscribed),
            'subscribers' => new SubscriptionSampleCollection($this->subscribers),
            // 'subscribers2' => (new SubscriptionsCollection($subscribers))->response()->getData(true),
            'tracks' => [
                "total" => (int) $this->tracks()->count(),
            ],
        ];
    }
}
