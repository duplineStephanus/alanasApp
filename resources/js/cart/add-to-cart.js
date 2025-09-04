import { postData } from '../utils/api';

const productsContainer = document.querySelector('#products-container');

productsContainer.addEventListener('click', function(e) {
    const btn = e.target.closest('.add-to-cart');
    if (!btn) return;

    const productCard = btn.closest('.product-card');
    const productId = btn.dataset.productId;
    const variantId = productCard.querySelector('select[name="size"]').value;

    postData('/cart/add', { product_id: productId, variant_id: variantId, quantity: 1 })
        .then(res => {
            if (res.data.success) {
                alert(res.data.message); // For testing
                console.log(res.data.cart);
            }
        })
        .catch(err => console.error(err));
});
