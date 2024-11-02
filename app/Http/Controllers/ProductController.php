<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoteProductRequest;
use App\Models\Gallary;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Product::with('category', 'tags', 'gallaries')->latest('id')->paginate(10);
            return response()->json([
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'messenge' => 'Lỗi hệ thống'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoteProductRequest $request)
    {
        try {

            $products = $request->except(['gallaries', 'tag']);
            if ($request->hasFile('pro_image')) {
                $products['pro_image'] = Storage::put('products', $request->file('pro_image'));
            }
            $tags = $request->tags;

            DB::transaction(function () use ($products, $tags, $request) {
                $dataProduct = Product::query()->create($products);

                $dataProduct->tags()->attach($tags);

                if ($request->hasFile('gallaries')) {
                    foreach ($request->gallaries ?? [] as $value) {
                        $dataProduct->gallaries()->create(['image' => Storage::put('gallaries', $value)]);
                    }
                }
            });
            return response()->json([
                'messenge' => 'Thêm mới thành công !!!'
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return response()->json([
                'messenge' => 'Thêm mới không thành công !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
