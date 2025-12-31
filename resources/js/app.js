//main entry point for vite 
import './bootstrap';
import './cart/cart-counter';
import './products/product-variant';
import './users/toggle-signin-modal';
import './products/product-detail';
import './users/logout';

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

import { addToCart } from './cart/add-to-cart';

document.addEventListener('DOMContentLoaded', function (){

    const productContainer = this.getElementById('products-container');

    if(productContainer){
        addToCart(); 

    }
});




