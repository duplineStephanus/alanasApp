// Listen on the container that holds all products
const productsContainer = document.querySelector('#products-container');

productsContainer.addEventListener('click', function(e) {
    // Check if the clicked element (or one of its parents) has 'add-to-cart' class
    const btn = e.target.closest('.add-to-cart');
    if (!btn) return; // Ignore clicks outside buttons

    const productId = btn.dataset.productId;
    const variantId = btn.dataset.variantId;

    // Make AJAX request to add to cart
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, variant_id: variantId })
    })
    .then(res => res.json())
    .then(data => {
        console.log(data.cart);
        alert(data.message); // Optional: replace with a nicer UI notification
    });
});

