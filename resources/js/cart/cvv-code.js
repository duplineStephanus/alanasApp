document.addEventListener('DOMContentLoaded', () => {
    const helpBtn = document.getElementById('cvv-help-btn');
    if (!helpBtn) return;

    // Create modal/tooltip element once
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden';
    modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 relative overflow-hidden">
            <!-- Close button -->
            <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl leading-none">
                &times;
            </button>

            <!-- Content -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">What is CVV?</h3>
                <p class="text-sm text-gray-600 mb-5">
                    The CVV (Card Verification Value) is a 3- or 4-digit number on your credit/debit card.  
                    It adds extra security for online purchases.
                </p>

                <!-- Image -->
                <div class="flex justify-center mb-4">
                    <img 
                        src="https://duplinestephanus.github.io/WebbApp-Files/icons/CVV-code.png" 
                        alt="Card showing CVV location" 
                        class="max-w-full h-auto rounded border border-gray-200"
                    >
                </div>

                <p class="text-xs text-gray-500 text-center">
                    Usually found on the back of the card (Visa/Mastercard) or front (Amex)
                </p>
            </div>
        </div>
    `;

    // Append to body
    document.body.appendChild(modal);

    // Elements inside modal
    const closeBtn = modal.querySelector('button');
    const image = modal.querySelector('img');

    // Open modal
    helpBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('help btn clicked');
        modal.classList.remove('hidden');
        // Optional: lazy load image only when shown
        if (image.dataset.src) {
            image.src = image.dataset.src;
            image.removeAttribute('data-src');
        }
    });

    // Close modal on X click
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close when clicking outside the card
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Optional: close with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
});