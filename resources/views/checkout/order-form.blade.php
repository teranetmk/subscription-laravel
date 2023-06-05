<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-coolgray-400 sm:text-3xl sm:truncate">
                Order Form
            </h2>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <form id="order-form" action="{{route('checkout-summary')}}" method="GET" autocomplete="off">
            @csrf

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <span>
                    Grand Total:
                    <span id="grand-total-display" class="mr-3">
                        $0.00
                    </span>
                </span>
                <input id="grand-total" name="total" type="hidden" value="0">
                <a href="{{route('subscriptions')}}" class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-60">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                    Review & Pay
                </button>
            </div>
        </form>
        <div class="relative shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-8">
                <div>
                    <h3 class="text-xl leading-6 font-medium text-gray-900">
                        Select Subscriptions
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Choose options below. Enter payment details on the next screen.
                    </p>
                </div>

                <fieldset>
                    <div class="mt-4 space-y-4">
                        @forelse ($products as $product)
                            <div class="mb-8 pb-8 @if(!$loop->last) border-b border-gray-200 @endif">
                                <div x-data="{
                                    open: false,
                                    subtotal: 0,
                                    quantity:0,
                                    cost:{{$product->price}}
                                    }"
                                    x-init="
                                        $watch('subtotal', () => $nextTick(
                                            function() {
                                                grandTotal();
                                            }
                                        ))">
                                    <div class="h-5 flex items-center mb-1 font-medium text-gray-700">
                                        <label class="mr-3">
                                            {{$product->name}}
                                        </label>
                                        <span>
                                            {{dollars($product->price)}}/month
                                        </span>
                                        <button
                                            x-show="subtotal != 0"
                                            class="text-red-600 hover:text-red-800 ml-6 text-xs"
                                            x-on:click.prevent.stop="subtotal=0; quantity=0; clearRadios(event);"
                                            data-cleartarget="{{$product->id}}">
                                            Clear Price
                                        </button>
                                    </div>

                                    <button
                                        x-on:click.prevent.stop="open=!open"
                                        x-text="open ? 'Hide Options' : 'Choose Options'"
                                        class="text-xs text-indigo-600 border-gray-300 rounded mb-3">
                                    </button>

                                    <div x-cloak x-show="open" class="ml-3 text-sm">
                                        <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                                            <div class="sm:col-span-1">
                                                <label for="purchase_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                                    How Many?
                                                </label>
                                                <div class="relative bg-white rounded-md -space-y-px">
                                                    <label class="border-gray-200 rounded-tl-md rounded-tr-md relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                                                        <div class="flex items-center text-sm">
                                                            <input
                                                                x-on:change="subtotal=1*cost; quantity=1;"
                                                                type="radio"
                                                                name="quantity-{{$product->id}}"
                                                                class="purchase-quantity"
                                                                autocomplete="off">
                                                            <span id="pricing-plans-0-label" class="ml-3 font-medium text-gray-900">
                                                                One
                                                            </span>
                                                        </div>
                                                        <p id="pricing-plans-0-description-0" class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                                                            <span class="text-gray-900 font-medium">
                                                                {{dollars($product->price)}}/mo
                                                            </span>
                                                        </p>
                                                        <p id="pricing-plans-0-description-1" class="text-gray-500 ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right">
                                                            Recurring
                                                        </p>
                                                    </label>
                                                    <label class="border-gray-200 relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                                                        <div class="flex items-center text-sm">
                                                            <input
                                                                x-on:change="subtotal=2*cost; quantity=2;"
                                                                type="radio"
                                                                name="quantity-{{$product->id}}"
                                                                class="purchase-quantity"
                                                                autocomplete="off">
                                                            <span id="pricing-plans-0-label" class="ml-3 font-medium text-gray-900">
                                                                Two
                                                            </span>
                                                        </div>
                                                        <p id="pricing-plans-0-description-0" class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                                                            <span class="text-gray-900 font-medium">
                                                                {{dollars($product->price*2)}}/mo
                                                            </span>
                                                        </p>
                                                        <p id="pricing-plans-0-description-1" class="text-gray-500 ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right">
                                                            Recurring
                                                        </p>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="sm:col-span-1 pl-6">
                                                <dt class="text-sm font-medium text-gray-500">
                                                    Today's Charge
                                                </dt>
                                                <dd class="mt-1 text-xs text-gray-900">
                                                    <span id="{{$product->id}}-total-display" class="block" x-text="formatUSD(subtotal)"></span>
                                                    <span id="{{$product->id}}-next-renewal" class="block">
                                                        Next Renewal Date: {{$next_month->format('F d, Y')}}
                                                    </span>
                                                    <input
                                                        type="hidden"
                                                        id="{{$product->id}}-total"
                                                        data-orderitem="{{$product->id}}"
                                                        data-ordername="{{$product->name}}"
                                                        data-unitprice="{{$product->price}}"
                                                        x-bind:class="{'subtotal':subtotal != 0}"
                                                        x-bind:data-orderquantity="quantity"
                                                        x-bind:data-itemsubtotal="subtotal"
                                                        x-bind:value="subtotal"
                                                        class="block">
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-5">
                                <p class="text-gray-500">
                                    There are no available purchasing options available right now.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function formatUSD(value)
    {
        return (value / 100).toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        })
    }

    function grandTotal()
    {
        let grand_total = document.getElementById('grand-total')
        let grand_total_display = document.getElementById('grand-total-display')
        let subtotals = document.querySelectorAll('.subtotal')
        let r = 0
        subtotals.forEach(function(subtotal) {
            r += parseInt(subtotal.value)
        })
        grand_total.value = r
        grand_total_display.innerHTML = formatUSD(r)
        setOrderData(subtotals)
    }

    function setOrderData(subtotals)
    {
        let orderForm = document.getElementById('order-form')
        let inputId = document.createElement('input')
        inputId.setAttribute('type', 'hidden')

        let inputName = document.createElement('input')
        inputName.setAttribute('type', 'hidden')

        let inputQuantity = document.createElement('input')
        inputQuantity.setAttribute('type', 'hidden')

        let inputNextRenewal = document.createElement('input')
        inputNextRenewal.setAttribute('type', 'hidden')

        let inputItemSubtotal = document.createElement('input')
        inputItemSubtotal.setAttribute('type', 'hidden')

        let inputItemUnitPrice = document.createElement('input')
        inputItemUnitPrice.setAttribute('type', 'hidden')

        let i = 0;
        subtotals.forEach(function(subtotal){
            let renewal = document.getElementById(subtotal.dataset.orderitem + '-next-renewal');
            inputId.setAttribute('name',`items[${i}][id]`)
            inputId.setAttribute('value', subtotal.dataset.orderitem)
            inputName.setAttribute('name',`items[${i}][name]`)
            inputName.setAttribute('value', subtotal.dataset.ordername)
            inputQuantity.setAttribute('name', `items[${i}][quantity]`)
            inputQuantity.setAttribute('value', subtotal.dataset.orderquantity)
            inputItemSubtotal.setAttribute('name', `items[${i}][item-subtotal]`)
            inputItemSubtotal.setAttribute('value', subtotal.dataset.itemsubtotal)
            inputItemUnitPrice.setAttribute('name', `items[${i}][item-unit-price]`)
            inputItemUnitPrice.setAttribute('value', subtotal.dataset.unitprice)

            if(renewal) {
                inputNextRenewal.setAttribute('name', `items[${i}][next-renewal]`)
                inputNextRenewal.setAttribute('value', renewal.textContent)
            }

            orderForm.appendChild(inputId)
            orderForm.appendChild(inputName)
            orderForm.appendChild(inputQuantity)
            orderForm.appendChild(inputNextRenewal)
            orderForm.appendChild(inputItemSubtotal)
            orderForm.appendChild(inputItemUnitPrice)

            i++
        });
    }

    function clearRadios(event)
    {
        let target = 'quantity-' + event.target.dataset.cleartarget
        let inputs = document.querySelectorAll(`[name=${target}]`)
        inputs.forEach(function(input) {
            input.checked = false
        })
    }
</script>

