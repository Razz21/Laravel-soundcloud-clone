<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "email" => $this->email,
            "username" => $this->username,
            "created_at" => $this->created_at,
            "screen_name" => $this->screen_name,
            "url" => $this->url,
            "thumbnail" => $this->thumbnail(),
        ];
    }
}
