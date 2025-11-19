import { signIn } from "./signin";

document.addEventListener("DOMContentLoaded", () => {
    // Open modal
    let signinBtns = document.querySelectorAll(".signin-btn");
    const signinModal = document.getElementById("signinModal");
    const signinStep1 = document.getElementById("signin-1");

    signinBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (signinModal && signinStep1) {
                // ✅ Always reset modal first (so you don’t see previous step)
                resetSigninModal();

                // Open the sign-in modal 
                signinModal.classList.remove("hidden");
                signinStep1.classList.remove("hidden");

                // Start the sign-in logic (attach listeners, etc.)
                signIn(); 
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

                // ✅ Reset modal state when closing
                resetSigninModal();
            }
        });
    });

    // ✅ Optional: Close when clicking the backdrop
    signinModal.addEventListener("click", (e) => {
        if (e.target.id === "signinModal") {
            signinModal.classList.add("hidden");
            resetSigninModal();
        }
    });
});

// ✅ Add this function at the bottom
function resetSigninModal() {
    const modal = document.getElementById("signinModal");
    if (!modal) return;

    // Hide all step divs
    modal.querySelectorAll("form > div").forEach(div => div.classList.add("hidden"));

    // Show the first step only
    const step1 = modal.querySelector("#signin-1");
    if (step1) step1.classList.remove("hidden");

    // (Optional) clear inputs if you want a full reset
    modal.querySelectorAll("input").forEach(input => input.value = "");
}
