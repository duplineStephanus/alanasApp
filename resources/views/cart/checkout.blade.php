<x-layout>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 xl:gap-x-16">
                
                <!-- Left - Form Section -->
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 mb-8 font-display">Checkout</h1>

                    <form action="" method="POST" class="space-y-10">
                        @csrf

                        @if (!auth()->check())
                            <!-- Contact Information -->
                            <div>
                                <h2 class="text-2xl font-display text-gray-900 mb-4">Contact information</h2>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                    <input type="email" name="email" id="email" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-tamanuleaf focus:ring-tamanuleaf sm:text-sm">
                                </div>
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        <x-cart.shipping-form></x-cart.shipping-form>

                        <!-- Billing Address -->
                        <x-cart.billing-form></x-cart.billing-form>

                        <!-- Payment Information -->
                        <div class="mt-10">
                            <button type="submit"
                                    class="w-full bg-tamanuleaf border border-transparent rounded-md py-3 px-8 text-base font-medium text-white hover:bg-coastalfern focus:outline-none focus:ring-2 focus:ring-tamanuleaf focus:ring-offset-2">
                                Complete Order
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right - Order Summary -->
                <div class="lg:mt-0">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm px-6 py-8">
                        <h2 class="text-2xl font-display text-gray-900 mb-6">Order summary</h2>

                        

                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <li class="flex py-6">
                                    <div class="flex-shrink-0 w-24 h-24 rounded-md overflow-hidden bg-gray-100">
                                        <img src="{{$item['image']}}" alt="Product" class="w-full h-full object-center object-cover">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-base font-medium text-gray-900">{{$item['name']}}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{$item['size']}}</p>
                                        <p class="mt-1 text-sm text-gray-900">× {{$item['quantity']}}</p>
                                        <p class="mt-2 text-base font-medium text-gray-900">{{$item['price']}}</p>
                                    </div>
                                </li>
                            
                            @endforeach
                            
                        </ul>

                        <dl class="mt-8 space-y-4 border-t border-gray-200 pt-6">
                            <div class="flex justify-between text-base text-gray-700">
                                <dt>Subtotal</dt>
                                <dd class="font-medium">$ {{number_format($subtotal, 2)}}</dd>
                            </div>
                            <div class="flex justify-between text-base text-gray-700">
                                <dt>Shipping & handling</dt>
                                <dd class="font-medium">$ {{number_format($shipping, 2)}}</dd>
                            </div>
                            <div class="flex justify-between text-base text-gray-700">
                                <dt>Estimated tax</dt>
                                <dd class="font-medium">$ {{number_format($tax, 2)}}</dd>
                            </div>
                            <div class="flex justify-between text-lg font-medium text-gray-900 border-t border-gray-200 pt-4">
                                <dt>Total</dt>
                                <dd>$ {{number_format($total, 2)}}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>