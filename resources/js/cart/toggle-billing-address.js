// resources/js/cart/toggle-billing-address.js

export function toggleBillingAddress() {
    const checkbox = document.getElementById('same-as-shipping');
    const billingSection = document.querySelector('.billing-address');

    if (!checkbox || !billingSection) {
        console.warn('Billing toggle elements not found');
        return;
    }

    // Initial state
    if (checkbox.checked) {
        billingSection.classList.add('hidden');
    }

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            billingSection.classList.add('hidden');
        } else {
            billingSection.classList.remove('hidden');
        }
    });
}