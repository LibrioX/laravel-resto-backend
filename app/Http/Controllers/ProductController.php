<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    //index
    public function index()
    {
        //paginate the products
        $products = Product::paginate(10);
        return view('pages.products.index', compact('products'));
    }

    //create
    public function create()
    {
        //categories
        $categories = Category::all();
        return view('pages.products.create', compact('categories'));
    }

    //store
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean'
        ]);

        //store the data
        $product = Product::create($request->all());

        //check if there is an image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image ='storage/products/' . $product->id . '.' .$image->getClientOriginalExtension();
            $product->save();
        }

        //redirect to the index page
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    //show
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.products.show', compact('product'));
    }

    //edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate the request
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean'
        ]);

        //find the product
        $product = Product::findOrFail($id);

        //update the product
        $product->update($request->all());

        //check if there is an image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        //redirect to the index page
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    //destroy
    public function destroy($id)
    {
        //find the product
        $product = Product::findOrFail($id);

        //delete the product
        $product->delete();

        //redirect to the index page
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}