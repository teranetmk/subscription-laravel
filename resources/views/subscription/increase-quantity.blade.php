<div x-data="{
    open:false,
    unitPrice: {{$subscription->product_price}},
    currentPrice: {{$subscription->product_total_price}},
    total:0,
    confirmAuthorize: false,
    }"
    x-init="
    $watch('open', value => {
        const body = document.body;
        if(!open) {
        body.classList.remove('h-screen');
        return body.classList.remove('overflow-hidden');
        } else {
            body.classList.add('h-screen');
            return body.classList.add('overflow-hidden');
        }
    });">

    <div
        x-transition:enter="transition ease-in duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-75"
        x-transition:leave="transition ease-out duration-150"
        x-transition:leave-start="opacity-75"
        x-transition:leave-end="opacity-0"
        x-cloak  x-on:click.prevent="open=false; total=0; clearRadios(); clearCheckboxes(); confirmAuthorize=false;" x-show="open" class="fixed top-0 left-0 w-screen h-screen bg-gray-900 opacity-75 z-30"></div>
    <div
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-24"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-24"
        x-cloak x-show="open" class="overflow-hidden h-screen w-full max-w-screen-md bg-white fixed right-0 top-0 z-40 p-12 shadow rounded">
        <h3 class="my-3 text-lg">
            Add More Sites to your {{$subscription->product_name}}
        </h3>
        <div class="flex justify-between items-center">
            <h3 class="my-3 text-base">
                Choose the quantity to add to your subscription
            </h3>
            <button
                x-show="total>0"
                class="text-red-600 hover:text-red-800 text-xs mr-3"
                x-on:click.prevent.stop="total=0; clearRadios(); clearCheckboxes(); confirmAuthorize=false;">
                Reset Form
            </button>
        </div>
        <form method="POST" action="{{route('increase-subscription-quantity')}}">
            @csrf
            <input type="hidden" name="subscription_name" value="{{$subscription->name}}">
            <div class="relative bg-white rounded-md -space-y-px">
                <label class="border-gray-200 rounded-tl-md rounded-tr-md relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                    <div class="flex items-center text-sm">
                        <input
                            x-on:change="total=unitPrice + currentPrice"
                            type="radio"
                            name="quantity"
                            value="1"
                            autocomplete="off">
                        <span id="pricing-plans-0-label" class="ml-3 font-medium text-gray-900">
                            One Site
                        </span>
                    </div>
                    <p id="pricing-plans-0-description-0" class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                        <span class="text-gray-900 font-medium">
                            <span class="italic text-xs text-coolgray-500">adds: </span> {{dollars($subscription->product_price)}}/mo
                        </span>
                    </p>

                    <p id="pricing-plans-0-description-1" class="text-gray-500 ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right">
                        Recurring
                    </p>
                </label>
                <label class="border-gray-200 relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                    <div class="flex items-center text-sm">
                        <input
                            x-on:change="total=(unitPrice*2) + currentPrice"
                            type="radio"
                            name="quantity"
                            autocomplete="off"
                            value="2">
                        <span id="pricing-plans-0-label" class="ml-3 font-medium text-gray-900">
                            Two Sites
                        </span>
                    </div>
                    <p id="pricing-plans-0-description-0" class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                        <span class="text-gray-900 font-medium">
                            <span class="italic text-xs text-coolgray-500">adds: </span>{{dollars($subscription->product_price * 2)}}/mo
                        </span>
                    </p>
                    <p id="pricing-plans-0-description-1" class="text-gray-500 ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right">
                        Recurring
                    </p>
                </label>

            </div>
            <div class="mt-4 mb-3 space-x-2 text-sm text-indigo-800">
                <span>Current Monthly Cost: {{dollars($subscription->product_total_price)}}</span>
                <span x-cloak x-show="total!=0">New Monthly Cost: <span x-text="formatUSD(total)"></span></span>
            </div>
            <div class="mb-6 text-sm">
                <h4>Clicking the button below will increase the quantity of your subscription, you will be charged a prorated amount. And the full amount beginning on your next renewal date.</h4>
            </div>
            <div x-cloak x-show="total>0" class="flex items-center mb-6">
                <input
                    x-on:change="confirmAuthorize=!confirmAuthorize"
                    id="confirm_authorize" name="confirm_authorize" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" x-bind:value="confirmAuthorize">
                <label for="confirm_authorize" class="ml-2 block text-xs text-gray-600">
                    I authorize these charges and understand that my new recurring charge will be <span x-text="formatUSD(total)"></span>.
                </label>
            </div>
            <div class="flex justify-between items-center">
                <button
                    x-bind:disabled="!confirmAuthorize"
                    x-bind:class="confirmAuthorize ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed pointer-events-none'"
                    type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add to Subscription
                </button>
                <button x-on:click.prevent.stop="open=false; total=0; clearRadios(); clearCheckboxes(); confirmAuthorize=false;" class="text-base text-red-600 hover:text-red-400 mr-4">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    <button x-on:click.prevent.stop="open=!open" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Add Quantity
    </button>
    <script>
        function clearRadios()
        {
            let inputs = document.querySelectorAll(`[type=radio]`);
            inputs.forEach(function(input) {
                input.checked = false
            })
        }
        function clearCheckboxes()
        {
            let inputs = document.querySelectorAll(`[type=checkbox]`);
            inputs.forEach(function(input) {
                input.checked = false
            })
        }
        function formatUSD(value)
        {
            return (value / 100).toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD'
            })
        }
    </script>
</div>

