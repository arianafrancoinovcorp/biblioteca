<!-- Sidebar -->
<aside
    class="w-full lg:w-64 flex-shrink-0 flex flex-col p-6 bg-gray-800 shadow-lg
       fixed lg:static inset-y-0 left-0 transform lg:translate-x-0 transition-transform duration-200 ease-in-out
       -translate-x-full lg:translate-x-0 z-30"
    id="sidebar">
    <h2 class="text-white font-semibold text-lg mb-8 tracking-wide uppercase">Library Management</h2>
    <ul class="flex flex-col space-y-3 flex-1">
        <li>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
               {{ request()->routeIs('admin.dashboard') ? 'active-border' : '' }}">
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.books.index') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
               {{ request()->routeIs('admin.books.index') ? 'active-border' : '' }}">
                Books
            </a>
        </li>
        <li>
            <a href="{{ route('users.index') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
               {{ request()->routeIs('users.index') ? 'active-border' : '' }}">
                Users
            </a>
        </li>
        <li>
            <a href="{{ route('requests.index') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
               {{ request()->routeIs('requests.index') ? 'active-border' : '' }}">
                Book Requests
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
       {{ request()->routeIs('admin.reviews.*') ? 'active-border' : '' }}">
                Reviews
            </a>
        </li>

        <li>
            <a href="{{ route('admin.orders') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
       {{ request()->routeIs('admin.orders.*') ? 'active-border' : '' }}">
                Orders
            </a>
        </li>
        <li>
            <a href="{{ route('admin.logs.index') }}"
                class="flex items-center px-4 py-2 rounded-r-md bg-gray-700 font-semibold text-white
       {{ request()->routeIs('admin.logs.*') ? 'active-border' : '' }}">
                Logs
            </a>
        </li>


    </ul>

    <div class="mt-auto bg-gray-700 rounded-lg p-4">
        <p class="uppercase text-xs text-gray-400 mb-2">lorem ipsum</p>
        <h3 class="text-white font-semibold mb-2">Lorem ipsum</h3>
        <p class="text-sm text-gray-300 mb-4">lorem ipsum </p>

        <a href="{{ route('profile.show') }}" class="inline-block bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded mr-2">
            Profile
        </a>

        <form method="POST" action="{{ route('logout') }}" class="inline-block">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-semibold py-2 px-4 rounded">
                Logout
            </button>
        </form>
    </div>
</aside>