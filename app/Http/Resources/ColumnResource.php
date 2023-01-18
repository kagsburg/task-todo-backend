<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
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
            'id'=> $this->id,
            'title' => $this->title,
            'cards' => CardResource::collection($this->cards->where('is_deleted', '0')),
           
        ];
    }
    public function with($request)
    {
        return ['status' => 'success'];
    }
}
