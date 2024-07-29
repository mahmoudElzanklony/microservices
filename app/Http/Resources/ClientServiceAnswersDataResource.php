<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientServiceAnswersDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id'=>$this->id,
          'attribute_id'=>$this->attribute_id,
          'section_id'=>$this->section_id,
          'attribute'=>AttributeResource::make($this->whenLoaded('attribute')),
          'answer'=>$this->answer,
          'answer_type'=>$this->answer_type,
          'created_at'=>$this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
