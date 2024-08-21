<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colors;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Colors::all();
        $productess  = Product::all();
        return view('product.index' , compact('colors','productess'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pname' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional validation for images
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'colors' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productdata = new Product();
        $productdata->pname = $request->input('pname');
        $productdata->price = $request->input('price');

        // Handle the file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/images', $filename);
            $productdata->image = $path;
        }

        $productdata->category = $request->input('category');
        $productdata->subcategory = $request->input('subcategory');
        $productdata->color = $request->input('colors');
        $productdata->description = $request->input('description');
        $productdata->save();

        return response()->json(['success' => 'Products added successfully!']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
        {
            $product = Product::findOrFail($id);
            return response()->json(['product' => $product]);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'pname' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'colors' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    
     
        $product = Product::findOrFail($id);
    
        
        $product->pname = $request->input('pname');
        $product->price = $request->input('price');
    
       
        if ($request->hasFile('image')) {
          
            if ($product->image) {
                Storage::delete($product->image);
            }
    
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/images', $filename);
            $product->image = $path;
        }
    
        $product->category = $request->input('category');
        $product->subcategory = $request->input('subcategory');
        $product->color = $request->input('colors');
        $product->description = $request->input('description');
    
        $product->save();
    
        return response()->json(['success' => 'Product updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
