//main entry point for vite 
import './bootstrap';
import './cart/cart-counter';
import './products/product-variant';    
import './users/logout';
import './cart/add-to-cart';
import './cart/toggle-billing-address'; 
import { openCvvCodeHelper } from './cart/cvv-code';    
import { updateCart } from './cart/update-cart';
import { toggleBillingAddress } from './cart/toggle-billing-address';
import { toggleProductDetail } from './products/product-detail';  
import {toggleSigninModal} from './users/toggle-signin-modal';
import { initCheckoutRefresh } from './checkout/update-checkout';

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

document.addEventListener('DOMContentLoaded', () => {

    if(document.getElementById('cvv-help-btn')){
        openCvvCodeHelper();
    }

    if(document.querySelector('.cart-btn')){
        updateCart(); 
    }

    if(document.getElementById('same-as-shipping')){
        toggleBillingAddress(); 
    }

    if(document.getElementById('variant-select')){
        toggleProductDetail();
    }

    if(document.getElementById("signinModal")){
        toggleSigninModal();
    }

    if(document.getElementById('drawer')){
        initCheckoutRefresh();
    }
    
})