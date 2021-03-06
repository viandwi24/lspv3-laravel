<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $eloquent = Place::query();
        $response = (new DataTable)
            ->of($eloquent)
            ->make();
        return apiResponse(
            $response,
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
            'address' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:12|max:13'
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
            $created = Place::create($request->only('name', 'address', 'phone'));
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
            'address' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:12|max:13'
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
        $place = Place::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $place, &$update) {
            $update = $place->update($request->only('name', 'address', 'phone'));
        });

        // 
        return apiResponse(
            $place,
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
        $places = Place::findOrFail($ids);
        $destroy = $places->each(function ($place, $key) {
            $place->delete();
        });

        // 
        return apiResponse(
            $places->pluck('id'),
            'delete data success.',
            true
        );
    }
}