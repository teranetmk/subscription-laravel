<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold leading-7 text-coolgray-400 sm:text-3xl sm:truncate">
                Thank You!
            </h2>
            <h2 class="text-xl font-bold leading-7 text-coolgray-400 sm:truncate">
                Order Complete
            </h2>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg" style="max-width: 600px;">
        <div class="px-4 pt-5 pb-3 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Order Details
                </h3>
                <button class="flex items-center text-sm text-indigo-600 hover:text-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download PDF Receipt
                </button>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">

            @if(!is_null($details))
                <div class="sm:col-span-1 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                        Order Id
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{$details['transaction_id']}}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Purchase Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{formatDate($details['purchase_date'])}}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Status:
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{$details['status']}}
                        </dd>
                    </div>
                </div>
                <div class="sm:col-span-1 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Ordered By
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{$details['user']}}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Amount:
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{dollars($details['amount'])}}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Card:
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @switch($details['card_type'])
                                @case('Visa')
                                    <img class="mr-1 inline-block" src="{{asset('images/visa.svg')}}" alt="Visa Accepted" width="30">
                                    @break
                                @case('Mastercard')
                                    <img src="{{asset('images/mastercard.svg')}}" alt="Mastercard Accepted" width="30">
                                    @break
                                @case('Amex')
                                    <img src="{{asset('images/amex.svg')}}" alt="American Express Accepted" width="30">
                                    @break
                                @case('Discover')
                                    <img src="{{asset('images/discover.svg')}}" alt="Discover Accepted" width="30">
                                    @break
                                @default
                             @endswitch
                          {{$details['card_type']}} ...{{$details['last_four']}}
                        </dd>
                    </div>
                </div>
                <div class="col-span-1 sm:col-span-2 px-4 py-5 border border-gray-200 rounded">
                    <dt class="text-sm font-medium text-gray-500">
                        Subscription:
                    </dt>
                        @foreach ($details['product_details'] as $item)
                            <dd class="mt-1 text-sm text-gray-900 mb-2">
                                {{$item['name']}}, Quantity {{$item['quantity']}}
                            </dd>
                        @endforeach
                    </div>
            @else
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    No order placed
                </p>
            @endif
        </div>
        <div class="px-6 pb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <a
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded text-white bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
            href="{{route('subscriptions')}}">
                See Subscriptions
            </a>
            <a
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded text-white bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
            href="{{route('billing')}}">
                Go to Billing
            </a>
        </div>

    </div>
</x-app-layout>

