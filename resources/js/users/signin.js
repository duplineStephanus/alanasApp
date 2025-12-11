import axios from "axios";

export function signIn() {

    const modal = document.getElementById("signinModal");
    const closeButtons = document.querySelectorAll('[command="close-modal"]');

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

    function isValidEmail(email) {
        // Returns true if email is valid format, false otherwise
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPassword(password) {
        // Updated regex: Lookaheads for requirements, but allow any characters (fixes restrictive char class)
        // Ensures min 8 chars, one lowercase, one uppercase, one digit, one specific special (@$!%*?&)
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(password);
    }

    function clearErrors() {
        // Hide all email and password error messages
        modal.querySelectorAll(".text-red-500").forEach(el => el.classList.add("hidden"));
        // Optionally, reset error text content if you change it dynamically
        modal.querySelectorAll(".text-red-500").forEach(el => el.textContent = "");
    }

    // Clear errors when modal is opened
    document.querySelectorAll('[command="show-modal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const modalId = btn.getAttribute("commandfor");
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                clearErrors();
            }
        });
    });

    // Clear errors when modal is closed
    document.querySelectorAll('[command="close-modal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const modalId = btn.getAttribute("commandfor");
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                clearErrors();
                modalEl.classList.add("hidden"); // actually hide the modal
            }
        });
    });

    // Force the first section to initialize + autofocus
    showSection("signin-1");

    // Step 1: Check if email exists
    const step1Btn = document.getElementById("signin-step1-continue-btn");
    step1Btn.addEventListener("click", async () => {
        const email = document.getElementById("signin-email").value.trim();
        const errorEl = document.getElementById("signin-emailError");

        if (!email || !isValidEmail(email)) {
            errorEl.textContent = "Please enter a valid email.";
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
            errorEl.textContent = "Password is required";
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

        let hasError = false;

        // Name validation
        const nameError = document.getElementById("create-nameError");
        if (!name) {
            nameError.textContent = "Name is required";
            nameError.classList.remove("hidden");
            hasError = true;
        } else {
            nameError.classList.add("hidden");
        }

        // Email validation
        const emailError = document.getElementById("create-emailError");
        if (!isValidEmail(email)) {
            emailError.textContent = "Please enter a valid email";
            emailError.classList.remove("hidden");
            hasError = true;
        } else {
            emailError.classList.add("hidden");
        }

        // Password validation
        const passwordError = document.getElementById("create-passwordError");
        if (!isValidPassword(password)) {
            passwordError.textContent = "Password must be at least 8 characters with uppercase, lowercase, number, and special character";
            passwordError.classList.remove("hidden");
            hasError = true;
        } else {
            passwordError.classList.add("hidden");
        }

        // Confirm password validation
        const confirmError = document.getElementById("create-passwordConfirmedError");
        if (password !== confirm) {
            confirmError.textContent = "Passwords do not match";
            confirmError.classList.remove("hidden");
            hasError = true;
        } else {
            confirmError.classList.add("hidden");
        }

        // Stop if any validation failed
        if (hasError) return;

        try {
            const res = await axios.post("/register", { 
                name, 
                email, 
                password, 
                password_confirmation: confirm 
            });

            if (res.data.status === "otp_sent")
                showSection("verifyEmailSection-otp");
            else 
                alert("Unexpected response");

        } catch (err) {
            if (err.response && err.response.status === 422) {
                const errors = err.response.data.errors;

                if (errors.name) {
                    nameError.textContent = errors.name[0];
                    nameError.classList.remove("hidden");
                }

                if (errors.email) {
                    emailError.textContent = errors.email[0];
                    emailError.classList.remove("hidden");
                }

                if (errors.password) {
                    passwordError.textContent = errors.password[0];
                    passwordError.classList.remove("hidden");
                }

                return; // stop further execution
            }

            console.error(err);
            alert('Something went wrong. Please try again.');
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
                // Optional: Auto-redirect after a brief delay to show confirmation
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } else {
                document.getElementById("codeError").classList.remove("hidden");
                //document.getElementById("codeError").textContent = res.data.message || 'Invalid security code';
            }
        } catch (err) {
            console.error(err);
            document.getElementById("codeError").classList.remove("hidden");
            document.getElementById("codeError").textContent = 'Something went wrong. Please try again.';
        }
    });

    // Close modal only when X button is clicked
    closeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const modalId = btn.getAttribute('commandfor');
            const modalEl = document.getElementById(modalId);

            if (modalEl) {
                modalEl.classList.add('hidden');
            }
        });
    });


    // Go back to Step 1 when "Change" or "Sign in using a different email" is clicked ---
    modal.querySelectorAll('.change-email').forEach(el => {
        el.addEventListener("click", (e) => {
            e.preventDefault();
            showSection("signin-1");
        });
    });


}