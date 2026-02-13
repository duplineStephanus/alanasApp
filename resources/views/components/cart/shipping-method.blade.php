<!-- Shipping Method -->
<div class="shipping-method border-t border-gray-200 pt-6">
    <h2 class="text-2xl font-display font-medium text-gray-900 mb-4">Shipping method</h2>
    
    <div class="grid grid-cols-1 gap-4">
        
        <!-- Standard Shipping -->
        <label class="shipping-option cursor-pointer">
            <input type="radio" name="shipping_method" value="standard" checked 
                   class="peer hidden" data-price="12.00">
            <div class="border-2 border-transparent peer-checked:border-tamanuleaf peer-checked:bg-coastalfern/20 rounded-xl p-3 hover:border-gray-300 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">Standard Shipping</p>
                        <p class="text-sm text-gray-500 mt-1">Delivery in 14–21 business days</p>
                    </div>
                    <p class="font-semibold text-gray-900">$12.00</p>
                </div>
            </div>
        </label>

        <!-- Express Shipping -->
        <label class="shipping-option cursor-pointer">
            <input type="radio" name="shipping_method" value="express" 
                   class="peer hidden" data-price="30.00">
            <div class="border-2 border-transparent peer-checked:border-tamanuleaf  peer-checked:bg-sandyshore/30 rounded-xl p-3 hover:border-gray-300 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">Express Shipping</p>
                        <p class="text-sm text-gray-500 mt-1">Delivery in 7–14 business days</p>
                    </div>
                    <p class="font-semibold text-gray-900">$30.00</p>
                </div>
            </div>
        </label>
    </div>
</div>