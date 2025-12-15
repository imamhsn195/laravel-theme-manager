@extends('theme-default::layout')

@section('content')
<div class="container">
    <div class="fade-in">
        <h1 style="margin-bottom: 2rem;">Dashboard</h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card">
                <div class="card-header">
                    <h3>Welcome Back!</h3>
                </div>
                <div class="card-body">
                    <p>Hello, {{ Auth::user()->name ?? 'User' }}!</p>
                    <p>This is your dashboard. You can customize this page to show your content.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Quick Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('home') }}" class="btn btn-primary" style="margin-bottom: 0.5rem; display: block; text-align: center;">
                        Go to Home
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%;">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Your Information</h3>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ Auth::user()->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email ?? 'N/A' }}</p>
                <p><strong>Member Since:</strong> {{ Auth::user()->created_at ? Auth::user()->created_at->format('F Y') : 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

