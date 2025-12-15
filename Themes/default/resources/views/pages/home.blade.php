@extends('theme-default::layout')

@section('content')
<div class="container">
    <div class="fade-in">
        <!-- Hero Section -->
        <section style="text-align: center; padding: 4rem 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1rem; margin-bottom: 3rem; color: white;">
            <h1 style="color: white; margin-bottom: 1rem;">Welcome to {{ config('app.name', 'Laravel') }}</h1>
            <p style="font-size: 1.25rem; margin-bottom: 2rem; color: rgba(255, 255, 255, 0.9);">
                Build amazing web applications with Laravel
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="background-color: white; color: #667eea;">
                    Go to Dashboard
                </a>
            @else
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('login') }}" class="btn btn-primary" style="background-color: white; color: #667eea;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline" style="border-color: white; color: white;">
                        Register
                    </a>
                </div>
            @endauth
        </section>

        <!-- Features Section -->
        <section style="margin-bottom: 3rem;">
            <h2 class="text-center" style="margin-bottom: 3rem;">Features</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="card">
                    <div class="card-header">
                        <h3>🚀 Fast & Reliable</h3>
                    </div>
                    <div class="card-body">
                        <p>Built with modern technologies for optimal performance and reliability.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>🔒 Secure</h3>
                    </div>
                    <div class="card-body">
                        <p>Enterprise-grade security features to keep your data safe.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>📱 Responsive</h3>
                    </div>
                    <div class="card-body">
                        <p>Fully responsive design that works on all devices and screen sizes.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>⚡ Easy to Use</h3>
                    </div>
                    <div class="card-body">
                        <p>Intuitive interface designed for both beginners and professionals.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>🎨 Customizable</h3>
                    </div>
                    <div class="card-body">
                        <p>Easily customize the look and feel to match your brand.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>📈 Scalable</h3>
                    </div>
                    <div class="card-body">
                        <p>Grows with your business, from startup to enterprise scale.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        @guest
        <section class="text-center" style="padding: 3rem; background-color: #f3f4f6; border-radius: 1rem; margin-bottom: 3rem;">
            <h2 style="margin-bottom: 1rem;">Ready to Get Started?</h2>
            <p style="margin-bottom: 2rem; color: var(--text-secondary);">
                Join thousands of users who are already building amazing things.
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary">
                Create Your Account
            </a>
        </section>
        @endguest
    </div>
</div>
@endsection

