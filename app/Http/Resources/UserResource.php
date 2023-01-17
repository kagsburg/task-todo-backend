<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'Fullname' => $this->user,
            'Email' => $this->Email,
            'Address'=>$this->Address,
            'roleId' => $this->role_id,
             'role_id' => $this->role_id ? Role::find($this->role_id)->role_name : '',
            'Id Number'=>$this->NIN
        ];
    }
    public function with($request)
    {
        return ['status' => 'success'];
    }
}
