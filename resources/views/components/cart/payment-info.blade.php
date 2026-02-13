<!-- Payment Information -->
<div class="payment-method border-t border-gray-200 pt-6">
    <h2 class="text-2xl font-display font-medium text-gray-900 mb-4">Payment information</h2>

    <div class="grid grid-cols-1 gap-y-6">
        
        <!-- Card Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Card type</label>
            <select name="payment[card_type]" required
                    class="checkout-fields w-full">
                <option value="">Select card type</option>
                <option value="visa">Visa</option>
                <option value="mastercard">Mastercard</option>
                <option value="amex">American Express</option>
                <option value="discover">Discover</option>
            </select>
        </div>

        <!-- Name on Card -->
        <div>
            <label for="card_name" class="block text-sm font-medium text-gray-700">Name on card</label>
            <input type="text" name="payment[card_name]" id="card_name" required
                   class="checkout-fields">
        </div>

        <!-- Card Number -->
        <div>
            <label for="card_number" class="block text-sm font-medium text-gray-700">Card number</label>
            <input type="text" name="payment[card_number]" id="card_number" 
                   maxlength="19" placeholder="4242 4242 4242 4242" required
                   class="checkout-fields">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Expiration Date -->
            <div>
                <label for="expiry" class="block text-sm font-medium text-gray-700">Expiration date (MM/YY)</label>
                <input type="text" name="payment[expiry]" id="expiry" 
                       placeholder="MM/YY" maxlength="5" required
                       class="checkout-fields">
            </div>

            <!-- CVV -->
            <div>
                <label for="cvv" class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                    Security code (CVV)
                    <button type="button" id="cvv-help-btn"
                            class="text-tamanuleaf hover:text-coastalfern text-xs underline">
                        What's this?
                    </button>
                </label>
                <input type="text" name="payment[cvv]" id="cvv" 
                       maxlength="4" placeholder="123" required
                       class="checkout-fields">
            </div>
        </div>
    </div>
</div>