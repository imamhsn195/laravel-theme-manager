<div class="marketplace-dashboard">
    <section class="dashboard-overview">
        <h1>Your Marketplace Dashboard</h1>
        <div class="overview-cards">
            <article class="card">
                <h2>Total Purchases</h2>
                <strong>{{ $purchases->total() }}</strong>
            </article>
            <article class="card">
                <h2>Active Licenses</h2>
                <strong>{{ $licenses->count() }}</strong>
            </article>
        </div>
    </section>

    <section>
        <h2>Purchase History</h2>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Theme</th>
                    <th>Status</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->order_number }}</td>
                        <td>{{ $purchase->theme?->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($purchase->status) }}</td>
                        <td>
                            <a href="{{ route('marketplace.dashboard.download', $purchase->id) }}">Download</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No purchases yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $purchases->links() }}
    </section>

    <section>
        <h2>Licenses</h2>
        <table>
            <thead>
                <tr>
                    <th>Theme</th>
                    <th>Domain</th>
                    <th>Status</th>
                    <th>Expires</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licenses as $license)
                    <tr>
                        <td>{{ $license->theme?->name ?? 'N/A' }}</td>
                        <td>{{ $license->domain }}</td>
                        <td>{{ ucfirst($license->status) }}</td>
                        <td>{{ optional($license->expires_at)->toDateString() ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No licenses issued yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
