<?php
namespace App\Actions\Subscription;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class ManageBilling
{
    public function show()
    {
        try {
            $user = currentUser();
            $hasActiveSubscriptions = $user->hasActiveSubscriptions();
            $payment_methods = [];
            $default_method = null;
            $hasPaymentMethod = $user->hasPaymentMethod();
            if ($hasPaymentMethod) {
                $payment_methods = $user->paymentMethods()->toArray();
                $hasDefault = $user->hasDefaultPaymentMethod();
                if($hasDefault) {
                    $default_method = $user->defaultPaymentMethod()->toArray();
                }

                $payment_methods = array_map(function($method) use($default_method, $hasDefault) {
                    if($hasDefault && $method['id'] == $default_method['id']) {
                        $method['is_default'] = 1;
                        return $method;
                    } else {
                        $method['is_default'] = 0;
                        return $method;
                    }
                }, $payment_methods);

                usort($payment_methods, function($a, $b){
                    if($a['is_default'] == $b['is_default']) {
                        return 0;
                    }
                    return ($a['is_default'] > $b['is_default']) ? -1 : 1;
                });
            }
            return view('billing.show', compact('user','payment_methods','hasActiveSubscriptions','hasPaymentMethod'));
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function removePayment(Request $request)
    {
        $pm = $request->validate(['payment_id' => ['required', 'string']]);
        try {
            $user = currentUser();
            $payment_method = $user->findPaymentMethod($pm['payment_id']);
            if(is_null($payment_method)){
                return redirect()->back()->with('danger', 'Payment method not found');
            }
            $payment_method->delete();

            if($user->hasPaymentMethod()) {
                $hasDefault = $user->hasDefaultPaymentMethod();
                if(!$hasDefault) {
                   $fallbackDefault = $user->paymentMethods()->first();
                   $user->updateDefaultPaymentMethod($fallbackDefault->id);
                }
            }
            return redirect()->route('billing')->with('info', 'Payment method removed.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function removeAllPayments()
    {
        try {
            $user = currentUser();
            $user->deletePaymentMethods();
            return redirect()->route('billing')->with('info', 'All payment methods have been removed.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function addPayment()
    {
        try {
            $user = currentUser();
            $intent = $user->createSetupIntent();
            return view('billing.add-payment', compact('user', 'intent'));
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function storePayment(Request $request)
    {
        $pm = $request->validate(['payment_id' => ['required', 'string']]);
        try {
            $user = currentUser();
            $user->addPaymentMethod($pm['payment_id']);
            return redirect()->route('billing')->with('success', 'Payment method added.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function setDefaultPayment(Request $request)
    {
        $pm = $request->validate(['payment_id' => ['required', 'string']]);
        try {
            $user = currentUser();
            $user->updateDefaultPaymentMethod($pm['payment_id']);
            return redirect()->route('billing')->with('success', 'Default Payment method has been changed.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

}
