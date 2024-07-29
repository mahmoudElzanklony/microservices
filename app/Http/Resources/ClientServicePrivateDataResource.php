<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientServicePrivateDataResource extends JsonResource
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
          'ip'=>$this->ip,
          'latitude'=>$this->latitude,
          'longitude'=>$this->longitude,
          'info'=>$this->info,
          'answers'=>ClientServiceAnswersDataResource::collection($this->whenLoaded('answers')),
          'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
