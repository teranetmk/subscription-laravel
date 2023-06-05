<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-coolgray-400 sm:text-3xl sm:truncate">
                Payment Detail
            </h2>
        </div>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-4 p-8 text-sm text-gray-700">
        <div class="text-xl font-bold">
            Transaction Id: {{substr($purchase->transaction_id, 3)}}
        </div>
        <div class="text-sm">
            Date Paid: {{formatDate($purchase->purchase_date)}}
        </div>
        <div class="text-sm">
            Amount: {{dollars($purchase->amount)}}
        </div>

        <div class="pb-8">
            <div class="border-b border-gray-200 mb-3 pb-3">
                Card Details:
            </div>
            <div>
                Type: {{$purchase->card_type}}
            </div>
            <div>
                Last Four: {{$purchase->last_four}}
            </div>
        </div>
    </div>
</x-app-layout>
