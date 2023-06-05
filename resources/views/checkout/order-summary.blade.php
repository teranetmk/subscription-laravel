<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-coolgray-400 sm:text-3xl sm:truncate">
                Order Summary
            </h2>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg" style="max-width: 600px;">
        <div class="px-4 pt-5 pb-3 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Order Details
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Review and pay with credit card below
            </p>
            @if(session('error'))
                <div class="font-main text-red-600 font-bold">{{ session('error') }}</div>
            @endif
        </div>
        <div class="border-t border-gray-200 px-4 pt-5 pb-10 sm:px-7">
            <dt class="text-sm font-medium text-gray-500 mb-2">
                Items
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                @foreach ($orderDetails['items'] as $item)
                    <div class="border border-gray-200 mb-2 p-3 rounded">
                        <div class="text-sm">
                            {{$item['name']}}
                        </div>
                        <div class="text-xs">
                            Quantity {{$item['quantity']}}, {{dollars($item['item-unit-price'])}} each, {{dollars($item['item-subtotal'])}}
                        </div>
                        <div class="text-xs">
                            {{$item['next-renewal']}}
                        </div>
                    </div>
                @endforeach
            </dd>
            <h3 class="text-gray-700 mt-4 mb-2">
                Total to be Charged: {{dollars($orderDetails['total'])}}
            </h3>
            <div class="flex items-center space-x-1">
                <small>
                    100% Secure Checkout
                </small>
                <img src="{{asset('images/visa.svg')}}" alt="Visa Accepted" width="30">
                <img src="{{asset('images/mastercard.svg')}}" alt="Mastercard Accepted" width="30">
                <img src="{{asset('images/amex.svg')}}" alt="American Express Accepted" width="30">
                <img src="{{asset('images/discover.svg')}}" alt="Discover Accepted" width="30">
            </div>
            @if($hasPaymentMethod)
                <div x-data="{showButton:'existing'}" class="my-4">
                    <button x-show="showButton=='existing'" x-on:click.prevent="$dispatch('set-existing'), showButton='new'" type="button" class="py-1 px-3 text-xs text-white bg-purple-600 hover:bg-purple-500 rounded-md">
                        use default payment
                    </button>
                    <button x-cloak x-show="showButton=='new'" x-on:click.prevent="$dispatch('set-new'), showButton='existing'" type="button" class="py-1 px-3 text-xs text-white bg-green-600 hover:bg-green-500 rounded-md">
                        use new payment
                    </button>
                </div>
            @endif

            <div x-data="{newPayment:true}" @set-existing.window="newPayment = false" @set-new.window="newPayment = true">
                <div x-show="newPayment" x-transition>
                    <form action="{{route('process-checkout')}}" method="POST" id="checkout-form" autocomplete="off" class="mt-5">
                        @csrf
                        <div class="space-y-2 mb-4">
                            <input type="hidden" name="total" value="{{$orderDetails['total']}}">
                            @foreach ($orderDetails['items'] as $i => $item)
                                <input type="hidden" name="items[{{$i}}][id]" value="{{$item['id']}}">
                                <input type="hidden" name="items[{{$i}}][quantity]" value="{{$item['quantity']}}">
                            @endforeach
                        </div>
                        <div x-data="{confirmAuthorize: false}" style="max-width: 400px;">
                            <div class="flex items-start mb-6">
                                <input
                                    x-on:change="confirmAuthorize=!confirmAuthorize"
                                    id="confirm_authorize" name="confirm_authorize" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" x-bind:value="confirmAuthorize">
                                <label for="confirm_authorize" class="ml-2 block text-xs text-gray-600">
                                    I authorize these charges and understand that a recurring charge of {{dollars($orderDetails['total'])}} will automatically be processed each month on the renewal date.
                                </label>
                            </div>
                            <input type="hidden" name="payment-method" id="payment-method" value="">
                            <input id="card-holder-name" type="text" placeholder="Name" class="mb-3 shadow-sm block w-full sm:text-sm border-gray-300 rounded-md"  >
                            <!-- Stripe Elements Placeholder -->
                            <div id="card-element" class="appearance-none rounded relative block w-full mb-2 px-3 py-2 border border-gray-300 shadow-sm text-gray-900 p-4"></div>
                            <div id="card-errors" class="text-red-500 mb-4" role="alert"></div>

                            <div class="flex justify-between items-center px-1">
                                <button
                                    x-bind:disabled="!confirmAuthorize"
                                    x-bind:class="confirmAuthorize ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed pointer-events-none'"
                                    class="border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600" id="card-button">
                                    Process Payment
                                </button>
                                <a x-on:click.stop="confirmAuthorize=false;" class="text-red-600 hover:text-red-500" href="{{route('order-form')}}">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            @if($hasPaymentMethod)
                <div x-data="{existingPayment:false}" @set-existing.window="existingPayment = true" @set-new.window="existingPayment = false" x-transition>
                    <div x-cloak x-show="existingPayment">
                        <div class="flex items-end space-x-3 text-sm mt-3">
                            <span>
                                @switch($defaultPayment['brand'])
                                    @case('visa')
                                        <img class="mr-1 inline-block" src="{{asset('images/visa.svg')}}" alt="Visa Accepted" width="30">
                                        @break
                                    @case('mastercard')
                                        <img src="{{asset('images/mastercard.svg')}}" alt="Mastercard Accepted" width="30">
                                        @break
                                    @case('amex')
                                        <img src="{{asset('images/amex.svg')}}" alt="American Express Accepted" width="30">
                                        @break
                                    @case('discover')
                                        <img src="{{asset('images/discover.svg')}}" alt="Discover Accepted" width="30">
                                        @break
                                    @default
                                    {{$defaultPayment['brand']}}
                                @endswitch
                                ...{{$defaultPayment['last4']}}
                            </span>
                            <span>
                                Exp: {{$defaultPayment['expiration']}}
                            </span>
                        </div>
                        <form action="{{route('subscribe-with-existing')}}" method="POST" id="checkout-form" autocomplete="off" class="mt-5">
                            @csrf

                            <input type="hidden" name="total" value="{{$orderDetails['total']}}">
                            @foreach ($orderDetails['items'] as $i => $item)
                                <input type="hidden" name="items[{{$i}}][id]" value="{{$item['id']}}">
                                <input type="hidden" name="items[{{$i}}][quantity]" value="{{$item['quantity']}}">
                            @endforeach
                            <div x-data="{confirmAuthorize: false}" style="max-width: 400px;">
                                <div class="flex items-start mb-6">
                                    <input
                                        x-on:change="confirmAuthorize=!confirmAuthorize"
                                        id="confirm_authorize" name="confirm_authorize" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" x-bind:value="confirmAuthorize">
                                    <label for="confirm_authorize" class="ml-2 block text-xs text-gray-600">
                                        I authorize these charges and understand that a recurring charge of {{dollars($orderDetails['total'])}} will automatically be processed each month on the renewal date.
                                    </label>
                                </div>

                                <div class="flex justify-between items-center px-1">
                                    <button
                                        x-bind:disabled="!confirmAuthorize"
                                        x-bind:class="confirmAuthorize ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed pointer-events-none'"
                                        class="border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600" id="card-button">
                                        Process Payment
                                    </button>
                                    <a x-on:click.stop="confirmAuthorize=false;" class="text-red-600 hover:text-red-500" href="{{route('order-form')}}">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

