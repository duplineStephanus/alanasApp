<div id="cart" class="ml-4 flow-root lg:ml-6 relative mr-3">
    <button 
    command="show-modal" 
    commandfor="drawer" 
    class="cart-btn group -m-2 flex items-center p-2 relative">
    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="43" class="h-6 w-6 text-gray-400 hover:text-tamanuleaf" viewBox="0 0 38 43"><path id="Subtraction_2" data-name="Subtraction 2" d="M35.487,43H2.513A2.372,2.372,0,0,1,0,40.807L3.768,16.678a2.373,2.373,0,0,1,2.513-2.194H9.735v-4.9a9.579,9.579,0,1,1,19.157,0v4.9h2.826a2.372,2.372,0,0,1,2.513,2.194L38,40.807A2.372,2.372,0,0,1,35.487,43ZM19.314,2.575A6.131,6.131,0,0,0,13.19,8.7v5.785H25.438V8.7A6.131,6.131,0,0,0,19.314,2.575Z" fill="currentColor"/></svg>

    <!-- Badge -->
        <span class="cart-counter absolute -top-1 -right-1 bg-red-400 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
        @php
            $cartCount = 0;

            if (auth()->check()) {
                $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
            } else {
                $guestToken = request()->cookie('cart_token');
                $cart = \App\Models\Cart::where('guest_token', $guestToken)->first();
            }
            if ($cart) {
                $cartCount = $cart->items()->sum('quantity');
            }
        @endphp
        {{$cartCount}}
        </span>
        <span class="sr-only">Items in cart, view bag</span>
    </button>


    <el-dialog>
    <dialog id="drawer" aria-labelledby="drawer-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
        <el-dialog-backdrop class="absolute inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

        <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
        <el-dialog-panel class="ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
            <div class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                <div class="flex items-start justify-between">
                <h2 id="drawer-title" class="text-3xl font-medium text-gray-900 font-display">Shopping cart</h2>
                <div class="ml-3 flex h-7 items-center">
                    <button type="button" command="close" commandfor="drawer" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Close panel</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    </button>
                </div>
                </div>

                <div class="mt-8">
                <div class="flow-root">
                    <ul role="list" class="-my-6 divide-y divide-gray-200">
                    {{-- cart items dynamically added by cart/view-cart.js --}}
                    </ul>
                </div>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                <div class="flex justify-between text-base font-medium text-gray-900">
                <p>Subtotal</p>
                <p id="cart-subtotal"></p>
                </div>
                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                <div class="mt-6">
                <a href="{{route('cart.checkout')}}" class="flex items-center justify-center rounded-md border border-transparent bg-tamanuleaf px-6 py-3 text-base font-medium text-white shadow-xs hover:bg-coastalfern">Checkout</a>
                </div>
                <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                <p>
                    or
                    <button type="button" command="close" commandfor="drawer" class="font-medium text-tamanuleaf hover:text-coastalfern">
                    Continue Shopping
                    <span aria-hidden="true"> &rarr;</span>
                    </button>
                </p>
                </div>
            </div>
            </div>
        </el-dialog-panel>
        </div>
    </dialog>
    </el-dialog>

</div>