<header>
    <nav class="navbar">
        <div class="container">
            <div class="d-flex" style="justify-content: space-between; align-items: center; width: 100%;">
                <a href="{{ route('home') }}" class="navbar-brand">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <ul class="navbar-nav">
                    <li>
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>

