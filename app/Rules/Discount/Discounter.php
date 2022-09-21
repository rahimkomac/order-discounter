<?php


namespace App\Rules\Discount;


use App\Models\Order;

interface Discounter
{
    public function calculate(Order $order, $options = []);
}
