import { getCart } from "../cart/update-cart";  

export function initCheckoutRefresh() {

    const drawer = document.getElementById('drawer');

    if (!drawer) return;

    drawer.addEventListener('toggle', () => {
        if (!drawer.open && window.location.pathname === '/checkout') {
            refreshCheckoutSummary();
        }
    });
    
}
function refreshCheckoutSummary() {

    const cartState = getCart();
    console.log(cartState);

    const summaryList = document.getElementById('order-summary-list');
    const summarySubtotal = document.getElementById('order-summary-totals');
    console.log(`summary subtotal: ${summarySubtotal}`);
    //reset order summary list 
    summaryList.innerHTML = '';

    let subtotal = 0;

    Object.values(cartState).forEach(item => {
        const li = document.createElement('li');
        li.className = 'flex py-6';
        li.innerHTML = `

            <div class="flex-shrink-0 w-24 h-24 rounded-md overflow-hidden bg-gray-100">
                <img src="${item.image}" alt="${item.name}" class="w-full h-full object-center object-cover">
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-base font-medium text-gray-900">${item.name}</h3>
                <p class="mt-1 text-sm text-gray-500">${item.size}</p>
                <p class="mt-1 text-sm text-gray-900">Quantity: ${item.quantity}</p>
                <p class="mt-2 text-base font-medium text-gray-900">$ ${item.price}</p>
            </div>
        `;
        summaryList.appendChild(li);
        //calculate subtotal 
        subtotal += item.price * item.quantity;

    })

    console.log(`subtotal: ${subtotal}`)

    let tax = subtotal * 0.06;
    let total = subtotal + tax;

    //update order summary
    summarySubtotal.querySelector('.cart-subtotal dd').textContent = `$ ${subtotal.toFixed(2)}`;
    console.log(`subtotal: ${subtotal.toFixed(2)}`);
    //summarySubtotal.querySelector('.cshipping-handling-cost dd').textContent = `$ 10.00`;
    summarySubtotal.querySelector('.estimated-tax dd').textContent = `$ ${tax.toFixed(2)}`;
    console.log(`tax: ${subtotal*0.06.toFixed(2)}`);
    summarySubtotal.querySelector('.order-total dd').textContent = `$ ${total.toFixed(2)}`;
    console.log(`total: ${subtotal+subtotal*0.06.toFixed(2)}`);
 
}
    