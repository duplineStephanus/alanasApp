import { collectAndSendEmail } from "./signin-email";

document.addEventListener("DOMContentLoaded", () => {
    // Open modal
    let signinBtns = document.querySelectorAll(".signin-btn");
    const signinModal = document.getElementById("signinModal");
    const signinStep1 = document.getElementById("signin-1");
    const mobileMenu = document.getElementById("mobile-menu");

    signinBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            
            
            if(signinModal && signinStep1){

                //opne the sigin modal 
                signinModal.classList.remove("hidden");
                signinStep1.classList.remove("hidden");

            }
            
            
        });
    });

    // Close modal (for any button with command="close-modal")
    const closeButtons = document.querySelectorAll('[command="close-modal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const targetId = btn.getAttribute("commandfor");
            const targetModal = document.getElementById(targetId);
            if (targetModal) {
                targetModal.classList.add("hidden");
                // also hide signin-1 inside it if present
                const step1 = targetModal.querySelector("#signin-1");
                if (step1) step1.classList.add("hidden");
            }
        });
    });

    
    //getEmail();

});

function getEmail (){

    const continueBtn = document.getElementById('signin-step1-continue-btn');

    if(continueBtn){
        document.getElementById('signin-step1-continue-btn').addEventListener('click', () => {
        const emailInput = document.getElementById('signin-email');
        const email = emailInput.value.trim();

        if (email) {
            collectAndSendEmail(email);
        }
        });
    }
}
