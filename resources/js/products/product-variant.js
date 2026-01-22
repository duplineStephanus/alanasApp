const productCards = document.querySelectorAll('.product-card');

productCards.forEach(card => {
    const select = card.querySelector('select[name="size"]');
    const stockDisplay = card.querySelector('.stock-display');

    if (!select || !stockDisplay) return;

    select.addEventListener('change', function() {
        const selectedOption = select.options[select.selectedIndex];
        //update price 
        card.querySelector('.price').textContent = selectedOption.dataset.price;
        //update image
        card.querySelector('img').src = selectedOption.dataset.image;
        //update stock 
        const stock = selectedOption.dataset.stock || 0;
        stockDisplay.textContent = `${stock} left in stock`;
        if(stock <= 10) {
            stockDisplay.classList.add('text-red-400');
        } else {
            stockDisplay.classList.remove('text-red-400');
        }

    });
});


