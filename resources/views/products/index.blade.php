
@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>All Products</h2>
        <form action="{{ route('products.index') }}" method="GET" class="form-inline" id="searchForm">
            <input type="text" name="search" class="form-control" placeholder="" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr style="background-color: #143c58; color: white;">
                <th>No</th>
                <th>Image</th>
                <th>Product ID</th>
                <th>
                    <a href="{{ route('products.index', ['sort' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc']) }}">
                        Name
                        @if(request('sort') === 'name')
                            @if(request('sort_order') === 'asc')
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        @endif
                    </a>
                </th>
                <th>Description</th>
                <th>
                    <a href="{{ route('products.index', ['sort' => 'price', 'sort_order' => request('sort_order') === 'asc' && request('sort') === 'price' ? 'desc' : 'asc']) }}">
                        Price
                        @if(request('sort') === 'price')
                            @if(request('sort_order') === 'asc')
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        @endif
                    </a>
                </th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: auto;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $product->product_id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ Str::limit($product->description, 50, '...') }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock ?? 'N/A' }}</td>
                    <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
        </div>
        <div>
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection