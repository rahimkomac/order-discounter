<?php


namespace App\Rules\Discount;


use App\Models\Order;

class OrderCategoryCheckProductDiscounter implements Discounter
{
    public function calculate(Order $order, $options = []) {

        $productPerPrice = 0;
        foreach ($order->items as $item) {
            if(
                $options['categoryId'] == $item->product->category_id &&
                $item->quantity >= $options['productCount']
            ) {
                $productPerPrice = $item->unit_price;
            }
        }

        if(!$productPerPrice) {
            return false;
        }

        $calculated = $productPerPrice * $options['freeCount'];

        return [
            "discountReason" => "BUY_{$options['productCount']}_GET_{$options['freeCount']}",
            "discountAmount" => $calculated,
            "subtotal" =>  $order->total - $calculated
        ];

    }
}