<div id="processing-loader" class="fixed transition-all ease-in-out hidden flex-col items-center justify-center top-0 left-0 overflow-hidden z-50 h-full w-full bg-white opacity-75 text-green-600">
    <h2 class="mb-4 text-green-500">
        Payment Processing
    </h2>
    <svg  class="as-animate-spin" viewBox="0 0 24 24" width="92" height="92" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="23 4 23 10 17 10"></polyline>
        <polyline points="1 20 1 14 7 14"></polyline>
        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
    </svg>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe( "{{env('STRIPE_KEY')}}" );

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    let paymentMethod = null;
    let paymentByCard = null;
    let body = document.body;
    let processingLoader = document.getElementById('processing-loader');

    cardButton.addEventListener('click', async (e) => {
        e.preventDefault();
        if(paymentMethod){
            return true;
        }
        body.classList.add('overflow-hidden');
        processingLoader.classList.remove('hidden');
        processingLoader.classList.add('flex');

        const { setupIntent, error } = await stripe.confirmCardSetup(
            "{{$intent->client_secret}}",
            {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
            }).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    body.classList.remove('overflow-hidden');
                    processingLoader.classList.add('hidden');
                    processingLoader.classList.remove('flex');
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    const getPaymentMethod = document.getElementById('payment-method');
                    const getCheckoutForm = document.getElementById('checkout-form');
                    paymentMethod = result.setupIntent.payment_method;
                    getPaymentMethod.value = paymentMethod;
                    getCheckoutForm.submit();
                }
            }
        );
        return false;
    });
</script>
