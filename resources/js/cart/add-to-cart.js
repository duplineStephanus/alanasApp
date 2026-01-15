import { postData } from '../utils/api';
import { updateCartCounter } from './cart-counter';

document.addEventListener('click', function (e) {
        //Test 1 
        console.log('addToCart function initialized');
        //Test 2
        document.addEventListener('click', function (e){
            console.log('document clicked on', e.target);
        });

        
        const btn = e.target.closest('.add-to-cart');
        if (!btn) return;


        e.preventDefault(); // REQUIRED for show.blade.php

        btn.classList.add('clicked');
        setTimeout(() => btn.classList.remove('clicked'), 200);

        let productId;
        let variantId;
        let quantity = 1;

        
        const productCard = btn.closest('.product-card');

        if (productCard) {
            // ============================
            // CASE 1: index.blade.php
            // ============================
            console.log('case 1');
            productId = btn.dataset.productId;

            const variantSelect = productCard.querySelector('select[name="size"]');
            if (!variantSelect) return;

            variantId = variantSelect.value;
        } else {
            // ============================
            // CASE 2: show.blade.php
            // ============================
            console.log('case 2');
            const form = btn.closest('form');
            if (!form) return;

            productId = form.querySelector('#product_id')?.value;
            variantId = form.querySelector('#variant_id')?.value;
            variantQuantity = form.querySelector('#quantity')?.value;

            console.log('product id: ', productId);
            console.log('variant id: ', variantId);
            console.log('quantity: ', quantity);    
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