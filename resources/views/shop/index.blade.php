<x-layout>
    <div class="container mx-auto px-4 py-8 text-coconuthusk">
        <div class="text-center mb-8">
            <h1 class="font-display text-4xl mt-1">Shop Our Products</h1>
        </div>
        
        <div id="products-container" class="flex flex-wrap justify-center gap-6">

            <!-- Loop Products -->
            @foreach($products as $product)
                <div class="product-card bg-white shadow-md rounded-2xl overflow-hidden w-100" data-product-id="{{ $product->id }}">
                    <!-- Product Image -->
                    <div id="product-img-{{ $product->id }}">
                        <img src="{{ $product->variants->first()->image_url }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-100 object-cover">
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Name -->
                        <h2 class="text-2xl font-semibold text-gray-800 font-display hover:text-coastalfern">
                            {{ $product->name }}
                        </h2>

                        <!-- Description -->
                        <p class="text-gray-600 mt-2 text-sm font-body font-light">
                            {{ $product->description }}
                        </p>

                        <a href="{{ route('products.show', $product) }}" class="text-coastalfern hover:underline font-body">View Details</a>

                        <!-- Variant Selector -->
                        <div class="flex items-center align-middle gap-6 mt-3"> 
                            <select name="size" 
                                    class="variant-select bg-coastalfern/35 rounded-md p-1 font-body font-light text-sm"
                                    data-product-id="{{ $product->id }}">
                                @foreach($product->variants as $variant)
                                    @if ($variant->stock_quantity > 0)
                                        <option value="{{ $variant->id }}"
                                            data-price="{{ $variant->price }}"
                                            data-image="{{ $variant->image_url }}"
                                            data-stock="{{ $variant->stock_quantity }}"
                                            >
                                            {{ $variant->size }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <!-- Stock display next to select -->
                            <p class="text-sm mt-1 stock-display text-black-400" id="stock-{{ $product->id }}">
                                {{ $product->variants->first()->stock_quantity }} in stock
                            </p>
                        </div>

                        <!-- Price + Cart -->
                        <div class="flex justify-between items-center mt-3">
                            <p id="price-{{ $product->id }}" class="price font-semibold text-lg">
                                ${{ number_format($product->variants->first()->price, 2) }}
                            </p>
                            <button 
                                type="button" 
                                class="add-to-cart"
                                data-product-id="{{$product->id}}"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="39" height="44" viewBox="0 0 39 44"><path id="Subtraction_3" data-name="Subtraction 3" d="M35.487,43H2.513A2.372,2.372,0,0,1,0,40.807L3.768,16.678a2.373,2.373,0,0,1,2.513-2.194H9.735v-4.9a9.579,9.579,0,1,1,19.157,0v4.9h2.826a2.372,2.372,0,0,1,2.513,2.194L38,40.807A2.372,2.372,0,0,1,35.487,43ZM19.314,2.575A6.131,6.131,0,0,0,13.19,8.7v5.785H25.438V8.7A6.131,6.131,0,0,0,19.314,2.575Z" fill="#f7c758"/><text id="_" data-name="+" transform="translate(8 3)" fill="#1f3a25" font-size="35" font-family="'\.AppleSystemUIFont'"><tspan x="0" y="34">+</tspan></text></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>
</x-layout>