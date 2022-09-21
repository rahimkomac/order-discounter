<?php


namespace App\Rules\Discount;


use App\Models\Order;

class OrderCategoryCheckProductCheapestPercentageDiscounter implements Discounter
{
    public function calculate(Order $order, $options = []) {

        $filters = [];
        foreach ($order->items as $item) {
            if($options['categoryId'] == $item->product->category_id) {
                $filters[] = $item->toArray();
            }
        }

        if(empty($filters)) {
            return false;
        }

        if(collect($filters)->sum('quantity') < $options['productCount']) {
            return false;
        }

        $minPrice = collect($filters)->min('unit_price');
        $calculated =  $minPrice * $options['discountPercentage'] / 100;
        return [
            "discountReason" => "BUY_{$options['productCount']}_PRODUCT_CHEAPEST_{$options['discountPercentage']}_PERCENT",
            "discountAmount" => $calculated,
            "subtotal" =>  $order->total - $calculated
        ];

    }
}
