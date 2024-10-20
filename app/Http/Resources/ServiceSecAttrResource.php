<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSecAttrResource extends JsonResource
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
          'service_id'=>$this->service_id,
          'section_id'=>$this->section_id ?? null,
          'attribute_id'=>$this->attribute_id,
          'type'=>$this->type ?? null,
          'service'=>ServiceResource::make($this->whenLoaded('service')),
          'section'=>SectionResource::make($this->whenLoaded('section')),
          'attribute'=>AttributeResource::make($this->whenLoaded('attribute')),
        ];
    }
}
