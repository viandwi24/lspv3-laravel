<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\PlaceController;
use App\Http\Controllers\Api\V1\Admin\ScheduleController;
use App\Http\Controllers\Api\V1\Admin\AccessionController;
use App\Http\Controllers\Api\V1\Admin\AssessorController;

use App\Http\Controllers\Api\V1\Admin\SchemaController;
use App\Http\Controllers\Api\V1\Admin\Schema\AccessionController as SchemaAccessionController;
use App\Http\Controllers\Api\V1\Admin\Schema\AssessorController as SchemaAssessorController;
use App\Http\Controllers\Api\V1\Admin\Schema\PlaceController as SchemaPlaceController;
use App\Http\Controllers\Api\V1\Admin\Schema\ScheduleController as SchemaScheduleController;
use App\Http\Controllers\Api\V1\Admin\Schema\FileController as SchemaFileController;
use App\Http\Controllers\Api\V1\Admin\CompetencyUnitController;
use App\Http\Controllers\Api\V1\Admin\JobCriteriaController;
use App\Http\Controllers\Api\V1\Admin\WorkElementController;

Route::group([
    'prefix' => '/admin',
    'as' => 'admin.'
], function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/places', PlaceController::class);
    Route::apiResource('/schedules', ScheduleController::class);
    Route::apiResource('/schemas', SchemaController::class);
    
    // api for schemas
    Route::group([
        'prefix' => '/schemas/{schema_id}',
        'as' => 'schema.'
    ], function () {
        Route::apiResource('/accessions', SchemaAccessionController::class)->except(['show', 'update']);
        Route::apiResource('/assessors', SchemaAssessorController::class)->except(['show', 'update']);
        Route::apiResource('/places', SchemaPlaceController::class)->except(['show', 'update']);
        Route::apiResource('/schedules', SchemaScheduleController::class)->except(['show', 'update']);
        Route::apiResource('/files', SchemaFileController::class)->except(['show']);

        Route::group([
            'prefix' => '/competency-units',
            'as' => 'competency_units.'
        ], function () {
            Route::apiResource('/', CompetencyUnitController::class)->parameters(['' => 'competency_unit']);
            Route::get('/full', [ CompetencyUnitController::class, 'full' ])->name('full'); 

            Route::group([
                'prefix' => '/{competency_unit}/work-elements',
                'as' => 'work_elements.'
            ], function () {
                Route::apiResource('/', WorkElementController::class)->parameters(['' => 'work_element']);

                Route::group([
                    'prefix' => '/{work_element}/job-criterias',
                    'as' => 'job_criterias.'
                ], function () {
                    Route::apiResource('/', JobCriteriaController::class)->parameters(['' => 'job_criteria']);
                });
            });
        });
    });

    // 
    Route::apiResource('/accessions', AccessionController::class);
    Route::apiResource('/assessors', AssessorController::class);
});