<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketChatResource extends JsonResource
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
          'info'=>$this->info,
          'ip'=>$this->ip,
          'latitude'=>$this->latitude,
          'longitude'=>$this->longitude,
          'owner'=>ClientServiceTicketInfoResource::make($this->whenLoaded('owner')),
          'service'=>ServiceResource::make($this->whenLoaded('service')),
          'chat'=>ChatResource::collection($this->whenLoaded('chat')),
          'created_at'=>$this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
