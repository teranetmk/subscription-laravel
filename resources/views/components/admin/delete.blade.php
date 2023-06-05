<div
x-data="{confirmDelete: false}"
x-init="
$watch('confirmDelete', value => {
    const body = document.body;
    if(!confirmDelete) {
       body.classList.remove('h-screen');
       return body.classList.remove('overflow-hidden');
    } else {
        body.classList.add('h-screen');
        return body.classList.add('overflow-hidden');
    }
});">

    <div x-cloak x-ref="modal" x-show.transition.opacity="confirmDelete" class="fixed z-50 top-0 left-0 w-screen h-screen bg-gray-500 bg-opacity-25 flex items-center justify-center" role="dialog" aria-modal="true">

        <div @mousedown.away="confirmDelete = false" @keydown.window.escape="confirmDelete = false" class="w-full max-w-screen-sm bg-white rounded shadow-xl flex flex-col absolute divide-y divide-gray-200">

            <div class="px-5 py-4 flex items-center justify-between">
                <h2 class="text-2xl">Are You Sure?</h2>
                <button class="text-gray-400 hover:text-gray-600" @click="confirmDelete = false">
                        <svg class="w-4 fill-current transition duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001"><path d="M284.286 256.002L506.143 34.144c7.811-7.811 7.811-20.475 0-28.285-7.811-7.81-20.475-7.811-28.285 0L256 227.717 34.143 5.859c-7.811-7.811-20.475-7.811-28.285 0-7.81 7.811-7.811 20.475 0 28.285l221.857 221.857L5.858 477.859c-7.811 7.811-7.811 20.475 0 28.285a19.938 19.938 0 0014.143 5.857 19.94 19.94 0 0014.143-5.857L256 284.287l221.857 221.857c3.905 3.905 9.024 5.857 14.143 5.857s10.237-1.952 14.143-5.857c7.811-7.811 7.811-20.475 0-28.285L284.286 256.002z"/></svg>
                </button>
            </div>
            <div class="p-6">
                <h3 class="text-xl">
                    <span class="text-red-700 font-bold">
                        {{ucwords(str_replace('-', ' ', $object))}}
                    </span>
                     will be deleted.
                </h3>
                <form id="confirm-delete-{{$object}}" method="POST" action="{{route($routename, $object)}}">
                    @csrf
                    @method('DELETE')

                    <p>This action is permanent, and cannot be undone. </p>
                </form>
            </div>

            <div class="flex items-center justify-between px-5 py-4 space-x-2">
                <button
                    onclick="event.preventDefault();document.getElementById('confirm-delete-{{$object}}').submit();"
                    class="rounded flex items-center px-5 py-2 rounded-sm bg-red-600 text-white font-semibold transition duration-150 hover:bg-red-500 focus:outline-none focus:shadow-outline">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Permanently Delete
                </button>
                <button x-on:click.prevent="confirmDelete = false" class="rounded px-5 py-2 rounded-sm border border-gray-300 text-gray-600 font-semibold transition duration-150 hover:border-gray-400 hover:text-gray-900 focus:outline-none focus:shadow-outline">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    @if($size != 1)
        <span class="text-sm text-coolgray-600">Need to Remove? </span>
    @endif
    <button
    x-on:click.prevent="confirmDelete=true"
    class="{{$size == 1 ? 'text-xs' : 'text-sm'}} ml-6 inline-flex justify-center py-{{$size}} px-{{$size * 2}} border border-transparent shadow-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Delete
    </button>
</div>
