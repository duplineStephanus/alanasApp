import { postData } from '../utils/api';
import { updateCartCounter } from './cart-counter';

document.addEventListener('click', function (e) {
      
        const btn = e.target.closest('.add-to-cart');
        if (!btn) return;

        //test 2 
        console.log('btn found', btn);
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
            console.log('case 2: Show Page');
            
            const variantSelect = document.getElementById('variant-select');
            const quantityInput = document.getElementById('quantity');

            if (variantSelect) {
                // Get the actual option element currently chosen
                const selectedOption = variantSelect.options[variantSelect.selectedIndex];

                variantId = variantSelect.value; 
                productId = selectedOption.dataset.productId;
                quantity = quantityInput ? quantityInput.value : 1;

                console.log('product id from data attr: ', productId);
                console.log('variant id from value: ', variantId);
                console.log('quantity: ', quantity);
         }
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