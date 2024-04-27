<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenNotNull(new UserResource($this->documentable)),
            'name' => $this->name,
            'type' => $this->type,
            'file' => $this->file,
            'type_data' =>$this->whenNotNull(json_decode($this->type_data)) ,
            'encryption_data' =>$this->whenNotNull(json_decode($this->encryption_data)),
        ];
    }
}
