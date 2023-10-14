<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Repository extends JsonResource
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
            'id' => $this['id'],
            'name' => $this['name'],
            'full_name' => $this['full_name'],
            'owner' => [
                "id" => $this['owner']['id'],
                "login" => $this['owner']['login'],
                "url" => $this['owner']['url'],
            ],
            'html_url' => $this['html_url'],
            'url' => $this['url'],
            'description' => $this['description'],
            'clone_url' => $this['clone_url'],
        ];
    }
}
