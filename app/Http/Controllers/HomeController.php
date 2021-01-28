<?php
namespace App\Http\Controllers;

use App\Helpers\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        $format = env('VERSION_FORMAT_API', ':major.:minor.:patch-:prerelease.:buildmetadata');
        $data = [
            'name' => env('APP_NAME', 'elearning'),
            'version' => (new Version)->format($format),
            'api_version' => env('API_VERSION', 'v1'),
            'api-tech' => [
                'framework' => 'Laravel',
                'version' => app()->version()
            ]
        ];
        return Response::json($data);
    }

    public function v1()
    {
        $format = env('VERSION_FORMAT_API', ':major.:minor.:patch-:prerelease.:buildmetadata');
        $data = [
            'name' => env('APP_NAME', 'elearning'),
            'institute' => 'SMKN 1 MOJOKERTO',
            'version' => (new Version)->format($format),
            'api_version' => 'v1',
            'api-tech' => [
                'framework' => 'Laravel',
                'version' => app()->version()
            ]
        ];
        return Response::json($data);
    }
}