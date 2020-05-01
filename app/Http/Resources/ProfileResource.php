<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            "description" => $this->description,
            "city" => $this->city,
            "country" => $this->country,
        ];
    }
}
