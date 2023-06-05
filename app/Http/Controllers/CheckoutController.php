<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function orderForm()
    {
        $user = currentUser();
        // if($user->hasActiveSubscriptions()) {
        //     return redirect()->route('subscriptions')->with('info', 'Use the options below to add to your existing subscription');
        // }

        $today = CarbonImmutable::now();
        $products = Product::all();
        $next_month = $today->add(1, 'month');
        return view('checkout.order-form', compact('products', 'next_month'));
    }

    public function checkoutSummary(Request $request)
    {
        $user = currentUser();
        // if($user->hasActiveSubscriptions()) {
        //     return redirect()->route('subscriptions')->with('info', 'Use the options below to add to your existing subscription');
        // }
        $hasPaymentMethod = $user->hasDefaultPaymentMethod();
        $defaultPayment = [];
        if($hasPaymentMethod) {
            $card = $user->defaultPaymentMethod()->toArray()['card'];
            $defaultPayment = [
                'brand' => $card['brand'],
                'expiration' => $card['exp_month'].'/'.$card['exp_year'],
                'last4' => $card['last4']
            ];
        }
        if(empty($request->input())) {
            return redirect()->route('order-form')->with('info', 'Select items in order form below');
        }

        $orderDetails = $request->validate([
            'total' => ['integer', 'required', 'gt:0'],
            'items' => ['array', 'required'],
            'items.*.id' => ['integer','required_with:items'],
            'items.*.name' => ['string','required_with:items'],
            'items.*.quantity' => ['integer','required_with:items'],
            'items.*.next-renewal' => ['string','required_with:items'],
            'items.*.item-subtotal' => ['string','required_with:items'],
            'items.*.item-unit-price' => ['string','required_with:items'],
        ]);

        $intent = $user->createSetupIntent();
        return view('checkout.order-summary', compact('intent','orderDetails','hasPaymentMethod','defaultPayment'));
    }

    public function processCheckout(Request $request)
    {
        $attributes = $request->validate([
            'confirm_authorize' => ['required','in:on'],
            'total' => ['integer', 'required', 'gt:0'],
            'items' => ['array', 'required'],
            'items.*.id' => ['integer','required_with:items'],
            'items.*.quantity' => ['integer','required_with:items'],
        ]);

        $user = currentUser();
        $price = (int) $attributes['total'];
        $products = $attributes['items'];
        $transaction_id = $user->id .'-'. makeTransactionId();

        try {
            foreach($products as $key => $product) {
                $item = Product::find($product['id']);
                $stripe_price_id = $item->stripe_price_id;
                $subscription_name = $item->name;
                $quantity = $product['quantity'];
                $user->newSubscription($subscription_name, $stripe_price_id)
                    ->quantity($quantity)
                    ->create($request->input('payment-method'),[],[
                        'payment_behavior' => 'error_if_incomplete',
                    ]);
                $products[$key]['name'] =  $item->name;
            }

            $purchase_record = [
                'transaction_id' => $transaction_id,
                'user_id' => $user->id,
                'purchase_date' => CarbonImmutable::now(),
                'amount' => $price,
                'status' => 'Success',
                'last_four' => $user->pm_last_four,
                'card_type' => ucwords($user->pm_type),
            ];
            $record = new Purchase();
            $new_purchase = $record->create($purchase_record);

            $purchase_record['user'] = $user->name;
            $purchase_record['product_details'] = $products;

            return redirect()->route('order-complete')->with('purchase_record', $purchase_record)->with('success','Payment Successful.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function orderComplete()
    {
        $details = session()->get('purchase_record');
        return view('checkout.order-complete', compact('details'));
    }
}
