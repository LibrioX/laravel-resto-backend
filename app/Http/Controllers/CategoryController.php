<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index
    public function index()
    {
        //paginate the categories
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        //store the data
        $category = Category::create($request->all());

        //check if there is an image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        //redirect to the index page
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    //show
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.categories.show', compact('category'));
    }

    //edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        //store the data
        $category = Category::findOrFail($id);
        $category->update($request->all());

        //check if there is an image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        //redirect to the index page
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    //destroy
    public function destroy($id)
    {
        //find the category
        $category = Category::findOrFail($id);

        //delete the category
        $category->delete();

        //redirect to the index page
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}