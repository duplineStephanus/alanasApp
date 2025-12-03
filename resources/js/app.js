//main entry point for vite 
import './bootstrap';
import './cart/cart-counter';
import './products/product-variant';
import './users/toggle-signin-modal';
import './products/product-detail';
import './users/logout';

import { addToCart } from './cart/add-to-cart';

document.addEventListener('DOMContentLoaded', function (){

    const productContainer = this.getElementById('products-container');

    if(productContainer){
        addToCart(); 

    }
});




