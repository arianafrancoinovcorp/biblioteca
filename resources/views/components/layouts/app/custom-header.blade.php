<div class="navbar bg-[#171928] shadow-sm w-full px-10">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 w-52 p-2 shadow">
        <li><a href="{{ route('books.index') }}">Books</a></li>
        <li><a href="{{ route('authors.index') }}">Authors</a></li>
        <li><a href="{{ route('publishers.index') }}">Publishers</a></li>
      </ul>
    </div>
    <a href="/" class="text-xl text-white">Beyond The Page</a>
  </div>

  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      <li><a href="{{ route('books.index') }}">Books</a></li>
      <li><a href="{{ route('authors.index') }}">Authors</a></li>
      <li><a href="{{ route('publishers.index') }}">Publishers</a></li>
    </ul>
  </div>

  <div class="navbar-end space-x-2">
    @auth

    @if(Auth::user()->role === 'admin')
    <a href="{{ route('admin.dashboard') }}">
      <button class="btn btn-custom">Dashboard</button>
    </a>
    @else
    <a href="{{ route('users.dashboard') }}">
      <button class="btn btn-custom">Dashboard</button>
    </a>
    @endif
    <a href="{{ route('cart.index') }}" class="relative flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V5a4 4 0 10-8 0v6M5 11h14l1 10H4L5 11z" />
      </svg>

      @php
      $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
      @endphp
      @if($cartCount > 0)
      <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
        {{ $cartCount }}
      </span>
      @endif
    </a>
    @else
    <a href="{{ route('login') }}">
      <button class="btn btn-custom">Log in</button>
    </a>
    <a href="{{ route('register') }}">
      <button class="btn btn-custom">Register</button>
    </a>
    @endauth
  </div>
</div>