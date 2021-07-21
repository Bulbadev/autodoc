<?php

namespace App\Http\Controllers\API\Frontend\V2;

use Bulbadev\Autodoc\ApiVersions\Example;
use Illuminate\Routing\Controller;

class ExampleController extends Controller
{

    //show documentation template
    public function index()
    {
        //filePath - link on method getJson
        return view('autodoc.documentation', ['filePath' => route('example.autodoc.json')]);
    }

    //must return json file
    public function getJson()
    {
        //route('example.autodoc.json')
        $api     = app(Example::class);
        $apiJson = $api->getFinalPath();

        return response()->file($apiJson);
    }
}