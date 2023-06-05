<x-app-layout>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-coolgray-400 sm:text-3xl sm:truncate">
                Products
            </h2>
        </div>
        <div class="mt-4 md:mt-0 md:ml-4">
            <a href="{{route('product-create')}}" role="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                New Product
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-coolgray-200">
            @forelse ($products as $product)
                <li>
                    <div class="block sm:flex justify-between items-start px-4 py-6 sm:px-6">
                        <div class="flex-1">
                            <a href="{{route('product-edit', $product->id)}}" class="hover:opacity-50" title="Edit the listing">
                                <h2 class="text-xl font-bold text-indigo-800 pb-4 truncate border-b border-gray-100">
                                    {{$product->name}}
                                    <br>
                                    <small class="text-xs text-gray-600 font-normal mr-2">
                                        Stripe Price ID:
                                        {{$product->stripe_price_id}}
                                    </small>
                                </h2>
                                <div class="">
                                    <div class="bg-coolgray-50 rounded p-4">
                                        <p class="text-lg text-coolgray-700 capitalize">
                                            <span class="font-bold">Price:</span> {{dollars($product->price)}} / month
                                        </p>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3 mt-3 text-right">
                                        <p class="text-xs text-coolgray-700">
                                            Last Modified on
                                            <time>{{formatDate($product->updated_at)}}</time>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-4">No products entered yet</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
