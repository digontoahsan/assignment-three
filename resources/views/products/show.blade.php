@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Product Details</h2>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Back to Product List</a>
    </div>
    <p><strong>Product ID:</strong> {{ $product->product_id }}</p>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ $product->price }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>
    @if($product->image)
        <p><strong>Image:</strong></p>
        <img src="{{ asset('images/' . $product->image) }}" alt="Product Image" width="200">
    @endif 
@endsection
