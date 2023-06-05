<?php
namespace App\Actions\Subscription;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class ManageSubscriptions
{
    public function show()
    {
        try {
            $user = currentUser();
            $subscriptions = $user->subscriptions()->active()->get();
            $products = Product::all();
            $today = CarbonImmutable::now();
            $next_month = $today->add(1, 'month');

            $subscriptions = $subscriptions->map(function($sub, $key) {
                $stripe = $sub->asStripeSubscription();
                $product = DB::table('products')->where('stripe_price_id', $stripe->plan->id)->first();
                $sub->product_name = $product->name;
                $sub->product_price = $product->price;
                $sub->product_total_price = $product->price * $stripe->quantity;
                $sub->next_charge_date = formatDate($stripe->current_period_end);
                $sub->last_charge_date = formatDate($stripe->current_period_start);
                return $sub;
            });

            return view('subscription.show', compact('user', 'subscriptions', 'next_month'));
        } catch(\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function increaseSubscriptionQuantity(Request $request)
    {
        $attributes = $request->validate([
            'confirm_authorize' => ['required','in:on'],
            'quantity' => ['integer', 'required', 'gt:0', 'lt:10'],
            'subscription_name' => ['string', 'required', 'max:80']
        ]);

        try{
            $quantity = $attributes['quantity'];
            $name = $attributes['subscription_name'];
            $user = currentUser();
            $user->subscription($name)->incrementQuantity($quantity);
            return redirect()->route('order-complete')->with('success', 'Subscription quantity increased, you can add more sites now.');
        } catch(\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function subscribeWithExisting(Request $request)
    {
        $attributes = $request->validate([
            'confirm_authorize' => ['required','in:on'],
            'total' => ['integer', 'required', 'gt:0'],
            'items' => ['array', 'required'],
            'items.*.id' => ['integer','required_with:items'],
            'items.*.quantity' => ['integer','required_with:items'],
        ]);

        $products = $attributes['items'];
        $price = (int) $attributes['total'];

        $user = currentUser();
        $paymentMethod = $user->defaultPaymentMethod();
        $transaction_id = $user->id .'-'. makeTransactionId();

        try {
            foreach($products as $key => $product) {
                $item = Product::find($product['id']);
                $stripe_price_id = $item->stripe_price_id;
                $subscription_name = $item->name;
                $quantity = $product['quantity'];
                $user->newSubscription($subscription_name, $stripe_price_id)
                    ->quantity($quantity)
                    ->create($paymentMethod->id,[],[
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

    public function cancel(Request $request)
    {
        $stripe_id = $request->input('stripe_id');
        try {
            $user = currentUser();
            $subscription = $user->subscriptions->where('stripe_status','active')->where('stripe_id',$stripe_id)->first();

            if(is_null($subscription)) {
                return redirect()->route('subscriptions')->with('info', 'subscription not found.');
            }

            if($subscription->user_id != $user->id) {
                return redirect()->route('subscriptions')->with('info', 'Only the account holder can cancel a subscription');
            }

            $subscription->cancelNow();

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('subscriptions')->with('success', 'subscription removed.');
    }
}
