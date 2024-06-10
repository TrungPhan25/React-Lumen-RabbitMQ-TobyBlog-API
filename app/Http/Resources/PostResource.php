<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->title,
            'meta_title'  => $this->meta_title,
            'slug'        => $this->slug,
            'category_id' => $this->category_id,
            'summary'     => $this->summary,
            'content'     => $this->content,
            'image'       => $this->image,
            'updated_at'  => $this->updated_at,
        ];
    }
}
