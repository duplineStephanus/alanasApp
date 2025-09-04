<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="/src/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.typekit.net/apw7jrw.css">
    <title>Alana's App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sandyshore/20 text-coconuthusk">
    <div >
        <!-- Mobile menu -->
        <el-dialog>
            <dialog id="mobile-menu" class="backdrop:bg-transparent">
                <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-closed:opacity-0"></el-dialog-backdrop>
                <div tabindex="0" class="fixed inset-0 flex focus:outline-none">
                    <el-dialog-panel class="relative flex w-full max-w-xs transform flex-col overflow-y-auto bg-stone-50 pb-12 shadow-xl transition duration-300 ease-in-out data-closed:-translate-x-full">
                        <div class="flex px-4 pt-5 pb-2">
                            <button type="button" command="close" commandfor="mobile-menu" class="relative -m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            </button>
                        </div>

                        <div class="space-y-6 border-t border-gray-200 px-4 py-6 font-body">
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Shop</a>
                            </div>
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">About Us</a>
                            </div>
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Contact Us</a>
                            </div>
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">FAQ</a>
                            </div>
                        </div>

                        <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Sign in</a>
                            </div>
                            <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Register</a>
                            </div>
                        </div>
                    </el-dialog-panel>
                </div>
            </dialog>
        </el-dialog>

        <header class="relative bg-sandyshore/20">
            
            <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 my-1 py-1">
                <div class="">
                    <div class="flex h-16 items-center">
                        <!-- Logo -->
                    <div class="ml-4 flex lg:ml-0">
                        <a href="#">
                        <span class="sr-only">Your Company</span>
                        <img src="https://duplinestephanus.github.io/WebbApp-Files/logo/alanas-logo.png" class="h-15 w-auto"/>
                        </a>
                    </div>
                    <!-- Burger menu button -->
                    <button type="button" command="show-modal" commandfor="mobile-menu" class="relative rounded-md bg-sandyshore/20 p-2 text-gray-400">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open menu</span>
                        <i class="fa-solid fa-bars p-3 text-2xl text-tamanuleaf"></i>
                    </button>

                   <!-- Search form -->
                    <form class="hidden md:block w-xl mx-auto">   
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search products, contents..." required />
                            <button type="submit" class="absolute end-2.5 bottom-2.5 text-3xl text-gray-500 hover:text-tamanuleaf">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                    </form>

                    <div class="ml-auto flex items-center">
                        <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-800">Sign in</a>
                        <span aria-hidden="true" class="h-6 w-px bg-gray-200"></span>
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-800">Register</a>
                        </div>

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
                                <span class="absolute -top-1 -right-1 bg-red-400 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                                <span class="sr-only">items in cart, view bag</span>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
            </nav>
            <p class="flex h-10 items-center justify-center bg-tamanuleaf px-4 text-lg text-sandyshore sm:px-6 lg:px-8 font-body font-light">Get free delivery on orders over $50</p>
        </header>
    </div>
    <main>
        {{$slot}}
    </main>

    <footer class="border-t border-gray-200">
        <div class="flex flex-col items-center md:flex-row md:justify-between mx-12 pt-3 mb-5 mt-4">
            <div id="footerlinks" class="flex justify-start items-start space-x-10 mb-8">
                <div>
                    <h1 class="font-display text-2xl">HELP</h1>
                    <ul>
                        <li class="links"><a href="">CONTACT US</a></li>
                        <li class="links"><a href="">DELIVERY</a></li>
                        <li class="links"><a href="">RETURNS</a></li>
                        <li class="links"><a href="">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h1 class="font-display text-2xl">THE BRAND</h1>
                    <ul>
                        <li class="links"><a href="">OUR STORY</a></li>
                        <li class="links"><a href="">REVIEWS</a></li>
                        <li class="flex space-x-1.5 py-1">
                           <div><a href=""><i class="fa-brands fa-facebook text-blue-600/80 hover:text-blue-600"></i></a></div>
                           <div><a href=""><i class="fa-brands fa-youtube text-red-600/80 hover:text-red-600"></i></a></div>
                           <div><a href=""><i class="fa-brands fa-instagram text-pink-600/80 hover:text-pink-600"></i></a></div>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="quickcontuct-form" class="flex flex-col justify-center items-center bg-white shadow-sm w-xs rounded-2xl mb-4">
                <h1 class="font-display text-2xl mt-3">DON'T MISS A THING!</h1>
                <p class="font-body font-thin">Sign up for email updates.</p>
                <form class="w-2xs mb-1" action="">
                    <div class="flex flex-col space-y-2 mt-2">
                        <input id="name" class="border border-gray-400 rounded-lg font-body font-thin p-1 px-3" name="name" type="text" placeholder="Name">
                        <input id="email" class="border border-gray-400 rounded-lg font-body font-thin p-1 px-3" name="email" type="email" placeholder="Email">
                        <button class="btn-primary">SIGN UP</button>
                    </div>
                </form>

            </div>

        </div>

    </footer>

</body>
</html>