import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const cartButton = document.querySelector('.cart-btn');
    const cartList = document.querySelector('[role="list"]');
    let subtotal = 0;

    if (!cartButton || !cartList) return;

    cartButton.addEventListener('click', async () => {
        try {
            const response = await axios.get('/cart');
            const items = response.data.items;

            cartList.innerHTML = '';

            if (items.length === 0) {
                cartList.innerHTML = `
                    <li class="py-6 text-center text-gray-500">
                        Your cart is empty.
                    </li>
                `;
                return;
            }
            console.log(items);//Test 

            subtotal = 0;  

            items.forEach(item => {
                cartList.insertAdjacentHTML('beforeend', `
                    <li class="flex py-6 font-body">
                        <div class="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                            <img src="${item.image }" class="size-full object-cover" />
                        </div>

                        <div class="ml-4 flex flex-1 flex-col">
                            <div>
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <h3 class="font-display text-xl">${item.name}</h3>
                                    <p class="item-total" data-id="${item.id}">
                                        $${(item.price * item.quantity).toFixed(2)}
                                    </p>
                                </div>
                                <p class="mt-1 text-sm text-tamanuleaf">
                                   $${item.price} | ${item.size}
                                </p>
                            </div>
                            <div class="flex flex-1 items-end justify-between text-sm">
                                <div class="flex gap-4">
                                    <div class="flex items-center gap-2">
                                        <button 
                                            class="qty-btn decrement"
                                            data-id="${item.id}"
                                            data-price="${item.price}">
                                            -
                                        </button>

                                        <input
                                            type="number"
                                            min="1"
                                            value="${item.quantity}"
                                            class="qty-input w-14 text-center border rounded"
                                            data-id="${item.id}"
                                            data-price="${item.price}" />

                                        <button 
                                            class="qty-btn increment"
                                            data-id="${item.id}"
                                            data-price="${item.price}">
                                            +
                                        </button>
                                    </div>
                                    <p class="text-red-400 italic">${item.stock_quantity} left in stock.</p>
                                </div>
                                <button 
                                    data-id="${item.id}"
                                    class="font-medium text-tamanuleaf hover:text-coastalfern remove-item">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </li>
                `);

                //calculate subtotal
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;  
            });

            updateSubtotal(subtotal);

        } catch (error) {
            console.error('Failed to load cart:', error);
        }
    });

    //remove items
    cartList.addEventListener('click', async (e) => {
        if (!e.target.classList.contains('remove-item')) return;

        const itemId = e.target.dataset.id;

        try {
            await axios.delete(`/cart/items/${itemId}`);

            // Reload cart UI
            cartButton.click();
            console.log('Item removed from cart');

        } catch (error) {
            console.error('Failed to remove item:', error);
        }
    });

    //update quantity 
    cartList.addEventListener('click', async (e) => {
        if (!e.target.classList.contains('qty-btn')) return;

        const isIncrement = e.target.classList.contains('increment');
        const isDecrement = e.target.classList.contains('decrement');

        if (!isIncrement && !isDecrement) return;

        const itemId = e.target.dataset.id;
        const price = parseFloat(e.target.dataset.price);

        const input = cartList.querySelector(`.qty-input[data-id="${itemId}"]`);
        let quantity = parseInt(input.value);

        quantity = isIncrement ? quantity + 1 : quantity - 1;
        if (quantity < 1) return;

        input.value = quantity;

        // Update item total UI
        const itemTotalEl = cartList.querySelector(`.item-total[data-id="${itemId}"]`);
        itemTotalEl.textContent = `$${(price * quantity).toFixed(2)}`;

        // Recalculate subtotal
        recalculateSubtotal();

        // Persist to backend
        try {
            await axios.patch(`/cart/items/${itemId}`, {
                quantity: quantity
            });
        } catch (error) {
            console.error('Failed to update quantity', error);
        }
    });

});

function updateSubtotal(amount) {
  const subtotalEl = document.getElementById('cart-subtotal');

  if (!subtotalEl) return;

  subtotalEl.textContent = `$${amount.toFixed(2)}`;
}

function recalculateSubtotal() {
    let newSubtotal = 0;

    document.querySelectorAll('.qty-input').forEach(input => {
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value);
        newSubtotal += price * quantity;
    });

    updateSubtotal(newSubtotal);
}


