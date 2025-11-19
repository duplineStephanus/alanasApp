//main entry point for vite 
import './bootstrap';
import './cart/cart-counter';
import './products/product-variant';
import './products/product-filter';
import './users/toggle-signin-modal';

import { addToCart } from './cart/add-to-cart';

document.addEventListener('DOMContentLoaded', function (){

    const productContainer = this.getElementById('products-container');

    if(productContainer){
        addToCart(); 

    }
});




