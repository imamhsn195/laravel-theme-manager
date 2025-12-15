@extends('theme-default::layout')

@section('content')
<div class="container">
    <div style="text-align: center; padding: 4rem 0;">
        <h1 style="font-size: 6rem; color: var(--primary-color); margin-bottom: 1rem;">404</h1>
        <h2 style="margin-bottom: 1rem;">Page Not Found</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.125rem;">
            Sorry, the page you are looking for could not be found.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            Go Back Home
        </a>
    </div>
</div>
@endsection

