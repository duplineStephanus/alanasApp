<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="https://use.typekit.net/apw7jrw.css">
    <title>Alana's App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-coconuthusk">
    <div >
        <x-mobile-menu/>
        <x-signin-modal/>
        <!-- Header -->
        <header class="relative">
            <nav aria-label="Top" class="mx-auto max-w-full px-4 sm:px-6 lg:px-8 my-1 py-1 mt-2">
                <div class="flex h-16 items-center">
                    <div class="flex space-x-3">
                        <!-- Logo -->
                        <div class="ml-4 flex lg:ml-0">
                            <a href="/">
                            <span class="sr-only">Your Company Logo</span>
                            <img src="https://duplinestephanus.github.io/WebbApp-Files/logo/alanas-logo.png" class="h-15 w-auto"/>
                            </a>
                        </div>
                        <!-- Burger menu button -->
                        <button type="button" command="show-modal" commandfor="mobile-menu" class="relative rounded-md p-2">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open menu</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 50 35" class="text-tamanuleaf" fill="currentColor"><g id="Group_28" data-name="Group 28" transform="translate(-229 -79)"><rect id="Rectangle_8" data-name="Rectangle 8" width="50" height="7" rx="3.5" transform="translate(229 79)" /><rect id="Rectangle_9" data-name="Rectangle 9" width="50" height="7" rx="3.5" transform="translate(229 93)" /><rect id="Rectangle_10" data-name="Rectangle 10" width="50" height="7" rx="3.5" transform="translate(229 107)" /></g>
                            </svg>
                        </button>
                    </div>

                    <!-- Search form -->
                    <form class="hidden md:block w-xl mx-auto">   
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <input type="search" id="default-search" class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:outline-none focus:ring-0 focus:ring-coastalfern focus:border-coastalfern dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-coastalfern dark:focus:border-coastalfern" placeholder="Search products, contents..." required />
                            <button type="submit" class="absolute end-2.5 bottom-2.5 text-3xl text-gray-500 hover:text-tamanuleaf">
                                <i class="fa-solid fa-magnifying-glass text-base"></i>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                    </form>

                    <div class="ml-auto flex items-center">
                        <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                            <x-user-btn/> <!-- Greet user / show sign in button -->
                        </div>

                        <!-- Log Out Form -->
                        <form id="logout-form" action="{{route('logout')}}" method="POST" class="hidden">
                            @csrf
                        </form>

                        <!-- Search icon at collapse -->
                        <div class="ml-4 flow-root md:hidden">
                            <button type="button" class="group -m-2 flex items-center p-2">
                                <i class="fa-solid fa-magnifying-glass text-gray-500 text-xl group-hover:text-tamanuleaf"></i>
                                <span class="sr-only">Open search</span>
                            </button>
                        </div>

                        <!-- Cart -->
                        <div id="cart" class="ml-4 flow-root lg:ml-6 relative mr-3">
                            <a href="#" class="group -m-2 flex items-center p-2 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="43" class="h-6 w-6 text-gray-400 hover:text-tamanuleaf" viewBox="0 0 38 43"><path id="Subtraction_2" data-name="Subtraction 2" d="M35.487,43H2.513A2.372,2.372,0,0,1,0,40.807L3.768,16.678a2.373,2.373,0,0,1,2.513-2.194H9.735v-4.9a9.579,9.579,0,1,1,19.157,0v4.9h2.826a2.372,2.372,0,0,1,2.513,2.194L38,40.807A2.372,2.372,0,0,1,35.487,43ZM19.314,2.575A6.131,6.131,0,0,0,13.19,8.7v5.785H25.438V8.7A6.131,6.131,0,0,0,19.314,2.575Z" fill="currentColor"/></svg>
                                
                                <!-- Badge -->
                                <span class="cart-counter absolute -top-1 -right-1 bg-red-400 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                                <span class="sr-only">items in cart, view bag</span>
                            </a>
                        </div>


                    </div>
                </div> 
            </nav>
            <div class="announcement-bar">
                <div class="scroll-track">
                    @for ($i=0; $i < 6; $i++)
                        <div class="space-x-2">
                            <i class="fa-solid fa-truck-fast text-sandyshore"></i>
                            <span >
                                Free shipping on orders over $50.00
                            </span>
                        </div>
                    @endfor
                </div>
            </div>
        </header>
    </div>
    <main>
        {{$slot}}
    </main>

    <x-footer/>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>