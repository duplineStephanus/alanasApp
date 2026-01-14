import { postData } from '../utils/api';
import { updateCartCounter } from './cart-counter';

export function addToCart() {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-to-cart');
        if (!btn) return;

        e.preventDefault(); // REQUIRED for show.blade.php

        btn.classList.add('clicked');
        setTimeout(() => btn.classList.remove('clicked'), 200);

        let productId;
        let variantId;
        let quantity = 1;

        // ============================
        // CASE 1: index.blade.php
        // ============================
        const productCard = btn.closest('.product-card');

        if (productCard) {
            productId = btn.dataset.productId;

            const variantSelect = productCard.querySelector('select[name="size"]');
            if (!variantSelect) return;

            variantId = variantSelect.value;
        }

        // ============================
        // CASE 2: show.blade.php
        // ============================
        else {
            const form = btn.closest('form');
            if (!form) return;

            productId = form.querySelector('#product_id')?.value;
            variantId = form.querySelector('#variant_id')?.value;
            quantity = form.querySelector('#quantity')?.value ?? 1;
        }

        // Safety check
        if (!productId || !variantId) {
            console.error('Missing product or variant ID');
            return;
        }

        postData('/cart/add', {
            product_id: productId,
            variant_id: variantId,
            quantity: quantity
        })
        .then(res => {
            if (res.data.success) {
                updateCartCounter();
            }
        })
        .catch(err => console.error(err));
    });
}
