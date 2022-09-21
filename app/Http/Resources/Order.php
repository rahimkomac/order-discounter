<?php


namespace App\Http\Resources;


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'id'         => $this->id,
            'customerId' => $this->customer->id,
            'items'      => collect($this->items->toArray())->map(function ($item) {
                return collect($item)
                    ->only(['product_id', 'quantity', 'unit_price', 'total'])
                    ->all();
            }),
            'total'      => $this->total,
        ];
    }
}
