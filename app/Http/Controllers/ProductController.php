<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoteProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Gallary;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

            $gallary = [];
            if ($request->hasFile('gallaries')) {
                foreach ($request->gallaries ?? [] as $value) {
                    $gallary[] = Storage::put('gallaries', $value);
                }
            }
            $dataProduct = null;
            DB::transaction(function () use ($products, $tags, $gallary, &$dataProduct) {
                $dataProduct = Product::query()->create($products);

                $dataProduct->tags()->attach($tags);

                if (!empty($gallary)) {
                    foreach ($gallary as $value) {
                        $dataProduct->gallaries()->create(['image' => $value]);
                    }
                }
            });

            return response()->json([
                'messenge' => 'Thêm mới thành công !!!',
                'data' => $dataProduct
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            if ($request->hasFile('pro_image') && Storage::exists($products['pro_image'])) {
                Storage::delete($products['pro_image']);
            }

            foreach ($gallary as $value) {
                if ($request->hasFile('gallaries') && Storage::exists($value)) {
                    Storage::delete($value);
                }
            }

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
        try {

            $product = Product::with('category', 'tags', 'gallaries')->findOrFail($id);
            return response()->json([
                'data' => $product
            ]);
        } catch (\Throwable $th) {

            if ($th instanceof ModelNotFoundException) {

                return response()->json([
                    'messenge' => 'Sản phẩm không tồn tại !!!'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'messenge' => 'Lỗi hệ thống !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $productId = Product::query()->findOrFail($id);

            $productImage = $productId->pro_image;

            $product = $request->except(['gallaries', 'tags']);

            if ($request->hasFile('pro_image')) {
                $product['pro_image'] = Storage::put('products', $request->file('pro_image'));
            }

            $tags = $request->tags;

            $gallary = [];
            if ($request->hasFile('gallaries')) {
                foreach ($request->gallaries ?? [] as $key => $value) {
                    $gallary[$key] = Storage::put('gallaries', $value);
                }
            }

            DB::transaction(function () use ($product, $tags, $gallary, $productId, $productImage) {
                $update = $productId->update($product);

                if ($update && Storage::exists($productImage)) {

                    Storage::delete($productImage);
                }

                if (!empty($gallary)) {

                    foreach ($gallary ?? [] as $key => $value) {

                        $gallaryId = Gallary::query()->find($key);

                        if (!empty($gallaryId->image) && Storage::exists($gallaryId->image)) {
                            Storage::delete($gallaryId->image);
                        }

                        $productId->gallaries()->where('id', $key)->update(['image' => $value]);
                    }
                }

                $productId->tags()->sync($tags);
            });

            return response()->json([
                'messenge' => "Update thành công !!!",
                'data' => $productId->load('category', 'tags', 'gallaries')
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            if ($request->hasFile('pro_image') && Storage::exists($product['pro_image'])) {

                Storage::delete($product['pro_image']);
            }
            if ($request->hasFile('gallaries') && !empty($gallary)) {
                foreach ($gallary ?? [] as $key => $value) {
                    if (Storage::exists($value)) {
                        Storage::delete($value);
                    }
                }
            }
            return response()->json([
                'messenge' => "Update không thành công !!!"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productId = Product::query()->findOrFail($id);

            $productId->tags()->sync([]);

            $productId->gallaries()->delete();

            $productId->delete();

            return response()->json([
                'messenge' => 'Xóa Thành Công !!!'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'messenge' => 'Xóa Không Thành Công !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function search(Request $request)
    {
        try {
            $keyword = $request->query('search');
            if (!$keyword) {
                return response()->json([
                    'messenge' => 'Từ khóa rỗng !!!',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }
            $data = Product::query()->whereAny(['pro_name'], 'LIKE', "%$keyword%")->get();
            return response()->json([
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'messenge' => "Lỗi hệ thống !!!"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
