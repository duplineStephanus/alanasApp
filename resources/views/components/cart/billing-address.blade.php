<!-- Billing Address Form-->
<div class="billing border-t border-gray-200 pt-6">
    <div class="flex items-center mb-4">
        <input type="checkbox" name="billing_same_as_shipping" id="same-as-shipping" checked
            class="h-4 w-4 border-gray-300 rounded text-tamanuleaf focus:ring-tamanuleaf">
        <label for="same-as-shipping" class="ml-2 text-sm text-gray-700">
            Billing address is the same as shipping address
        </label>
    </div>

    <!-- Billing Address -->
    <div class="billing-address mt-2 hidden">
        <h2 class="text-2xl font-display font-medium text-gray-900 mb-4">Billing address</h2>
        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                <select name="billing[country]" id="country" required
                        class="checkout-fields">
                    <option value="US">United States</option>
                    <option value="US">Guam</option>
                    <option value="US">Palau</option>
                    <!-- Add more countries as needed -->
                </select>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="font-light italic">(optional)</span></label>
                <input type="text" name="billing[phone]" id="phone" required
                    class="checkout-fields">
            </div>
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                <input type="text" name="billing[first_name]" id="first_name" required
                    class="checkout-fields">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                <input type="text" name="billing[last_name]" id="last_name" required
                    class="checkout-fields">
            </div>

            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="billing[address]" id="address" required
                    class="checkout-fields">
            </div>
            <div class="sm:col-span-2 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="billing[city]" id="city" required
                        class="checkout-fields">
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                    <input type="text" name="billing[state]" id="state" required
                        class="checkout-fields">
                </div>

                <div>
                    <label for="zip" class="block text-sm font-medium text-gray-700">ZIP / Postal code</label>
                    <input type="text" name="billing[zip]" id="zip" required
                        class="checkout-fields">
                </div>
            </div>
        </div>
    </div>
</div>