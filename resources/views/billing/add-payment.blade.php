<x-app-layout>
    <div class="pb-16">


        <div class="bg-white rounded-md px-4 sm:px-6 ">
            <div class="pt-3 pb-6">


                <!-- Description list with inline editing -->
                <div class="mt-10 divide-y divide-gray-200">
                    <div class="flex justify-between items-center mb-4 pr-4">
                        <div  x-data="{
                            confirmAuthorize: false,
                            }"
                            class="w-full">
                            @if(session('error'))
                                <div class="text-red-600 font-bold">{{ session('error') }}</div>
                            @endif
                            <div class="px-3">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Add New Payment Method
                                </h3>
                                <p class="max-w-2xl text-sm text-gray-500 ">
                                    Submit the form below to add a new payment method. After it's added, you will be able to select it as the default payment method if needed.
                                </p>
                                <div class="flex items-center my-6">
                                    <input
                                        x-on:change="confirmAuthorize=!confirmAuthorize"
                                        id="confirm_authorize" name="confirm_authorize" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" x-bind:value="confirmAuthorize">
                                    <label for="confirm_authorize" class="ml-2 block text-xs text-gray-600">
                                        I acknowledge that I am authorized to add this card as a method of payment.
                                    </label>
                                </div>
                            </div>
                            <div class="px-3">
                                <form action="{{route('store-payment')}}" method="POST" id="add-payment-form" autocomplete="off" class="mt-5">
                                    @csrf
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center space-x-1">
                                            <small>
                                                100% Secure
                                            </small>
                                            <img src="{{asset('images/visa.svg')}}" alt="Visa Accepted" width="30">
                                            <img src="{{asset('images/mastercard.svg')}}" alt="Mastercard Accepted" width="30">
                                            <img src="{{asset('images/amex.svg')}}" alt="American Express Accepted" width="30">
                                            <img src="{{asset('images/discover.svg')}}" alt="Discover Accepted" width="30">
                                        </div>
                                    </div>
                                    <div style="max-width: 400px;">
                                        <input type="hidden" name="payment_id" id="payment-method" value="">
                                        <input id="card-holder-name" type="text" placeholder="Name" class="mb-3 shadow-sm block w-full sm:text-sm border-gray-300 rounded-md">

                                        <!-- Stripe Elements Placeholder -->
                                        <div id="card-element" class="appearance-none rounded relative block w-full mb-2 px-3 py-2 border border-gray-300 shadow-sm text-gray-900 p-4"></div>
                                        <div id="card-errors" class="text-red-500 mb-4" role="alert"></div>

                                        <div class="flex justify-between items-center px-1">
                                            <button
                                                x-bind:disabled="!confirmAuthorize"
                                                x-bind:class="confirmAuthorize ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed pointer-events-none'" class="border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600" id="card-button">
                                                Add Payment Method
                                            </button>
                                            <a x-on:click.stop="confirmAuthorize=false;"
                                                class="text-red-600 hover:text-red-500"
                                                 href="{{route('billing')}}">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<div id="processing-loader" class="fixed transition-all ease-in-out hidden flex-col items-center justify-center top-0 left-0 overflow-hidden z-50 h-full w-full bg-white opacity-75 text-green-600">
    <h2 class="mb-4 text-green-500">
        Validating Card
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
                const getCheckoutForm = document.getElementById('add-payment-form');
                paymentMethod = result.setupIntent.payment_method;
                getPaymentMethod.value = paymentMethod;
                getCheckoutForm.submit();
            }
            }
        );
        return false;
    });
</script>
