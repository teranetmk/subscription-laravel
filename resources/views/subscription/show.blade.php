<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-400 sm:text-3xl sm:truncate">
                Subscriptions
            </h2>
        </div>
        <div class="mt-4 md:mt-0 md:ml-4">
            <a href="{{route('order-form')}}" role="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Subscribe
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse ($subscriptions as $subscription)
                <li>
                    <div class="block sm:flex justify-between items-start px-4 py-6 sm:px-6">
                        <div class="flex-1">
                            <div>
                                <div class="text-xl font-bold text-indigo-800 pb-4 truncate border-b border-gray-100">
                                    <div>
                                        {{$subscription->name}}
                                    </div>
                                    <div class="text-xs text-gray-600 font-normal mr-2">
                                        Stripe Subscription ID:
                                        {{$subscription->stripe_id}}
                                    </div>
                                    <div class="text-xs text-gray-600 font-normal mr-2">
                                        Status:
                                        {{$subscription->stripe_status}}
                                    </div>
                                </div>
                                <div class="my-2 py-2 grid grid-cols-2 border-b border-gray-100 pb-4">
                                    <div class="text-left text-xs text-gray-700">
                                        <p>
                                            Quantity: {{$subscription->quantity}}
                                        </p>
                                        <p>
                                            Product Price: {{dollars($subscription->product_price)}} / month
                                        </p>
                                        <p>
                                            Total Recurring: {{dollars($subscription->product_total_price)}} / month
                                        </p>
                                    </div>
                                    <div class="text-right text-xs text-gray-700">
                                        <p>
                                            Created on
                                            <time>{{formatDate($subscription->created_at)}}</time>
                                        </p>
                                        <p>
                                            Last Charge:
                                            <time>{{formatDate($subscription->last_charge_date)}}</time>
                                        </p>
                                         <p>
                                            Next Charge:
                                            <time>{{formatDate($subscription->next_charge_date)}}</time>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-3">
                                    <span>
                                        @include('subscription.increase-quantity')
                                    </span>
                                    <span>
                                        @include('subscription.confirm-cancel')
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-4">No subscriptions yet</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
