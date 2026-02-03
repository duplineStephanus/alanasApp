<x-layout>
    <!-- SHOP SHOW BLADE -->
    <div class="single-product-container mx-auto px-4 py-8 text-coconuthusk">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Image -->
            <div id="product-image">
                <img src="{{ $product->variants->first()->image_url }}" alt="{{ $product->name }}" class="w-full h- object-cover rounded-2xl">
            </div>
            
            <!-- Details -->
            <div>
                <h1 class="text-3xl font-bold font-display">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-2 text-sm font-body font-light">{{ $product->description }}</p>
                
                <div class="flex justify-start space-x-8">
                    <!-- Variant Dropdown -->
                    <div class="mt-4">
                        <label class="block font-semibold">Select Size</label>
                        <select id="variant-select" class="h-10 w-full bg-coastalfern/35 rounded-md p-2 font-body font-light text-sm mt-1">
                            @foreach($product->variants as $variant)
                            @if($variant->stock_quantity > 0)
                                <option value="{{ $variant->id }}"
                                    data-product-id = "{{ $variant->product_id }}"
                                    data-price="{{ $variant->price }}"
                                    data-image="{{ $variant->image_url }}"
                                    data-stock="{{ $variant->stock_quantity }}"
                                    data-size="{{ $variant->size }}"
                                    data-type="{{ $variant->type }}">
                                    <p>{{ $variant->size }}</p>
                                </option>
                            @endif
                            @endforeach
                        </select>
                        <p id="stock-info" class="text-sm text-gray-500 mt-1"></p>
                    </div>

                    <!-- Quantity -->
                    <div class="mt-4">
                        <label 
                            class="block font-semibold">
                            Quantity
                        </label>
                        <div class="flex items-center">
                            <input 
                                type="number" 
                                id="quantity" 
                                value="1" 
                                min="1" 
                                max="{{ $product->variants->first()->stock_quantity }}"
                                class=" h-10 w-20 bg-coastalfern/35 rounded-md p-2 font-body font-light"
                            >
                            <span 
                                id="stock-note" 
                                class="ml-2 text-sm">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Total Price -->
                <div class="mt-2">
                    <p id="price-display" class="text-2xl font-semibold">${{ number_format($product->variants->first()->price, 2) }}</p>
                </div>
                
                <!-- Add to Cart -->
                <div class="mt-4">
                    <button id="product-details-add-to-cart" type="button" class="add-to-cart mt-4 w-full bg-coastalfern text-white py-3 rounded-md font-semibold">Add to Cart</button>
                </div>
                
                <!-- Benefits -->
                <div class="mt-6">
                    <h3 class="font-semibold">Benefits</h3>
                    <ul class="list-disc ml-4 text-sm font-body font-light">
                        <li>100% pure, cold-pressed Tamanu Oil</li>
                        <li>Organic, sun-dried for maximum potency</li>
                        <li>Supports skin healing and rejuvenation</li>
                    </ul>
                    <h3 class="font-semibold mt-4">Ingredients</h3>
                    <p class="text-sm font-body font-light">100% Pure Tamanu Oil (Calophyllum inophyllum)</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>