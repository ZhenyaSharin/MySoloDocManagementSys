<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BusinessLogic\RelationLogic;
use App\Http\Requests\Relations\RelationRequest;
use App\Http\Requests\Relations\RelationArrayRequest;

class RelationController extends Controller
{
    private $relations;

    public function __construct(RelationLogic $relations)
    {
        $this->relations = $relations;
    }

    public function addNewRelation(RelationArrayRequest $request)
    {
    	$data = $request->data();
        $result = $this->relations->addNewRelation($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getRelationByDocAssignId(RelationRequest $request)
    {
    	$data = $request->data();
        $result = $this->relations->getRelationByDocAssignId($data->documentId1 , $data->assignmentId1);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateRelation(RelationRequest $request)
    {
    	$data = $request->data();
        $result = $this->relations->updateRelation($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getRelationsList()
    {
        $data = $request->data();
        $result = $this->relations->getRelationsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getRelationById(RelationRequest $request)
    {
        $data = $request->data();
        $result = $this->relations->getRelationById($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
}
