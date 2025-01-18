<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
       
        $query = Product::query();
    
       
        if ($request->has('sort')) {
           
            $sortOrder = $request->get('sort_order', 'asc'); 
            if ($request->sort === 'name') {
                $query->orderBy('name', $sortOrder);
            } elseif ($request->sort === 'price') {
                $query->orderBy('price', $sortOrder);
            }
        }
    
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('product_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%") 
                  ->orWhere('price', 'like', "%{$search}%"); 
        }
    
        
        $products = $query->paginate(5); 
    
        return view('products.index', compact('products'));
    }

    public function create ()
    { 
        return view('products.create');

    }

    public function store(request $request)
    {
        {
            $request->validate([
                'product_id' => 'required|unique:products|max:255',
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'stock' => 'nullable|integer',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        
            $data = $request->all();
        
            
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $fileName);
                $data['image'] = $fileName;
            }
        
            Product::create($data);
        
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        return view(  'products.show', compact('product'));

    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));

    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_id' => 'required|max:255|unique:products,product_id,' . $id,
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['product_id', 'name', 'description', 'price', 'stock']);

        // Handle image upload and delete the old image if necessary
        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path('images/' . $product->image))) {
                File::delete(public_path('images/' . $product->image));
            }

            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if($product->image && File::exists('images/' . $product->image)){
            File::delete('images/' . $product->image);
        }
    
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');

    }

}