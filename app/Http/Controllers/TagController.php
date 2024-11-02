<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoteTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Tag::query()->latest('id')->paginate(10);
        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoteTagRequest $request)
    {
        try {
            $data = $request->validated();
            $category = Tag::query()->create($data);
            return response()->json([
                'messenge' => 'Thêm mới thành công !!!',
                'data' => $category
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
        try {
            $data = Tag::query()->findOrFail($id);
            return response()->json([
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            if ($th instanceof ModelNotFoundException) {
                return response()->json([
                    'messenge' => 'Không tồn tại'
                ], Response::HTTP_NOT_FOUND);
            }
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);

            return response()->json([
                'messenge' => 'Lỗi hệ thống'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $category = Tag::query()->findOrFail($id);
            $category->update($data);
            return response()->json([
                'messenge' => 'Update thành công !!',
                'data' =>  $category
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return response()->json([
                'messenge' => 'Update không thành công !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Tag::query()->findOrFail($id);
            $category->delete();
            return response()->json([
                'messenge' => 'Delete thành công !!!'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return response()->json([
                'messenge' => 'Delete không thành công !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
