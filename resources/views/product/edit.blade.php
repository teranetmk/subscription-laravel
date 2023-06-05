<x-app-layout>
    <form method="POST" action="{{route('product-update', $product->id)}}" class="space-y-8">
        @csrf
        @method('PATCH')

        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div>
                <div class="flex flex-col sm:flex-row justify-between">
                    <div>
                        <h3 class="text-xl leading-6 font-medium text-gray-900">
                            Update: {{$product->name}}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Update the product details
                        </p>
                    </div>
                    <div class="flex items-end mt-3 sm:mt-0">
                        <a href="{{route('products')}}" role="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update
                        </button>
                    </div>
                </div>

                <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Product Name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input type="text" name="name" id="name" value="{{ old('name') ? old('name') : $product->name }}" required class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-md sm:text-sm border-gray-300">
                            </div>
                        </div>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                           Stripe Price Identifier
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input type="text" name="stripe_price_id" id="stripe_price_id" value="{{ old('stripe_price_id') ? old('stripe_price_id') : $product->stripe_price_id }}" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-md sm:text-sm border-gray-300">
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="price" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Price
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="hidden" name="price" id="cents" value="{{ old('price') ? old('price') : $product->price }}">
                            <input type="text" name="price-display" id="price-input" value="{{ old('price-display') ? old('price-display') : $format_price }}"
                            pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                            placeholder="$100.00"
                            required
                            class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <div class="mt-10 pt-8 border-t border-gray-200 flex justify-end">
        <x-admin.delete routename="product-delete" :object="$product->id" size="2"/>
    </div>
</x-app-layout>
