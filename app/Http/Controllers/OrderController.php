<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // get the items in the cart
        $cart = session('cart');

        // Validate cart contents
        if (empty($cart)) {
            return response("Shopping cart is empty", 422);
        }

        // Save entires to order tables
        DB::beginTransaction();

        // Create new order entry
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $orderAmount = 0;
        $order->amount = $orderAmount;
        $order->save();

        // Add products to order from cart
        foreach ($cart as $item) {
            $product = Product::findOrFail($item['product_id']);
            $productAmount = $product->price * $item['quantity'];
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $item['product_id'];
            $orderProduct->quantity = $item['quantity'];
            $orderProduct->amount = $productAmount;
            $order->order_products()->save($orderProduct);
            $orderAmount += $productAmount;
        }

        // Update order with the total amount
        $order->amount = $orderAmount;
        $order->save();

        /**
         * Send the order to a payment service such as PayPal or Stripe
         *
         * This service would be provided with the order details and the user
         * would be redirected to a page whereby they enter their payment details.
         *
         * For this example project this will be simulated randomly as a success or
         * failed payment. The payment service would often provide success/failure
         * results via redirecting to routes/URLs that are provided to it.
         */

        $paymentSuccess = rand(0, 1) == 1;

        // save the order on success
        if ($paymentSuccess) {
            DB::commit();

            // Clear the cart
            session()->put('cart', []);

            return response("Successfully placed order", 200);
        }

        // unsuccessful payment (don't save the order)
        DB::rollBack();
        return response("Error processing payment", 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response($order);
    }
}
