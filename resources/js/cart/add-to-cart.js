import { postData } from '../utils/api';
import { updateCartCounter } from './cart-counter';

export function addToCart () {
    const productsContainer = document.querySelector('#products-container');

    productsContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart');

        if (!btn) return;

        btn.classList.add('clicked');

        setTimeout(() => {
            btn.classList.remove('clicked');
        }, 200);

        const productCard = btn.closest('.product-card');
        const productId = btn.dataset.productId;
        const variantId = productCard.querySelector('select[name="size"]').value;

        postData('/cart/add', { product_id: productId, variant_id: variantId, quantity: 1 })
            .then(res => {
                if (res.data.success) {
                    console.log(res.data.message); // For testing
                    console.log(res.data.cart);
                    updateCartCounter(); 
                }
            })
            .catch(err => console.error(err));
        });
    }
