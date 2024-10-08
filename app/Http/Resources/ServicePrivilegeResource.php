<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePrivilegeResource extends JsonResource
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
          'service'=>ServiceResource::make($this->whenLoaded('service')),
          'controls'=>ControllerPrivilegeResource::collection($this->whenLoaded('controls')),
        ];
    }
}
