<header class="header">
    <div class="header__wrapper">
        <div class="header__logo">Logo</div>
        <div class="header__nav">
            <a href="/" class="header__nav-item">Home</a>
            @auth()
                <a href="/posts" class="header__nav-item">My Post</a>
                <a href="{{ route('logout') }}" class="header__nav-item">Logout</a>
            @endauth
            @guest()
                <a href="{{ route('showLoginForm') }}" class="header__nav-item">Login</a>
            @endguest
        </div>
    </div>
</header>

