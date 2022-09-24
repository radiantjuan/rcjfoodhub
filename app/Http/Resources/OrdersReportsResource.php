<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersReportsResource extends JsonResource
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
            'ordered_items' => !empty($this->ordered_items) ? json_decode($this->ordered_items) : '',
            'promo_code_setup' => !empty($this->promo_code_setup) ? json_decode($this->promo_code_setup) : ''
        ];
    }
}
