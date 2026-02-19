import { signIn } from "./signin";

export function toggleSigninModal() {
    const signinBtns = document.querySelectorAll(".signin-btn");
    const signinModal = document.getElementById("signinModal");

    // Open modal
    signinBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (signinModal) {
                resetSigninModal();
                signinModal.classList.remove("hidden");
                signIn(); // Attach step logic
            }
        });
    });

    // Close modal buttons
    document.querySelectorAll('[command="close-modal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const targetModal = document.getElementById(btn.getAttribute("commandfor"));
            if (targetModal) {
                targetModal.classList.add("hidden");
                resetSigninModal();
            }
        });
    });

    // Close when clicking outside modal
    signinModal.addEventListener("click", e => {
        if (e.target.id === "signinModal") {
            signinModal.classList.add("hidden");
            resetSigninModal();
        }
    });
}

function resetSigninModal() {
    const modal = document.getElementById("signinModal");
    if (!modal) return;

    // Hide all sections
    modal.querySelectorAll("form > div").forEach(div => div.classList.add("hidden"));

    // Show first step
    const step1 = modal.querySelector("#signin-1");
    if (step1) step1.classList.remove("hidden");

    // Clear inputs
    modal.querySelectorAll("input").forEach(input => input.value = "");
}
