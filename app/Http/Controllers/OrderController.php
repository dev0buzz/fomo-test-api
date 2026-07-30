<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('customer')->get();

        return [
            'message' => 'Success',
            'data' => $orders,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $order = \DB::transaction(function () use ($request) {
            // lock product for update - check product stock
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if(!$product || $product->stock < $item['qty']) {
                    // stop transaction and return error
                    throw ValidationException::withMessages([
                        'items' => "The product '{$product->name}' does not have enough stock ({$product->stock} available)"
                    ]);
                }
            }

            // calculate price * qty
            $totalPrice = collect($request->items)->sum(function ($item) {
                return $item['qty'] * $item['price'];
            });

            // add order and set to completed (sample)
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'total' => $totalPrice,
                'status' => 'completed',
            ]);

            // order items
            $orderItems = collect($request->items)->map(function ($item) {
                // deduct product stock here
                Product::where('id',$item['id'])->decrement('stock', $item['qty']);

                return [
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ];
            });

            // create order items
            $order->items()->createMany($orderItems->toArray());

            return $order;
        });

        // load customer and product to order
        $order->load(['customer', 'items.product']);

        return [
            'message' => 'Order created',
            'data' => $order,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // load customer and product to order
        $order->load(['customer', 'items.product']);

        return [
            'message' => 'Success',
            'data' => $order,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return [
            'message' => 'Order updated',
            'data' => $order,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return [
            'message' => 'Order deleted',
        ];
    }
}
