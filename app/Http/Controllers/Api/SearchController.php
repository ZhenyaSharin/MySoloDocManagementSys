<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\SearchLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;

class SearchController extends Controller
{
    private $search;

    public function __construct(SearchLogic $search)
    {
        $this->search = $search;
    }

    public function makeSearch(SearchRequest $request)
    {
        $data = $request->data();
        $result = $this->search->makeSearch($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
}
