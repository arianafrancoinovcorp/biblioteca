<div class="navbar bg-[#171928] shadow-sm px-10">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
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
    <a href="{{ route('dashboard') }}">
      <button class="btn btn-custom px-1">
        Dashboard
      </button>
    </a>
    @else
    <a href="{{ route('login') }}">
      <button class="btn btn-custom">
        Log in
      </button>
    </a>
    <a href="{{ route('register') }}">
      <button class="btn btn-custom">
        Register
      </button>
    </a>
    @endauth
  </div>

</div>