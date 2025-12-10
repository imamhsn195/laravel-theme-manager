@extends('theme-manager::admin.layout')

@section('header', 'Installed Themes')

@section('admin-content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Version</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($themes as $theme)
                        <tr>
                            <td>{{ $theme->name }}</td>
                            <td>{{ $theme->slug }}</td>
                            <td>{{ $theme->version }}</td>
                            <td>
                                <span class="badge badge-{{ $theme->is_active ? 'success' : 'secondary' }}">
                                    {{ $theme->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No themes discovered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
