const productCards = document.querySelectorAll('.product-card');

productCards.forEach(card => {
    const select = card.querySelector('select[name="size"]');
    select.addEventListener('change', function() {
        const selectedOption = select.options[select.selectedIndex];
        card.querySelector('.price').textContent = selectedOption.dataset.price;
        card.querySelector('img').src = selectedOption.dataset.image;
    });
});
