<x-app-layout>
    <div class="pb-16">


        <div class="bg-white rounded-md px-4 sm:px-6 ">
            <div class="pt-3 pb-6">


                <!-- Description list with inline editing -->
                <div class="mt-10 divide-y divide-gray-200">
                    <div class="flex justify-between items-center mb-4 pr-4">
                        <div class="w-full">
                            @if(session('error'))
                                <div class="text-red-600 font-bold">{{ session('error') }}</div>
                            @endif
                            <div class="flex justify-between items-end mb-3">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Payment Methods
                                    </h3>
                                    <p class="max-w-2xl text-sm text-gray-500 ">
                                        Manage your payment methods below
                                    </p>

                                </div>
                            </div>
                            <div style="max-width: 576px;">
                                <div class="flex justify-between items-center mb-4">
                                    @if($hasPaymentMethod)
                                        <a href="{{route('add-payment')}}" class="block mt-1 text-sm text-indigo-600 hover:text-indigo-500">Add New Payment Method</a>
                                        @if(!$hasActiveSubscriptions)
                                            <div x-data="{open:false}"
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
                                                <button x-on:click.prevent.stop="open=true" class="text-red-600 hover:text-red-400 text-sm">
                                                    Remove All Payment Methods
                                                </button>
                                                <div
                                                        x-transition:enter="transition ease-in duration-150"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-75"
                                                        x-transition:leave="transition ease-out duration-150"
                                                        x-transition:leave-start="opacity-75"
                                                        x-transition:leave-end="opacity-0"
                                                        x-cloak  x-show="open" class="fixed top-0 left-0 w-screen h-screen bg-gray-900 opacity-75 z-20"></div>
                                                <div
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 transform scale-75"
                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                    x-transition:leave-end="opacity-0 transform scale-75"
                                                    x-cloak x-show="open"
                                                    class="overflow-hidden fixed z-30 top-0 left-0 w-screen h-screen flex items-center justify-center">
                                                    <div
                                                        x-on:mousedown.away="open = false" x-on:keydown.window.escape="open = false"
                                                        class="absolute p-8 shadow rounded-lg sm:p-6 bg-white max-w-screen-md ">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                        Delete All Payment Methods
                                                        </h3>
                                                        <div class="mt-2 max-w-xl text-sm text-gray-500">
                                                        <p>
                                                            This will permanently remove all payment methods. Once you delete your payment methods, you will have to enter new card details to purchase subscriptions.
                                                        </p>
                                                        </div>
                                                        <div class="mt-5 flex justify-between">
                                                        <form id="" method="POST" action="{{route('remove-all-payments')}}" class="inline text-center">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                                                Delete Payment Methods
                                                            </button>
                                                        </form>
                                                        <button x-on:click.prevent.stop="open=false" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                                            Nevermind, I'll keep them
                                                        </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{route('order-form')}}" class="block mt-1 text-sm text-indigo-600 hover:text-indigo-500">Click here to Place an order</a>
                                    @endif
                                </div>
                                <div class="relative bg-white rounded-md -space-y-px">
                                    @forelse ($payment_methods as $method)
                                        <div class=" {{$loop->first ? 'rounded-tl-md rounded-tr-md' : ''}} {{$loop->last ? 'rounded-bl-md rounded-br-md' : ''}} border-gray-200 relative border p-3 text-gray-800 text-sm flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-5 ">
                                            <span>
                                                @switch($method['card']['brand'])
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
                                                    {{$method['card']['brand']}}
                                                @endswitch
                                            </span>
                                            <span>....{{$method['card']['last4']}} </span>
                                            <span>{{$method['card']['exp_month']}}/{{$method['card']['exp_year']}} </span>
                                            @if($method['is_default'])
                                                @if(!$hasActiveSubscriptions)
                                                    <div x-data="{open:false}"
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
                                                        <button x-on:click.prevent.stop="open=true" class="w-full text-red-600 hover:text-red-400 text-sm">
                                                            remove
                                                        </button>
                                                        <div
                                                                x-transition:enter="transition ease-in duration-150"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-75"
                                                                x-transition:leave="transition ease-out duration-150"
                                                                x-transition:leave-start="opacity-75"
                                                                x-transition:leave-end="opacity-0"
                                                                x-cloak  x-show="open" class="fixed top-0 left-0 w-screen h-screen bg-gray-900 opacity-75 z-20"></div>
                                                        <div
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 transform scale-75"
                                                            x-transition:enter-end="opacity-100 transform scale-100"
                                                            x-transition:leave="transition ease-in duration-150"
                                                            x-transition:leave-start="opacity-100 transform scale-100"
                                                            x-transition:leave-end="opacity-0 transform scale-75"
                                                            x-cloak x-show="open"
                                                            class="overflow-hidden fixed z-30 top-0 left-0 w-screen h-screen flex items-center justify-center">
                                                            <div
                                                                x-on:mousedown.away="open = false" x-on:keydown.window.escape="open = false"
                                                                class="absolute p-8 shadow rounded-lg sm:p-6 bg-white max-w-screen-md ">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                Delete Payment Method
                                                                </h3>
                                                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                                                    <p>
                                                                        This will permanently remove
                                                                        <span>....{{$method['card']['last4']}} </span>
                                                                        <span>{{$method['card']['exp_month']}}/{{$method['card']['exp_year']}} </span>.
                                                                    </p>
                                                                </div>
                                                                <div class="mt-5 flex justify-between">
                                                                    <form method="POST" action="{{route('remove-payment')}}" class="inline text-center">
                                                                        @csrf
                                                                        <input type="hidden" name="payment_id" value="{{$method['id']}}">
                                                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                    <button x-on:click.prevent.stop="open=false" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                                                        Nevermind, I'll keep it
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span x-data="{reveal:false}" class="relative text-center text-xs leading-5 font-semibold rounded-full text-gray-400">
                                                        <svg x-on:mouseover="reveal=true"  x-on:mouseout="reveal=false" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 m-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <div x-cloak x-show="reveal" class="absolute z-10 w-52 bg-white p-4 text-gray-700 text-xs rounded shadow" style="top:-115px;">
                                                            The default payment method cannot be removed while site subscriptions are active. All must be cancelled first.
                                                        </div>
                                                    </span>
                                                @endif
                                                <span class="text-center">
                                                    <span class="text-xs px-2 py-1 leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        default
                                                    </span>
                                                </span>
                                            @else
                                                <div x-data="{open:false}"
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
                                                        <button x-on:click.prevent.stop="open=true" class="w-full text-red-600 hover:text-red-400 text-sm">
                                                            remove
                                                        </button>
                                                        <div
                                                                x-transition:enter="transition ease-in duration-150"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-75"
                                                                x-transition:leave="transition ease-out duration-150"
                                                                x-transition:leave-start="opacity-75"
                                                                x-transition:leave-end="opacity-0"
                                                                x-cloak  x-show="open" class="fixed top-0 left-0 w-screen h-screen bg-gray-900 opacity-75 z-20"></div>
                                                        <div
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 transform scale-75"
                                                            x-transition:enter-end="opacity-100 transform scale-100"
                                                            x-transition:leave="transition ease-in duration-150"
                                                            x-transition:leave-start="opacity-100 transform scale-100"
                                                            x-transition:leave-end="opacity-0 transform scale-75"
                                                            x-cloak x-show="open"
                                                            class="overflow-hidden fixed z-30 top-0 left-0 w-screen h-screen flex items-center justify-center">
                                                            <div
                                                                x-on:mousedown.away="open = false" x-on:keydown.window.escape="open = false"
                                                                class="absolute p-8 shadow rounded-lg sm:p-6 bg-white max-w-screen-md ">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                Delete Payment Method
                                                                </h3>
                                                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                                                    <p>
                                                                        This will permanently remove
                                                                        <span>....{{$method['card']['last4']}} </span>
                                                                        <span>{{$method['card']['exp_month']}}/{{$method['card']['exp_year']}} </span>.
                                                                    </p>
                                                                </div>
                                                                <div class="mt-5 flex justify-between">
                                                                    <form method="POST"  action="{{route('remove-payment')}}" class="inline text-center">
                                                                        @csrf
                                                                        <input type="hidden" name="payment_id" value="{{$method['id']}}">
                                                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                                                            remove
                                                                        </button>
                                                                    </form>
                                                                    <button x-on:click.prevent.stop="open=false" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                                                        Nevermind, I'll keep it
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <form method="POST" action="{{route('set-default-payment')}}" class="inline text-center">
                                                    @csrf
                                                    <input type="hidden" name="payment_id" value="{{$method['id']}}">
                                                    <button type="submit" class="text-center text-blue-600 hover:text-blue-400">
                                                        set as default
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        There are no payment methods yet.
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
