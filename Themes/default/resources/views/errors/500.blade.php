@extends('theme-default::layout')

@section('content')
<div class="container">
    <div style="text-align: center; padding: 4rem 0;">
        <h1 style="font-size: 6rem; color: var(--danger-color); margin-bottom: 1rem;">500</h1>
        <h2 style="margin-bottom: 1rem;">Server Error</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.125rem;">
            Something went wrong on our end. Please try again later.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            Go Back Home
        </a>
    </div>
</div>
@endsection

