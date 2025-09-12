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

  <x-layouts.app.custom-header />

  <div class="hero bg-[#F4F5FE] py-20 px-10">
    <div class="hero-content flex-col lg:flex-row items-center">
      <img src="" class="max-w-sm rounded-lg shadow-2xl" />
      <div class="mt-8 lg:mt-0 lg:ml-10">
        <h1 class="text-5xl font-bold text-black">Explore the best books available now!</h1>
        <p class="py-6 text-[#444A68]">
          Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
          quasi. In deleniti eaque aut repudiandae et a id nisi.
        </p>
        <a href="{{ route('books.index') }}" class="btn" style="background-color: #FE7F63; border-color: #FE7F63; color: white;">
          Our Books
        </a>
      </div>
    </div>
  </div>

  <div class="px-10 py-8">
    <h2 class="text-5xl font-bold text-black mb-8 text-center">Latest Books</h2>

    <div class="flex flex-wrap justify-center gap-6">
      @foreach($latestBooks as $book)
      @if($book->cover_image)
      <a href="{{ route('books.show', $book->id) }}" class="group w-40 bg-[#F4F5FE] rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <figure class="h-56 overflow-hidden">
          <img src="{{ $book->cover_image }}" alt="{{ $book->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        </figure>
        <div class="p-3 text-center">
          <h2 class="text-xs md:text-sm font-semibold text-gray-800 break-words">
            {{ $book->name }}
          </h2>
        </div>
      </a>
      @endif
      @endforeach
    </div>
  </div>
  <x-layouts.app.custom-footer />
</body>

</html>