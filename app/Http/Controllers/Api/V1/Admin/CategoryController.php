<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $eloquent = Category::query();
        $response = (new DataTable)
            ->of($eloquent)
            ->make();
        return $response;

        $data = apiDataTablesResponse(
            $eloquent, 
            function ($q) {
                return $q->addColumn('tes', function () {
                    return "awekoawekoawe";
                });
            }
        );
        return apiResponse(
            $data,
            'get data success.',
            true
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'name' => 'required|string|min:2|max:30',
            'description' => 'required|string|min:3|max:255'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // 
        $created = null;
        DB::transaction(function () use ($request, &$created) {
            $created = Category::create($request->only('name', 'description'));
        });

        // 
        return apiResponse(
            $created,
            'create data success.',
            true
        );
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'name' => 'required|string|min:2|max:30',
            'description' => 'required|string|min:3|max:255'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // 
        $category = Category::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $category, &$update) {
            $update = $category->update($request->only('name', 'description'));
        });

        // 
        return apiResponse(
            $category,
            'update data success.',
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        $categories = Category::findOrFail($ids);
        $destroy = $categories->each(function ($category, $key) {
            $category->delete();
        });

        // 
        return apiResponse(
            $categories->pluck('id'),
            'delete data success.',
            true
        );
    }
}