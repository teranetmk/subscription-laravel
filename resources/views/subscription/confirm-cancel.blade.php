<div
x-data="{confirmCancel: false}"
x-init="
$watch('confirmCancel', value => {
    const body = document.body;
    if(!confirmCancel) {
    body.classList.remove('h-screen');
    return body.classList.remove('overflow-hidden');
    } else {
        body.classList.add('h-screen');
        return body.classList.add('overflow-hidden');
    }
});">

    <div x-cloak x-ref="modal" x-show.transition.opacity="confirmCancel" class="fixed z-50 top-0 left-0 w-screen h-screen bg-gray-500 bg-opacity-25 flex items-center justify-center" role="dialog" aria-modal="true">

        <div @mousedown.away="confirmCancel = false" @keydown.window.escape="confirmCancel = false" class="w-full max-w-screen-sm bg-white rounded shadow-xl flex flex-col absolute divide-y divide-gray-200">

            <div class="px-5 py-4 flex items-center justify-between">
                <h2 class="text-2xl">Are You Sure?</h2>
                <button class="text-gray-400 hover:text-gray-600" @click="confirmCancel = false">
                        <svg class="w-4 fill-current transition duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001"><path d="M284.286 256.002L506.143 34.144c7.811-7.811 7.811-20.475 0-28.285-7.811-7.81-20.475-7.811-28.285 0L256 227.717 34.143 5.859c-7.811-7.811-20.475-7.811-28.285 0-7.81 7.811-7.811 20.475 0 28.285l221.857 221.857L5.858 477.859c-7.811 7.811-7.811 20.475 0 28.285a19.938 19.938 0 0014.143 5.857 19.94 19.94 0 0014.143-5.857L256 284.287l221.857 221.857c3.905 3.905 9.024 5.857 14.143 5.857s10.237-1.952 14.143-5.857c7.811-7.811 7.811-20.475 0-28.285L284.286 256.002z"/></svg>
                </button>
            </div>
            <div class="p-6">
                <h3 class="text-xl">
                    This subscription will be cancelled.
                </h3>
                <form id="form-{{$subscription->id}}" method="POST" action="{{route('subscription-cancel')}}">
                    @csrf
                    <input type="hidden" name="stripe_id" value="{{$subscription->stripe_id}}">
                    <p>This action is immediate, you will no longer be billed. </p>
                </form>
            </div>

            <div class="flex items-center justify-between px-5 py-4 space-x-2">
                <button
                    onclick="event.preventDefault();document.getElementById('form-{{$subscription->id}}').submit();"
                    class="rounded flex items-center px-5 py-2 rounded-sm bg-red-600 text-white font-semibold transition duration-150 hover:bg-red-500 focus:outline-none focus:shadow-outline">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Yes, Cancel Subscription
                </button>
                <button x-on:click.prevent.stop="confirmCancel = false" class="rounded px-5 py-2 rounded-sm border border-gray-300 text-gray-600 font-semibold transition duration-150 hover:border-gray-400 hover:text-gray-900 focus:outline-none focus:shadow-outline">
                    No, Keep It
                </button>
            </div>
        </div>
    </div>
    <button x-on:click.prevent.stop="confirmCancel=true" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Cancel Subscription
    </button>
</div>
