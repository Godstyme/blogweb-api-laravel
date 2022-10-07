<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->blog_posts_title,
            'post'=>$this->blog_posts_desc,
            'user'=>new UserResource($this->whenLoaded('user')),
            'comment' => CommentResource::collection($this->whenLoaded('postComment')),
            'comment_count' => $this->whenCounted('postComment')
        ];
    }
}
