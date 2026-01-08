<x-layout>

    <div class="container mx-auto px-4 py-8">

        <h1 class="text-2xl font-semibold mb-6">Shopping Cart</h1>

        @if(count($cartItems) === 0)
            <p>Your cart is empty.</p>
        @else

        <table class="w-full border-collapse mb-6">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Product</th>
                    <th class="text-left py-2">Details</th>
                    <th class="text-right py-2">Price</th>
                    <th class="text-center py-2">Quantity</th>
                    <th class="text-right py-2">Total</th>
                    <th class="text-center py-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @php $subtotal = 0; @endphp

                @foreach($cartItems as $item)
                    @php
                        $itemTotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemTotal;
                    @endphp

                    <tr class="border-b">
                        <td class="py-4">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover">
                        </td>

                        <td class="py-4">
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-sm text-gray-600">
                                Size: {{ $item['size'] }} <br>
                                Variant: {{ $item['variant'] }}
                            </div>
                        </td>

                        <td class="py-4 text-right">
                            ${{ number_format($item['price'], 2) }}
                        </td>

                        <td class="py-4 text-center">
                            <form>
                            {{-- <form action="{{ route('cart.update', $item['id']) }}" method="POST"> --}}
                                @csrf
                                @method('PATCH')

                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ $item['quantity'] }}"
                                    min="1"
                                    class="w-16 text-center border rounded"
                                >
                            </form>
                        </td>

                        <td class="py-4 text-right">
                            ${{ number_format($itemTotal, 2) }}
                        </td>

                        <td class="py-4 text-center">
                            <form>
                            {{-- <form action="{{ route('cart.remove', $item['id']) }}" method="POST"> --}}
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:underline">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $shipping = 0;
            $total = $subtotal + $shipping;
        @endphp

        <div class="max-w-md ml-auto border p-4 rounded">
            <div class="flex justify-between mb-2">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>

            <div class="flex justify-between mb-2">
                <span>Estimated Shipping</span>
                <span>${{ number_format($shipping, 2) }}</span>
            </div>

            <div class="flex justify-between font-semibold text-lg border-t pt-2">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>

            <a
                href="#"
                {{-- href="{{ route('checkout.index') }}" --}}
                class="block text-center mt-4 bg-black text-white py-2 rounded"
            >
                Proceed to Checkout
            </a>
        </div>

        @endif

    </div>


</x-layout>