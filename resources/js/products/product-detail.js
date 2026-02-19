export function toggleProductDetail() {
    const select = document.getElementById('variant-select');

    if (!select) return;

    const priceDisplay = document.getElementById('price-display');
    const productImage = document.getElementById('product-image').querySelector('img');
    const stockInfo = document.getElementById('stock-info');
    const stockNote = document.getElementById('stock-note');
    const quantityInput = document.getElementById('quantity');
    const addButton = document.getElementById('product-details-add-to-cart');
    const variantIdInput = document.getElementById('variant-select');

    // Extract update logic into a function
    function updateProductDisplay(option) {
        const price = option.getAttribute('data-price');
        const image = option.getAttribute('data-image');
        const stock = parseInt(option.getAttribute('data-stock'));

        priceDisplay.textContent = `$${parseFloat(price).toFixed(2)}`;
        productImage.src = image;

        if (stock > 0) {
            stockInfo.textContent = '';
            stockNote.textContent = `${stock} left in stock`;
            if (stock <= 10) {
                stockNote.classList.add('text-red-400');
            } else {
                stockNote.classList.remove('text-red-400');
            }
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

    //implement quantity input boundries 
    quantityInput.addEventListener('input', function() {
       const value = parseInt(this.value);
       const max = parseInt(this.max);
       const min = parseInt(this.min || 1);
       
       if (value > max) {
           this.value = max;
       } else if (value < min) {
           this.value = min;
       }    
    });

    // Run on page load for the currently selected option
    updateProductDisplay(select.options[select.selectedIndex]);

    // Also run whenever the user changes the dropdown
    select.addEventListener('change', function() {
        updateProductDisplay(this.options[this.selectedIndex]);
    });
}