<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Beyond the page</title>

  <link rel="icon" href="/favicon.ico" sizes="any" />
  <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
  <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


  @vite(['resources/css/app.css'])

</head>

<body class="bg-[#FDFDFC] flex flex-col min-h-screen ">

  <div class="navbar bg-[#171928] shadow-sm px-10">
    <div class="navbar-start">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
          </svg>
        </div>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
          <li><a>Books</a></li>
          <li><a>Authors</a></li>
          <li><a>Editors</a></li>
        </ul>
      </div>
      <a href="/" class="text-xl">Beyond The Page</a>
    </div>
    <div class="navbar-center hidden lg:flex">
      <ul class="menu menu-horizontal px-1">
        <li><a>Books</a></li>
        <li><a>Authors</a></li>
        <li><a>Editors</a></li>
      </ul>
    </div>
    <div class="navbar-end space-x-2">
      @if (Route::has('login'))
      @auth
      <a href="{{ url('/dashboard') }}">
        <button class="btn px-1">Dashboard</button>
      </a>
      @else
      <a href="{{ route('login') }}">
        <button class="btn">Log in</button>
      </a>
      @if (Route::has('register'))
      <a href="{{ route('register') }}">
        <button class="btn">Register</button>
      </a>
      @endif
      @endauth
      @endif
    </div>
  </div>

<div class="hero bg-[#F4F5FE] min-h-screen px-10">
<div class="hero-content flex-col lg:flex-row">
  <img src="" class="max-w-sm rounded-lg shadow-2xl" />
  <div>
    <h1 class="text-5xl font-bold text-black">Explore the best books available now!</h1>
    <p class="py-6 text-[#444A68]">
      Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
      quasi. In deleniti eaque aut repudiandae et a id nisi.
    </p>
    <button class="btn" style="background-color: #FE7F63; border-color: #FE7F63; color: white;">
      Our Books
    </button>
  </div>
</div>
</div>

<footer class="footer sm:footer-horizontal bg-[#171928] text-neutral-content p-10">
  <nav>
    <h6 class="footer-title">Beyond the Page | Library</h6>
    <a class="link link-hover">Books</a>
    <a class="link link-hover">Authors</a>
    <a class="link link-hover">Editors</a>
  </nav>
  <nav>
    <h6 class="footer-title">Company</h6>
    <a class="link link-hover">About us</a>
    <a class="link link-hover">Contact</a>
  </nav>
  <nav>
    <h6 class="footer-title">Legal</h6>
    <a class="link link-hover">Terms of use</a>
    <a class="link link-hover">Privacy policy</a>
    <a class="link link-hover">Cookie policy</a>
  </nav>
</footer>
</body>

</html>