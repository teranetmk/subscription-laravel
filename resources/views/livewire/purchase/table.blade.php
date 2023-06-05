<div>
    <div class="mt-4">
        <div class="mb-1 text-xs">
            Search (search by: amount)
        </div>
        <div class="relative" style="max-width: 414px;">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <input
                wire:model.debounce.200ms="search"
                id="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-blue-300 focus:shadow-outline-blue sm:text-sm transition duration-150 ease-in-out"
                placeholder="Search"
                type="text"
                autocomplete="off">
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md mt-4">
            <ul class="divide-y divide-gray-200">
                @forelse ($purchases as $purchase)
                    <a href="{{route('purchase-show', $purchase->transaction_id)}}" class="block text-gray-800 hover:bg-indigo-50">
                        <li class="grid grid-cols-2 gap-2 px-4 py-3 items-center justify-between text-sm">
                            <span class="col-span-1">
                                <div class="text-sm">Transaction Id: {{substr($purchase->transaction_id, 3)}}</div>
                                <div class="text-xs text-gray-500"> Sent on: {{formatDate($purchase->purchase_date)}}</div>
                            </span>
                            <span class="justify-self-center capitalize">
                                {{dollars($purchase->amount)}}<br>
                            </span>
                        </li>
                    </a>
                @empty
                    <li class="p-4">
                        No purchase invoices
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="mt-4 px-2">
            {{$purchases->links()}}
        </div>
    </div>
</div>

