<?php

namespace App\Http\Controllers;

use App\Product;
use App\Supplier;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class ProductsController extends Controller
{
    // Index page of products
    public function index()
    {
        $message = Session::get('message');
        $products = Product::all();

        return view('products.index', compact('products', 'message'));
    }


    // Create a new product item
    public function create($id = 0)
    {
        if($id != 0) $supplier = Supplier::find($id);

        return view('products.edit', compact('supplier'));
    }


    // Edit product
    public function edit($id, $page = 0)
    {
        $product = Product::find($id);

        if($page != 0) $supplier = Supplier::find($page);

        return view('products.edit', compact('product', 'supplier'));
    }


    // Save products
    public function save(Request $request)
    {
        $input = Input::all();

        $this->validate($request, [
            'name' => 'required',
            'supplier'   => 'required',
            'supplier_id'   => 'not_in:0',
            'length'   => 'numeric',
            'height'   => 'numeric',
            'width'   => 'numeric',
            'diameter'   => 'numeric',
            'unit_price_from'   => 'numeric',
            'unit_price_to'   => 'numeric',
            'minimum_order_quantity'   => 'numeric',
            'production_lead_time'   => 'numeric',
            'product_image' => 'image'
        ]);

        if($input['id'] == 0)
        {
            // New product
            $this->validate($request, [
                'name' => 'required|unique:products'
            ]);

            $product = Product::create(Input::all());

            if (!$product->id) {
                return redirect()->route('products.create')->with('message', 'Unable to create product, please try again.');
            }
        }
        else
        {
            // Store edited product
            $product = Product::find($input['id']);
            $product->update($input);
        }

        // Storing product image
        if (Input::hasFile('product_image'))
        {
            if (Input::file('product_image')->isValid())
            {
                $extension = Input::file('product_image')->getClientOriginalExtension();
                $file_name = $product->id.'.'.$extension;
                $destination_path = 'uploads/products';

                Input::file('product_image')->move($destination_path, $file_name);

                // Make a thumbnail picture
                $thumbnail = Image::make($destination_path.'/'.$file_name);
                $thumbnail->resize(55, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $thumbnail->save('uploads/products/thumb_'.$file_name);

                $product->product_image = $file_name;
                $product->save();
            }
        }

        if($input['page'] == 0)
        {
            return redirect('/products')->with('message', 'Product has been stored successfully');
        }
        else
        {
            return redirect('/suppliers/'.$input['page'].'/products')->with('message', 'Product has been stored successfully');
        }
    }


    // Delete selected product
    public function delete()
    {
        $input = Input::all();
        $id = $input['delete'];

        $product = Product::find($id);

        // Delete product image and its thumbnail if they exist
        if($product->product_image != null)
        {
            $path_image = 'uploads/products/'.$product->product_image;
            $path_thumbnail = 'uploads/products/thumb_'.$product->product_image;
            if (file_exists($path_image))
            {
                unlink($path_image);
            }
            if (file_exists($path_thumbnail))
            {
                unlink($path_thumbnail);
            }
        }

        $product->delete();

        if(isset($input['supplier_id']))
        {
            return redirect('/suppliers/'.$input['supplier_id'].'/products')->with('message', 'Selected product has been deleted successfully');
        }
        else
        {
            return redirect('/products')->with('message', 'Selected product has been deleted successfully');
        }
    }
}
