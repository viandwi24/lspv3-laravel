<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\JobCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schema_id, $competency_unit_id, $work_element_id)
    {
        // 
        $eloquent = JobCriteria::where('work_element_id', $work_element_id);
        $response = (new DataTable)
            ->of($eloquent)
            ->make();
        return $response;

        $data = apiDataTablesResponse(
            $eloquent
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
    public function store(Request $request, $schema_id, $competency_unit_id, $work_element_id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'title' => 'required|string|min:3|max:255'
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
        DB::transaction(function () use ($request, $work_element_id, &$created) {
            $created = JobCriteria::create(
                array_merge(
                    $request->only('title'),
                    ['work_element_id' => $work_element_id]
                )
            );
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
    public function show($schema_id, $competency_unit_id, $work_element_id, $job_criteria_id)
    {
        $job_criteria = JobCriteria::findOrFail($job_criteria_id);
        return apiResponse(
            $job_criteria,
            'get data success.',
            true
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $schema_id, $competency_unit_id, $work_element_id, $job_criteria_id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'title' => 'required|string|min:3|max:255'
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
        $job_criteria = JobCriteria::findOrFail($job_criteria_id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $job_criteria, &$update) {
            $update = $job_criteria->update($request->only('title'));
        });

        // 
        return apiResponse(
            $job_criteria,
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
    public function destroy($schema_id, $competency_unit_id, $work_element_id, $job_criteria_id)
    {
        $ids = explode(',', $job_criteria_id);
        $job_criterias = JobCriteria::findOrFail($ids);
        $destroy = $job_criterias->each(function ($job_criteria, $key) {
            $job_criteria->delete();
        });

        // 
        return apiResponse(
            $job_criterias->pluck('id'),
            'delete data success.',
            true
        );
    }
}