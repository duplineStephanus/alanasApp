document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('variant-select');
    if (!select) return;

    const priceDisplay = document.getElementById('price-display');
    const productImage = document.getElementById('product-image').querySelector('img');
    const stockInfo = document.getElementById('stock-info');
    const stockNote = document.getElementById('stock-note');
    const quantityInput = document.getElementById('quantity');
    const addButton = document.querySelector('#add-to-cart-form button');
    const variantIdInput = document.getElementById('variant_id');

    // Extract update logic into a function
    function updateProductDisplay(option) {
        const price = option.dataset.price;
        const image = option.dataset.image;
        const stock = parseInt(option.dataset.stock);

        priceDisplay.textContent = `$${parseFloat(price).toFixed(2)}`;
        productImage.src = image;

        if (stock > 0) {
            stockInfo.textContent = '';
            stockNote.textContent = `${stock} left in stock`;
            quantityInput.max = stock;
            quantityInput.disabled = false;
            addButton.disabled = false;
        } else {
            stockInfo.textContent = 'Out of Stock';
            stockNote.textContent = '';
            quantityInput.max = 1;
            quantityInput.disabled = true;
            addButton.disabled = true;
        }

        variantIdInput.value = option.value;
    }

    // Run on page load for the currently selected option
    updateProductDisplay(select.options[select.selectedIndex]);

    // Also run whenever the user changes the dropdown
    select.addEventListener('change', function() {
        updateProductDisplay(this.options[this.selectedIndex]);
    });
});
