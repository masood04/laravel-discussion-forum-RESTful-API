<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadIndexResurece extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'users' => $this->user->name ,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'replies' => $this->replies_count,
            'slug' => $this->slug,
        ];
    }
}
