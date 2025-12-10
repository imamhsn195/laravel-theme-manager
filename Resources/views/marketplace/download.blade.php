<div class="marketplace-download">
    <h1>Download {{ $purchase->theme?->name }}</h1>

    <section>
        @if($downloadUrl)
            <p>Your download is ready:</p>
            <a href="{{ $downloadUrl }}" class="btn">Download Theme</a>
        @endif

        @if($distributionMethod === 'zip' && $purchase->download_token)
            <p>Use the token below when requesting the ZIP download from support:</p>
            <code>{{ $purchase->download_token }}</code>
            <p>Expires: {{ optional($purchase->download_expires_at)->toDayDateTimeString() ?? 'N/A' }}</p>
        @elseif($distributionMethod === 'packagist' && $purchase->download_token)
            <p>Add the private repository with token:</p>
            <code>{{ $purchase->download_token }}</code>
            <p>{!! nl2br(e($purchase->notes)) !!}</p>
        @elseif(! $downloadUrl)
            <p>The download link for this theme is not yet available. Please contact support with order <strong>{{ $purchase->order_number }}</strong>.</p>
        @endif
    </section>

    @if($purchase->license)
        <h2>License Information</h2>
        <p>Domain: {{ $purchase->license->domain }}</p>
        <p>Status: {{ ucfirst($purchase->license->status) }}</p>
    @endif
</div>
