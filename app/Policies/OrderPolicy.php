<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * @param Customer $customer
     * @param Order $order
     * @return bool
     */
    public function owner(Customer $customer, Order $order)
    {
        return $order->customer->id == $customer->id;
    }
}
