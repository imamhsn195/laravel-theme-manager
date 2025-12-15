<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About</h3>
                <p>{{ config('app.name', 'Laravel') }} is a modern web application built with Laravel.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="{{ route('home') }}">Home</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@example.com</p>
                <p>Phone: +1 (555) 123-4567</p>
            </div>
            
            <div class="footer-section">
                <h3>Follow Us</h3>
                <a href="#" target="_blank">Facebook</a>
                <a href="#" target="_blank">Twitter</a>
                <a href="#" target="_blank">LinkedIn</a>
                <a href="#" target="_blank">GitHub</a>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </div>
</footer>

