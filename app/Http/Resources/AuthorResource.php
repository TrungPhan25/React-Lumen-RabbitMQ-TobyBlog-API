<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'name'           => $this->name,
            'info'           => $this->info,
            'link_facebook'  => $this->link_facebook,
            'link_x'         => $this->link_x,
            'link_instagram' => $this->link_instagram,
            'image'          => $this->image,
        ];
    }
}
