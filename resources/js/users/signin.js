import axios from "axios";

export function signIn() {
    const modal = document.getElementById("signinModal");
    const closeButtons = document.querySelectorAll('[command="close-modal"]');
    const inputFields = document.querySelectorAll("#signinModal input");

    //clear each input errors on input change
    inputFields.forEach(input => {
        input.addEventListener("input", () => {
            const errorEl = document.getElementById(`${input.id}Error`);
            if (errorEl) errorEl.classList.add("hidden");
        });
    });
    //Clear passwords as soon as the user starts typing again after an error:
    document.querySelectorAll('input[type="password"]').forEach(input => {
        input.addEventListener('input', () => {
            clearErrors();
        });
    });

    //FUNCTIONS START

    function showSection(id) {
        modal.querySelectorAll("form > div").forEach(div => div.classList.add("hidden"));
        //clear all errors
        clearErrors();
        clearPasswordInputs();

        //show the active section
        const active = modal.querySelector(`#${id}`);
        active.classList.remove("hidden");

        const input = active.querySelector("input");
        if (input) setTimeout(() => input.focus(), 50);
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPassword(password) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(password);
    }

    function isValidName(name) {
        return typeof name === 'string' && name.length >= 4 && name.length <= 225;
    }


    function clearErrors() {
        modal.querySelectorAll(".text-red-500").forEach(el => {
            el.classList.add("hidden");
            el.textContent = "";
        });
    }

    function clearPasswordInputs() {
        const passwordInputs = document.querySelectorAll(
            'input[type="password"]'
        );

        passwordInputs.forEach(input => {
            input.value = '';
        });
    }

    async function prepareOtp(email) {
        try {
            const res = await axios.post("/prepare-otp", { email });

            const messageEl = document.getElementById("otpMessageText");
            if (messageEl && res.data.message) {
                messageEl.textContent = res.data.message;
                messageEl.classList.remove("hidden");
            }
        } catch (err) {
            console.error("Failed to prepare OTP", err);
        }
    }

    //FUNCTIONS ENDS HERE


    // Open modal → clear errors
    document.querySelectorAll('[command="show-modal"]').forEach(btn => {
        btn.addEventListener("click", () => clearErrors());
    });

    // Close modal
    closeButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const modalId = btn.getAttribute("commandfor");
            document.getElementById(modalId).classList.add("hidden");
        });
    });

    // Initialize modal
    showSection("signin-1");

    // Step 1: Check if email exists
    const step1Btn = document.getElementById("signin-step1-continue-btn");
    step1Btn.addEventListener("click", async () => {
        const email = document.getElementById("signin-email").value.trim();
        const errorEl = document.getElementById("signin-emailError");
        clearErrors(); //clear all errors

        if (!email || !isValidEmail(email)) {
            errorEl.textContent = "Please enter a valid email.";
            console.log("JS isValidEmail() failed.");
            errorEl.classList.remove("hidden");
            return;
        }

        try {
            const res = await axios.post("/check-email", { email });
            if (res.data.exists) {
                document.getElementById("signinStep2-email").textContent = email;
                showSection("signin-2");
            } else {
                modal.querySelector("#registerSection p").textContent = email;
                showSection("registerSection");
            }
        } catch (err) {
            console.error(err);

            //If validation error from Laravel (422)
            if (err.response?.status === 422 && err.response.data?.error) {
                errorEl.textContent = err.response.data.error;
                console.log("Laravel EMAIL ERROR 422");
            } else (
                errorEl.textContent = "Something went wrong."
            )

            errorEl.classList.remove("hidden");
        }
    });

    // Step 2: Sign in with password
    document.getElementById("signin-step2-signin-btn").addEventListener("click", async () => {
        const email = document.getElementById("signinStep2-email").textContent.trim();
        const password = document.getElementById("signin-password").value.trim();
        const errorEl = document.getElementById("signin-passwordError");
        clearErrors();

        if (!password) {
            errorEl.textContent = "Password is required.";
            errorEl.classList.remove("hidden");
            return;
        }

        try {
            const res = await axios.post("/signin", { email, password });
            const { status, message, retry_after } = res.data;

            switch (status) {
                case "success":
                    clearPasswordInputs();
                    location.reload();
                    break;
                case "otp_required":
                    document.getElementById("verify-email").textContent = email;
                    await prepareOtp(email);
                    showSection("verifyEmailSection-otp");
                    break;
                case "locked":
                    errorEl.textContent = `Too many attempts. Try again in ${retry_after} hours.`;
                    errorEl.classList.remove("hidden");
                    break;
                case "invalid":
                    errorEl.textContent = message;
                    errorEl.classList.remove("hidden");
                    break;
            }
        } catch (err) {
            clearPasswordInputs();
            console.error(err);
            errorEl.textContent = "Something went wrong. Please try again 1.";
            errorEl.classList.remove("hidden");
        }
    });

    // Step 3: Register → Continue to create account
    document.getElementById("lets-register-btn").addEventListener("click", () => {
        const email = modal.querySelector("#registerSection p").textContent;
        document.getElementById("create-email").value = email;
        showSection("createAccountSection");
    });

    // Step 4: Create account
    document.getElementById("create-account-btn").addEventListener("click", async () => {
        const name = document.getElementById("create-name").value.trim();
        const email = document.getElementById("create-email").value.trim();
        const password = document.getElementById("create-password").value.trim();
        const confirm = document.getElementById("create-password_confirmation").value.trim();

        let hasError = false;

        // Name validation
        const nameError = document.getElementById("create-nameError");
        if (!name) { nameError.textContent = "Name is required"; nameError.classList.remove("hidden"); hasError = true; } else if (!isValidName(name)) { nameError.textContent = "Name must be between 4 and 225 chars."; nameError.classList.remove("hidden"); hasError = true; } else nameError.classList.add("hidden");

        // Email validation
        const emailError = document.getElementById("create-emailError");
        if(!email){emailError.textContent = "Email is required"; emailError.classList.remove("hidden"); hasError = true; } else if (!isValidEmail(email)) { emailError.textContent = "Please enter a valid email"; emailError.classList.remove("hidden"); hasError = true; } else emailError.classList.add("hidden");

        // Password validation
        const passwordError = document.getElementById("create-passwordError");
        if (!isValidPassword(password)) { passwordError.textContent = "Password must be at least 8 chars and no longer than 25 chars with 1 uppercase, 1 lowercase, 1 number, and 1 special char."; passwordError.classList.remove("hidden"); hasError = true; } else passwordError.classList.add("hidden");

        // Confirm password
        const confirmError = document.getElementById("create-passwordConfirmedError");
        if (password !== confirm) { confirmError.textContent = "Passwords do not match"; confirmError.classList.remove("hidden"); hasError = true; } else confirmError.classList.add("hidden");

        if (hasError) return;

        try {
            const res = await axios.post("/register", { name, email, password, password_confirmation: confirm });
            if (res.data.status === "otp_sent") {
                document.getElementById("verify-email").textContent = email;
                await prepareOtp(email);
                showSection("verifyEmailSection-otp");
            }
        } catch (err) {
           clearPasswordInputs();
           console.error("AXIOS ERROR:", err);

            if (err.response) {
                console.error("STATUS:", err.response.status);
                console.error("DATA:", err.response.data);
            }

            console.log("Something went wrong. Please try again. 2");
        }
    });

    // Step 5: Verify OTP
    document.getElementById("verify-email-btn").addEventListener("click", async () => {
        const email = document.getElementById("verify-email").textContent.trim();
        const code = document.getElementById("code").value.trim();
        const errorEl = document.getElementById("codeError");
        clearErrors();

        try {
            const res = await axios.post("/verify-otp", { email, code });
            const { status, message, retry_after } = res.data;

            switch (status) {
                case "verified":
                    clearPasswordInputs();
                    location.reload();
                    return; // no need to show "success" message
                case "locked":
                    errorEl.textContent = `Too many attempts. Try again in ${retry_after} hours.`;
                    errorEl.classList.remove("hidden");
                    break;
                case "invalid":
                    errorEl.textContent = message;
                    errorEl.classList.remove("hidden");
                    break;
            }
        } catch (err) {
            // If navigation already started, ignore
            if (document.visibilityState === "hidden") return;

            console.error(err);
            errorEl.textContent = "Something went wrong. Please try again. 3";
            console.log(email);
            errorEl.classList.remove("hidden");
        }
    });

    // Step 6: Resend OTP
    document.getElementById("resend-otp-btn").addEventListener("click", async () => {
        const email = document.getElementById("verify-email").textContent.trim();
        const messageEl = document.getElementById("resendOtpMessage");

        try {
            const res = await axios.post("/resend-otp", { email },{
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        

            if (res.data.status === "otp_resent") {
                messageEl.textContent = "A new OTP has been sent to your email.";
                messageEl.classList.remove("hidden");
                //clearErrors();

                // Optional: clear the previous OTP input
                document.getElementById("code").value = "";
            }
        } catch (err) {
            console.error(err);
            messageEl.textContent = "Unable to resend OTP. Please try again.";
            messageEl.classList.remove("hidden");
        }
    });

    // Change email → go back to step 1
    modal.querySelectorAll(".change-email").forEach(el => {
        el.addEventListener("click", (e) => { e.preventDefault(); showSection("signin-1"); });
    });

    //Clear all errro messages if close-modal is clicked 
    document.querySelectorAll('[command="close-modal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            clearPasswordInputs();
            clearErrors();
        });
    });
}
