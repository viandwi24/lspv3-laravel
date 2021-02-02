<?php

use App\Helpers\DataTable;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

if (!function_exists('apiResponse'))
{
    /**
     * Make api response
     *
     * @param [type] $data
     * @param string $message
     * @param boolean $status
     * @param string $error_code
     * @param array $errors
     * @param integer $responseCode
     * @param array $customMeta
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|\Illuminate\Http\JsonResponse
     */
    function apiResponse($data, string $message, bool $status, string $error_code = null, $errors = null, int $responseCode = null, array $customMeta = [])
    {
        // if data is DataTable
        $datatable = null;
        if ($data instanceof DataTable || (isset($data['datatable']) && $data['datatable'] == true))
        {
            if ($data instanceof DataTable) $data = $data->make();
            $datatable = $data;
            $data = $data['data'];
            $customMeta['queries'] = $datatable['queries'];
            $customMeta['input'] = $datatable['input'];
            if (isset($datatable['totalRecords'])) $customMeta['totalRecords'] = $datatable['totalRecords'];
            if (isset($datatable['meta'])) $customMeta['meta'] = $datatable['meta'];
            if (isset($datatable['message']))
            {
                $error_code = 'datatable.error';
                $errors['datatable'] = $datatable['message'];
            }
        }


        // a data must extends JsonResource
        if (($data instanceof JsonResource)) $httpresource = $data;
        else $httpresource = new JsonResource($data);
    
        // clean data
        if ($data instanceof JsonResource) $data = $data->toArray(app('request'));
    
        // build meta
        $meta = [ 'status' => $status, 'message' => $message ];
        if ($error_code != null) { $meta['error_code'] = $error_code; $meta['errors'] = ($errors == null ? [] : $errors); }
        if (count($customMeta) > 0) $meta = array_merge($meta, $customMeta);
    
        // return
        return ($error_code == null) 
            ? (
                ($responseCode == null)
                ? $httpresource->additional($meta)->response()
                : $httpresource->additional($meta)->response()->setStatusCode($responseCode)
            )
            : response()->json(array_merge(['data' => $data], $meta), ($responseCode == null ? 200 : $responseCode))
                ->header('Content-Type', 'application/json')
                ->header('Accept', 'application/json');
    }
}