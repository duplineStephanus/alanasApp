import axios from 'axios';
import { updateCartCounter } from './cart-counter'; 
let cartState = {};

document.addEventListener('DOMContentLoaded', () => {
    const cartButton = document.querySelector('.cart-btn');
    const cartList = document.querySelector('[role="list"]');
    let subtotal = 0;

    if (!cartButton || !cartList) return;

    cartButton.addEventListener('click', async () => {
        cartState = {};
        cartList.innerHTML = '';

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
                updateSubtotal(0);
                return;
            }
            console.log(items);//Test 
            subtotal = 0;

            items.forEach(item => {
                cartState[item.id] = {
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity,
                    size: item.size,
                    image: item.image,
                    stock: item.stock_quantity
                };

                renderCartItem(cartState[item.id]);
                subtotal += item.price * item.quantity;
            });

            console.log(cartState);

            updateSubtotal(subtotal);

        } catch (error) {
            console.error('Failed to load cart:', error);
        }
    });

    //Update cart on drawer close
    const drawer = document.getElementById('drawer');
    drawer.addEventListener('toggle', () => {
        if (!drawer.open) {
        
            axios.post('/cart/sync', { items: cartState })
            .then(updateCartCounter())
            .catch(err => console.error('Cart sync failed', err));
        }
        
    });

    //Remove item handler 
    cartList.addEventListener('click', async (e) => {
        if (!e.target.classList.contains('remove-item')) return;

        const itemId = e.target.dataset.itemId;
        delete cartState[itemId];

        try {
            await axios.delete(`/cart/items/${itemId}`);
            cartButton.click(); // Reload cart UI
        } catch (error) {
            console.error('Failed to remove item:', error);
        }
    });

    //Update quantity handle 
    cartList.addEventListener('click', (e) => {
        if (!e.target.classList.contains('qty-btn')) return;

        const itemId = e.target.dataset.itemId;
        const action = e.target.dataset.action;

        if (!itemId || !cartState[itemId]) return;

        const item = cartState[itemId];

        if (action === 'increment') {
            item.quantity++;
        } else if (action === 'decrement') {
            if (item.quantity > 1) item.quantity--;
        }

        updateItemUI(e.target.closest('li'), item);
        
    });

    //sync cart at checkout
    const checkoutLink = document.querySelector('a[href*="checkout"]');
    if (checkoutLink) {
        checkoutLink.addEventListener('click', () => {
            axios.post('/cart/sync', { items: cartState });
        });

        //update cart badge 
        updateCartCounter();
    }

});


//HELPER FUNCTIONS 

function updateSubtotal(amount) {
  const subtotalEl = document.getElementById('cart-subtotal');

  if (!subtotalEl) return;

  subtotalEl.textContent = `$${amount.toFixed(2)}`;
}

function renderCartItem(item) {
    const cartList = document.querySelector('[role="list"]');

    cartList.insertAdjacentHTML('beforeend', `
        <li class="flex py-6 font-body data-item-id="${item.id}">
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
                                data-action="decrement"
                                data-item-id="${item.id}"
                                data-price="${item.price}">
                                -
                            </button>

                            <input
                                type="number"
                                min="1"
                                value="${item.quantity}"
                                class="qty-input hidden"
                                data-item-id="${item.id}"
                                data-price="${item.price}" 
                            />

                            <span class="qty-display w-12 p-1 text-center font-medium bg-goldennut rounded-xl">
                                ${item.quantity}
                            </span>

                            <button 
                                class="qty-btn increment"
                                data-action="increment"
                                data-item-id="${item.id}"
                                data-price="${item.price}">
                                +
                            </button>
                        </div>
                        
                        <p class="text-${item.stock <= 11 ? 'red' : 'black'}-400 italic">${item.stock} left in stock.</p>
                    </div>
                    <button 
                        data-item-id="${item.id}"
                        class="remove-item font-medium text-tamanuleaf hover:text-coastalfern">
                        Remove
                    </button>
                </div>
            </div>
        </li>
    `);
}

function updateItemUI(li, item) {
    li.querySelector('.qty-input').value = item.quantity;
    li.querySelector('.qty-display').textContent = item.quantity;
    li.querySelector('.item-total').textContent =
        `$${(item.price * item.quantity).toFixed(2)}`;

    recalculateSubtotal();
}

function recalculateSubtotal() {
    let subtotal = 0;

    Object.values(cartState).forEach(item => {
        subtotal += item.price * item.quantity;
    });

    updateSubtotal(subtotal);
}





