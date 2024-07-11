<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionAttributeResource extends JsonResource
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
          'section_id'=>$this->section_id,
          'attribute_id'=>$this->attribute_id,
          'section'=>SectionResource::make($this->whenLoaded('section')),
          'attribute'=>AttributeResource::make($this->whenLoaded('attribute')),
        ];
    }
}
