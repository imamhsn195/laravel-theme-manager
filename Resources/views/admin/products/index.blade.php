@extends('theme-manager::admin.layout')

@section('header', 'Products')

@section('admin-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Product</h3>
                </div>
                <form method="POST" action="{{ route('theme-manager.products.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="slug" class="form-control" placeholder="Slug">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input name="price" class="form-control" type="number" step="0.01" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input name="stock" class="form-control" type="number" placeholder="Stock">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
