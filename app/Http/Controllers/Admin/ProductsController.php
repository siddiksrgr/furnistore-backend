<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('name','LIKE','%'.$request->filter.'%')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|unique:products,name',
            'stock' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'descriptions' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Error !',
                'data'   => $validator->errors()
            ], 401);

        } else {

            $product = Product::create([
                'name' => $request->input('name'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
                'descriptions' => $request->input('descriptions'),
                'slug' => Str::slug($request->input('name')),
            ]);

            foreach($request->file('images') as $image) {
                $name = time().rand(1,50).'.'.$image->extension();
                $image->move('products', $name);  

                ProductImage::create([
                    'product_id' => $product->id,
                    'name' => $name,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil disimpan',
            ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrfail($id);

        $validator = Validator::make($request->all(), [
            'name'   => 'required|unique:products,name,'.$product->id,
            'stock' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'descriptions' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Error !',
                'data'   => $validator->errors()
            ], 401);

        } else {

            $product->update([
                'name' => $request->input('name'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
                'descriptions' => $request->input('descriptions'),
                'slug' => Str::slug($request->input('name')),
            ]);

            if ($request->hasFile('images')) {
                // delete old product images
                foreach($product->images as $oldImage){
                    File::delete(base_path('public/products/' . $oldImage->name)); 
                    $oldImage->delete();
                }

                // create new product images
                foreach($request->file('images') as $newImage) {
                    $name = time().rand(1,50).'.'.$newImage->extension();
                    $newImage->move('products', $name);  
    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'name' => $name,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diedit',
            ], 201);
        }
    }

    public function show($id)
    {
        $product = Product::with('images')->where('slug', $id)->firstOrFail();

        if ($product) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail product',
                'data'      => $product
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan',
            ], 404);
        }
    }

    public function destroy($id)
    {   
        $product = Product::findOrfail($id);

        foreach($product->images as $image){
            File::delete(base_path('public/products/' . $image->name)); 
            $image->delete();
        }
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ], 200);
    }
}