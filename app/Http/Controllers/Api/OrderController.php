<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Rules\Discount\Discounter;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request)
    {
        $request->user();
        $orders = Order::where('customer_id', $request->user()->id)->get();
        return \App\Http\Resources\Order::collection($orders);
    }

    /**
     * @param Order $order
     * @return \App\Http\Resources\Order
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Order $order)
    {
        $this->authorize('owner', $order);
        return new \App\Http\Resources\Order($order);
    }

    /**
     * @param StoreOrderRequest $request
     * @return \App\Http\Resources\Order
     * @throws InternalErrorException
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->customer()->associate($request->user());
        $order->save();

        $totalAmount = 0;
        foreach ($request->items as $item) {
            /** @var Product $product */
            $product = Product::where('id', $item['productId'])->first();
            /** @var OrderItem $orderItem */
            $orderItem = $order->items()->make([
                'quantity'   => $item['quantity'],
                'unit_price' => $product->price,
                'total'      => $product->price * $item['quantity']
            ]);
            $orderItem->product()->associate($product);
            $orderItem->unit_price = $product->price;
            $orderItem->save();

            $totalAmount += $orderItem->total;
            if ($product->decreaseStock($item['quantity']) <= 0) {
                throw new InternalErrorException('Not enough Stock');
            }

            $order->update(['total' => $totalAmount]);
        }

        return new \App\Http\Resources\Order($order);
    }


    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Order $order)
    {
        $this->authorize('owner', $order);
        $order->delete();
        return response()->json([
            'status' => true,
            'message' => 'Order deleted.'
        ], 201);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function discount(Order $order)
    {
        $this->authorize('owner', $order);

        /**
         * TODO : Bu roller db ye taşınmalı.
         * Bu sayede kişiye özel bir kupona tanımlanabilir ve yönetilebilir.
         * */

        $orderDiscountRules = [
            \App\Rules\Discount\OrderLimitPercentageDiscounter::class => [
                'limit'             => 1000,
                'discountPercentage'=> 10
            ],
            \App\Rules\Discount\OrderCategoryCheckProductDiscounter::class => [
                'categoryId'   => 1,
                'productCount' => 6,
                'freeCount'    => 1,
            ],
            \App\Rules\Discount\OrderCategoryCheckProductCheapestPercentageDiscounter::class => [
                'categoryId'        => 1,
                'productCount'      => 2,
                'discountPercentage'=> 20,
            ]
        ];

        $discounts = [];
        $totalDiscount = 0;
        foreach ($orderDiscountRules as $discounterClass => $options) {
            /** @var Discounter $discounterClass */
            $discount = (new $discounterClass($options))->calculate($order, $options);
            if($discount) {
                $discounts[] = $discount;
                $totalDiscount += $discount['discountAmount'];
            }
        }

        return response()->json([
            'orderId'       => $order->id,
            'discounts'     => $discounts,
            'totalDiscount' => $totalDiscount,
            'discountedTotal' => $order->total - $totalDiscount,
        ], 200);
    }
}
