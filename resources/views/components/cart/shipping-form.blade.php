<!-- Shipping Address -->
<div class="shipping-address">
    <h2 class="text-2xl font-display font-medium text-gray-900 mb-4">Shipping address</h2>
    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
            <select name="shipping[country]" id="country" required
                    class="checkout-fields">
                <option value="US">United States</option>
                <option value="US">FSM</option>
                <option value="US">Guam</option>
                <option value="US">Marshall Islands</option>
                <option value="US">Palau</option>
                <option value="US">Philippines</option>
                <option value="US">Saipan</option>
                <!-- Add more countries as needed -->
            </select>
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" name="shipping[phone]" id="phone" required
                class="checkout-fields">
        </div>
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
            <input type="text" name="shipping[first_name]" id="first_name" required
                class="checkout-fields">
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
            <input type="text" name="shipping[last_name]" id="last_name" required
                class="checkout-fields">
        </div>

        <div class="sm:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" name="shipping[address]" id="address" required
                class="checkout-fields">
        </div>
        <div class="sm:col-span-2 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" name="shipping[city]" id="city" required
                    class="checkout-fields">
            </div>

            <div>
                <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                <input type="text" name="shipping[state]" id="state" required
                    class="checkout-fields">
            </div>

            <div>
                <label for="zip" class="block text-sm font-medium text-gray-700">ZIP / Postal code</label>
                <input type="text" name="shipping[zip]" id="zip" required
                    class="checkout-fields">
            </div>
        </div>
    </div>
</div>