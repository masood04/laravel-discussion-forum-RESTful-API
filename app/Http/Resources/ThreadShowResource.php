<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadShowResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'owner_name' => $this->user->email,
            'created_at' => $this->created_at,
            'replies' => $this->replies_count,
            'solve' => $this->solve,
            'slug' => $this->slug,
            'answers' => AnswerResource::collection($this->answers)
        ];
    }
}
