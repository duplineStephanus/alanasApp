@props([
  'id',
  'variant' => 'default'
  ])

@php
  $fullName = Auth::user()->name;
  $firstName = explode(' ', trim($fullName))[0];
@endphp

<div class="relative">
  <button popovertarget="{{$id}}" class="inline-flex items-center gap-x-1 text-sm/6 font-semibold text-white">
    <span 
      class="
      font-medium
      {{ $variant === 'default' ? 'font-medium text-sm text-gray-600 hover:text-gray-800' : '-m-2 block p-2 text-gray-900' }}
      "
      >Hello, {{ $firstName }}
    </span>
    <i class="fa-solid fa-caret-down text-sm text-tamanuleaf"></i>
  </button>

  <el-popover id="{{$id}}" anchor="bottom" popover class="absolute right-0 mt-2 w-screen max-w-3xs overflow-visible bg-transparent px-4 transition transition-discrete [--anchor-gap:--spacing(1)] backdrop:bg-transparent open:flex data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
    <div class="w-screen max-w-md flex-auto overflow-hidden rounded-3xl bg-tamanuleaf text-sm/6 outline-1 -outline-offset-1 outline-white/10">
      <div class="p-2">
        <div class="group relative flex gap-x-6 rounded-lg p-2 hover:bg-white/5">
          <div>
            <a href="#" class="font-semibold text-white">
             Profile
              <span class="absolute inset-0"></span>
            </a>
            <p class="mt-1 text-gray-400">View your profile</p>
          </div>
        </div>
        <div class="group relative flex gap-x-6 rounded-lg p-2 hover:bg-white/5">
          
          <div>
            <a href="#" class="font-semibold text-white">
              Orders
              <span class="absolute inset-0"></span>
            </a>
            <p class="mt-1 text-gray-400">Viewe your order's history, status, and more.</p>
          </div>
        </div>
        <div class="group relative flex gap-x-6 rounded-lg p-2 hover:bg-white/5">
          
          <div>

            <button
                class="logout-btn width-full text-left text-white font-semibold">
                  Logout
                <span class="absolute inset-0"></span>
      
                <p class="mt-1 text-gray-400">Log out of your account</p>
            </button>
          </div>
        </div>
        
      </div>
      
    </div>
  </el-popover>
</div>
