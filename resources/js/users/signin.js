import axios from "axios";

export function signIn() {
        // Helper to show/hide sections
    function showSection(id) {
        document.querySelectorAll("#signinModal form > div").forEach(div => div.classList.add("hidden"));
        document.getElementById(id).classList.remove("hidden");
    }

    // Step 1: Check if email exists
    document.getElementById("signin-step1-continue-btn").addEventListener("click", async () => {
        const emailInput = document.getElementById("signin-email");
        const email = emailInput.value.trim();
        const errorEl = document.getElementById("signin-emailError");

        if (!email || !email.includes("@")) {
            errorEl.classList.remove("hidden");
            return;
        }
        errorEl.classList.add("hidden");

        try {
            const res = await axios.post("/check-email", { email });

            if (res.data.exists) {
                // User exists → go to sign-in password
                document.getElementById("signinStep2-email").textContent = email;
                showSection("signin-2");
            } else {
                // User doesn’t exist → go to register section
                document.querySelectorAll("#registerSection p")[0].textContent = email;
                showSection("registerSection");
            }

        } catch (err) {
            console.error(err);
        }
    });

    // Step 2: Handle sign-in
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
            if (res.data.success) {
                alert("Signed in successfully!");
                location.reload();
            } else {
                errorEl.textContent = "Invalid credentials.";
                errorEl.classList.remove("hidden");
            }
        } catch (err) {
            errorEl.textContent = "Something went wrong.";
            errorEl.classList.remove("hidden");
        }
    });

    // Step 3: Register new user - go to create account
    document.getElementById("lets-register-btn").addEventListener("click", () => {
        const email = document.querySelector("#registerSection p").textContent;
        document.getElementById("create-email").value = email;
        showSection("createAccountSection");
    });

    // Step 4: Create account
    document.getElementById("create-account-btn").addEventListener("click", async () => {
        const name = document.getElementById("create-name").value.trim();
        const email = document.getElementById("create-email").value.trim();
        const password = document.getElementById("create-password").value.trim();
        const confirm = document.getElementById("create-password_confirmation").value.trim();

        if (!name || !email || !password || password !== confirm) {
            alert("Please fill out all fields correctly.");
            return;
        }

        try {
            const res = await axios.post("/register", { name, email, password, password_confirmation: confirm });

            if (res.data.status === "otp_sent") {
                showSection("verifyEmailSection-otp");
            } else {
                alert("Unexpected response.");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Step 5: Verify OTP
    document.getElementById("verify-email-btn").addEventListener("click", async () => {
        const email = document.getElementById("create-email").value.trim();
        const code = document.getElementById("code").value.trim();

        try {
            const res = await axios.post("/verify-otp", { email, code });

            if (res.data.verified) {
                showSection("verifyEmailSection-confirmation");
            } else {
                document.getElementById("codeError").classList.remove("hidden");
            }
        } catch (err) {
            console.error(err);
        }
    });


};