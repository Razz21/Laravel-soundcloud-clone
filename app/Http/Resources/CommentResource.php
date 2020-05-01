<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            "parent_id" => $this->parent_id,
            "body" => $this->body,
            "created_at" => $this->created_at,
            "track_id" => $this->track_id,
            "user" => UserResource::make($this->user)->hide(['email', 'created_at', "username"]),
            "replies" => self::collection($this->whenLoaded('replies')),
        ];
    }
}
