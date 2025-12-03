import axios from "axios";

export function signIn() {

    const modal = document.getElementById("signinModal");

    function showSection(id) {
        // Hide all sections
        modal.querySelectorAll("form > div").forEach(div => div.classList.add("hidden"));

        // Show the selected section
        const active = modal.querySelector(`#${id}`);
        active.classList.remove("hidden");

        // Auto-focus the first input in this section
        const input = active.querySelector("input");
        if (input) {
            setTimeout(() => input.focus(), 50);
        }
    }

    // Force the first section to initialize + autofocus
    showSection("signin-1");

    // Step 1: Check if email exists
    const step1Btn = document.getElementById("signin-step1-continue-btn");
    step1Btn.addEventListener("click", async () => {
        const email = document.getElementById("signin-email").value.trim();
        const errorEl = document.getElementById("signin-emailError");

        if (!email || !email.includes("@")) {
            errorEl.classList.remove("hidden");
            return;
        }
        errorEl.classList.add("hidden");

        try {
            const res = await axios.post("/check-email", { email });
            if (res.data.exists) {
                document.getElementById("signinStep2-email").textContent = email;
                showSection("signin-2");
            } else {
                modal.querySelector("#registerSection p").textContent = email;
                showSection("registerSection");
            }
        } catch (err) { console.error(err); }
    });

    // Step 2: Sign-in with password
    document.getElementById("signin-step2-signin-btn").addEventListener("click", async () => {
        const email = document.getElementById("signinStep2-email").textContent;
        const password = document.getElementById("signin-password").value.trim();
        const errorEl = document.getElementById("signin-passwordError");

        if (!password) {
            errorEl.classList.remove("hidden");
            return;
        }
        errorEl.classList.add("hidden");

        try {
            const res = await axios.post("/signin", { email, password });
            if (res.data.success) location.reload();
            else { errorEl.textContent = "Invalid credentials"; errorEl.classList.remove("hidden"); }
        } catch (err) {
            errorEl.textContent = "Something went wrong"; errorEl.classList.remove("hidden");
        }
    });

    // Step 3: Register → Create Account
    document.getElementById("lets-register-btn").addEventListener("click", () => {
        const email = modal.querySelector("#registerSection p").textContent;
        document.getElementById("create-email").value = email;
        showSection("createAccountSection");
    });

    // Step 4: Create Account
    document.getElementById("create-account-btn").addEventListener("click", async () => {
        const name = document.getElementById("create-name").value.trim();
        const email = document.getElementById("create-email").value.trim();
        const password = document.getElementById("create-password").value.trim();
        const confirm = document.getElementById("create-password_confirmation").value.trim();

        if (!name || !email || !password || password !== confirm) {
            alert("Fill all fields correctly");
            return;
        }

        try {
            const res = await axios.post("/register", { name, email, password, password_confirmation: confirm });
            if (res.data.status === "otp_sent") showSection("verifyEmailSection-otp");
            else alert("Unexpected response");
        } catch (err) { console.error(err); }
    });

    // Step 5: Verify OTP
    document.getElementById("verify-email-btn").addEventListener("click", async () => {
        const email = document.getElementById("create-email").value.trim();
        const code = document.getElementById("code").value.trim();

        try {
            const res = await axios.post("/verify-otp", { email, code });
            if (res.data.verified) showSection("verifyEmailSection-confirmation");
            else document.getElementById("codeError").classList.remove("hidden");
        } catch (err) { console.error(err); }
    });
}
