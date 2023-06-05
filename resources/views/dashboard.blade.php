<x-app-layout>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <h2>Dashboard</h2>
                        <div class="flow-root mt-6">
                          <ul role="list" class="-my-5 divide-y divide-gray-200">
                            <li class="py-4">
                              <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                  <p class="text-sm font-medium text-gray-900 truncate">
                                    Products
                                  </p>
                                </div>
                                <div>
                                  <a href="{{route('products')}}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    View
                                  </a>
                                </div>
                              </div>
                            </li>
                            <li class="py-4">
                              <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                  <p class="text-sm font-medium text-gray-900 truncate">
                                    Subscriptions
                                  </p>
                                </div>
                                <div>
                                  <a href="{{route('subscriptions')}}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    View
                                  </a>
                                </div>
                              </div>
                            </li>
                            <li class="py-4">
                              <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                  <p class="text-sm font-medium text-gray-900 truncate">
                                    Billing
                                  </p>
                                </div>
                                <div>
                                  <a href="{{route('billing')}}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    View
                                  </a>
                                </div>
                              </div>
                            </li>
                            <li class="py-4">
                              <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                  <p class="text-sm font-medium text-gray-900 truncate">
                                    Purchases
                                  </p>
                                </div>
                                <div>
                                  <a href="{{route('purchases')}}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    View
                                  </a>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                </div>
            </div>
   </x-app-layout>
