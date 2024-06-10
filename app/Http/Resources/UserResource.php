<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'link_facebook'  => $this->link_facebook,
            'link_instagram' => $this->link_instagram,
            'link_x'         => $this->link_x,
            'info'           => $this->info,
            'role'           => $this->role,
            'image'          => $this->image,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
