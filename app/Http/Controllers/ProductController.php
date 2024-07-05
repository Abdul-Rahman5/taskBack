<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $Products=Product::all();
        return ProductResource::collection($Products);
    }


    public function store(Request $request)
    {
        //vlidation
        $validator=  Validator::make($request->all(),[
            "name"=>"required|string|max:255 ",
            "desc"=>"required|string",
            "image"=>"required|image|mimes:png,jpg,jpeg",
            "price"=>"required|numeric"
        ]);
         //check
         if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors()
            ], 301);
        }
        //storage
        $imageName=Storage::putFile("Products",$request->image);
          //create
          Product::create([
            "name" => $request->name,
            "desc" => $request->desc,
            "image" => $imageName,
            "price" => $request->price,
        ]);

        //msg
        return response()->json([
            "success" => "data added successflly",
        ], 201);
    }


}
