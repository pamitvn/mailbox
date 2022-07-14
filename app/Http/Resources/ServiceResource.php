<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Service */
class ServiceResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'feature_image' => $this->feature_image,
            'lifetime' => $this->lifetime,
            'price' => $this->price,
            'pop3' => $this->pop3,
            'imap' => $this->imap,
            'visible' => $this->visible,
            'is_local' => $this->is_local,
            'extras' => $this->extras,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'in_stock_count' => $this->in_stock_count
        ];
    }
}
