@auth
    <x-user-menu id="user-menu" variant="default"></x-user-menu>
@else
    <!-- Sign in button -->
    <button class="signin-btn text-sm font-medium text-gray-600 hover:text-gray-800">Sign in</button>

    <!-- Register button -->
    <button class="signin-btn text-sm font-medium text-gray-600 hover:text-gray-800">Register</button>
    
@endauth