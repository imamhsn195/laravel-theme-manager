@extends('theme-manager::admin.layout')

@section('header', 'Licenses')

@section('admin-content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
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
                            <td>{{ $license->theme?->name }}</td>
                            <td>{{ $license->domain }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($license->status) }}</span>
                            </td>
                            <td>{{ optional($license->expires_at)->toDateString() ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No licenses issued.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
