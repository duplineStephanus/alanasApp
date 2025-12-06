<!-- Mobile menu -->
<el-dialog>
    <dialog id="mobile-menu" class="backdrop:bg-transparent">
        <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-900 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] data-closed:opacity-0"></el-dialog-backdrop>
        <div tabindex="0" class="fixed inset-0 flex focus:outline-none">
            <el-dialog-panel class="relative flex w-full max-w-xs transform flex-col overflow-y-auto bg-stone-50 pb-12 shadow-xl transition-all duration-900 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] data-closed:-translate-x-full [will-change:transform]">
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
                        @auth
                            <x-user-menu id="mobile-user-menu" variant="mobile"></x-user-menu>
                        @endauth
                    </div>
                    <div class="flow-root">
                    <a href="/" 
                    class="-m-2 block p-2 font-medium text-gray-900">Shop</a>
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

                    @auth
                        <div class="flow-root">
                        <button class="logout-btn -m-2 block p-2 font-medium text-gray-900" command="close" commandfor="mobile-menu">Logout</button>
                        </div>
                    @else
                        <div class="flow-root">
                        <button class="signin-btn -m-2 block p-2 font-medium text-gray-900" command="close" commandfor="mobile-menu">Sign in</button>
                        </div>

                        <div class="flow-root">
                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Register</a>
                        </div>

                    @endauth

                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>