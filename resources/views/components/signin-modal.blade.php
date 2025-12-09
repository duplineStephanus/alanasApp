<!-- Modal backdrop -->
    <div id="signinModal" class="fixed inset-0 flex items-center justify-center bg-black/50 overflow-y-auto font-display z-50 hidden">
        <!-- Modal content -->
        <form id="registerForm" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                @csrf
                <!-- Sign in : enter email and continue-->
                <div id="signin-1" class="hidden">

                    <div class="flex justify-end">
                        <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-2xl text-tamanuleaf">Sign in or create an account</h2>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="signin-email" class="block text-sm font-medium text-tamanuleaf">Email</label>
                        <input type="email" id="signin-email" name="email" placeholder="Enter your email" required>
                        <p id="signin-emailError" class="text-red-500 text-sm mt-1 hidden">Valid email is required.</p>
                    </div>

                    <button type="button" id="signin-step1-continue-btn" class="btn-primary">Continue</button>

                    <div class="mb-4 text-xs text-gray-500">
                        <p>By continuing you agree to Our Terms of Use and Privacy Policy.</p>
                    </div>
                    
                    <!-- Social Sign In -->
                    <!-- 
                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-4 text-gray-500 font-medium">OR</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <button type="button" id="googleSigninBtn" class="btn-plain"> <i class="fa-brands fa-google text-blue-500"></i> Sign In with Google</button>

                    <button type="button" id="facebookSigninBtn" class="btn-plain"> <i class="fa-brands fa-facebook text-blue-600"></i> Sign In with Facebook</button>

                    <div class="mb-4 text-xs text-gray-500">
                        <p>By clicking Sign In or Continue with Google, Facebook, or Apple, you agree to Our Terms of Use and Privacy Policy.</p>
                    </div> 
                    -->

                </div>
                
                {{-- *** if user exist --}}
                <!-- Sign in : enter email and continue-->

                <div id="signin-2" class="hidden">

                    <div class="flex justify-end">
                        <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-2xl text-tamanuleaf">Sign in</h2>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 mb-4">
                        <p id="signinStep2-email">jane.doe@gmail.com</p>
                        <button type="button" class="change-email text-blue-500 text-sm">Change</button>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <label for="signin-password" class="text-sm font-medium text-tamanuleaf">Password</label>
                            <a href="#" class="text-xs text-blue-500 hover:underline">Forgot password?</a>
                        </div>
                        <input type="password" id="signin-password" name="password" placeholder="Enter your password" required class="w-full border border-gray-300 rounded px-3 py-2 mt-1"/>
                        <p id="signin-passwordError" class="text-red-500 text-sm mt-1 hidden">Invalid password.</p>
                    </div>

                    <button type="button" id="signin-step2-signin-btn" class="btn-primary">Sign In</button>

                    <div class="mb-4 text-xs text-gray-500">
                        <p>By signing in you agree to Our Terms of Use and Privacy Policy.</p>
                    </div>
                    
                </div>

                {{-- *** eles --}}
                    <!-- register user -->
                    
                    <!-- Register : if email does not exist in db, prompt user to click continue to create a new account using the email they entered -->
                    <div id="registerSection" class="hidden">

                        <div class="flex justify-end">
                            <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                
                        <div class="flex items-center mb-4">
                            <div>
                                <h2 class="text-xl text-tamanuleaf">Looks like you don't have an account.</h2>
                            </div>
                        </div> 

                        <div class="flex items-center space-x-5 mx-5 mb-4">
                            <p>jane.doe@gmail.com</p>
                            <button type="button" class="change-email text-blue-500 text-sm">Change</button>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg">Let's create an account using your email.</h2>
                        </div>

                        <button type="button" id="lets-register-btn" class="btn-primary">
                            Continue
                        </button>

                        <div id="divider-line" class="flex items-center my-4">
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <div class="mb-4 text-1xl font-bold">
                            <h1>Got an account?</h1>
                        </div>

                        <div class="mb-4 text-lg">
                            <button type="button" class="change-email text-blue-500">Sign in using a different email.</button>
                        </div>
                    </div>   
                    
                    <!-- CreateAccount : collect email, name, password and continue ... -->
                    <div id="createAccountSection" class="hidden">
                        <div class="flex justify-end">
                            <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-2xl text-tamanuleaf">Create an account</h2>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="create-email" class="block text-sm font-medium text-tamanuleaf">Email</label>
                            <input type="email" id="create-email" name="email" placeholder="Enter your email" required value="jane.doe@gmail.com">
                            <p id="create-emailError" class="text-red-500 text-sm mt-1 hidden">Valid email is required.</p>
                        </div>

                        <div class="mb-4">
                            <label for="create-name" class="block text-sm font-medium text-tamanuleaf">Name</label>
                            <input type="text" id="create-name" name="name" placeholder="Enter your name" required>
                            <p id="create-nameError" class="text-red-500 text-sm mt-1 hidden">Valid name is required.</p>
                        </div>

                        <div class="mb-4">
                            <label for="create-password" class="block text-sm font-medium text-tamanuleaf">Password </label>
                            <input type="password" id="create-password" name="password" placeholder="Enter your password" required>
                            <p id="create-passwordError" class="text-red-500 text-sm mt-1 hidden">Valid password is required.</p>
                        </div>

                        <div class="mb-4">
                            <label for="create-password_confirmation" class="block text-sm font-medium text-tamanuleaf">Confirm Password </label>
                            <input type="password" id="create-password_confirmation" name="password_confirmation" placeholder="Re-enter your password" required>
                            <p id="create-passwordConfirmedError" class="text-red-500 text-sm mt-1 hidden">Valid password confirmation is required.</p>
                        </div>

                        <button type="button" id="create-account-btn" class="btn-primary">Continue</button>

                        <div id="divider-line" class="flex items-center my-4">
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <div class="mb-4 text-1xl font-bold">
                            <h1>Got an account?</h1>
                        </div>

                        <div class="mb-4 text-lg">
                            <button type="button" class="change-email text-blue-500">Sign in using a different email.</button>
                        </div>

                        <div class="mb-4 text-xs text-gray-500">
                            <p>By continuing you agree to Our Terms of Use and Privacy Policy.</p>
                        </div>
            
                    </div> 

                    <!-- Verify email : collect OTP and continue...-->

                    <div id="verifyEmailSection-otp" class="hidden" >

                        <div class="flex justify-end">
                            <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-2xl text-tamanuleaf">Verify your email</h2>
                            </div>
                        </div>

                        <div class="flex items-center space-x-5 mx-5 mb-4">
                            <p>To verify your email address we've sent a One Time Password (OTP) to jane.doe@gmail.com </p>
                            <a href="" class="text-blue-500">Change</a>
                        </div>

                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-tamanuleaf">Enter security code</label>
                            <input type="text" id="code" name="code" placeholder="Enter your code" required>
                            <p id="codeError" class="text-red-500 text-sm mt-1 hidden">
                                Invalid security code 
                                <span>
                                    Re-send code
                                </span>
                            </p>
                        </div>

                        <button type="button" id="verify-email-btn" class="btn-primary">Create Account</button>

                        <div class="mb-4 text-xs text-gray-500">
                            <p>By creating an account, you agree to Our Terms of Use and Privacy Policy.</p>
                        </div>

                    </div>

                    <!-- Account Creation Confirmation : confirm that email is valid and account is created -->

                    <div id="verifyEmailSection-confirmation" class="hidden" >

                        <div class="flex justify-end">
                            <button type="button" command="close-modal" commandfor="signinModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-center space-x-6 mb-4">
                            <i class="fa-solid fa-square-check text-4xl text-coastalfern"></i>
                            <div>
                                <h2 class="text-2xl text-tamanuleaf">Account Created</h2>
                            </div>
                        </div>

                    </div>
                
                    
                {{-- end @if --}}

            </form>

    </div>