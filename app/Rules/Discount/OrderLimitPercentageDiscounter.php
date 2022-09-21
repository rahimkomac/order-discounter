<?php


namespace App\Rules\Discount;


use App\Models\Order;

class OrderLimitPercentageDiscounter implements Discounter
{
    public function calculate(Order $order, $options = []) {
        if($order->total < $options['limit']) {
            return false;
        }

        /**
         * TODO : burada sipariş tutarı ile ilgili yüzde kontrolü de eklenebilir.
         * indirim tutarı sipariş tutarının %A sından fazla olamaz vb.
         */

        $calculated =  $order->total * $options['discountPercentage'] / 100;
        // sipariş tutarından düşük olamaz.
        if($order->total < $calculated) {
            return false;
        }

        return [
            "discountReason" => "10_PERCENT_OVER_1000",
            "discountAmount" => $calculated,
            "subtotal" =>  $order->total - $calculated
        ];

    }
}
