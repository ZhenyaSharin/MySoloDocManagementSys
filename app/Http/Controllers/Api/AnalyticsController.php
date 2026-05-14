<?php

namespace App\Http\Controllers\api;

use App\BusinessLogic\AssignmentLogic;
use App\BusinessLogic\DocumentLogic;
use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    private $docs;
    private $assigns;

    public function __construct(DocumentLogic $docs, AssignmentLogic $assigns)
    {
        $this->docs = $docs;
        $this->assigns = $assigns;
    }

    public function getDocumentsList()
    {
        $result = $this->docs->getDocumentsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentsList()
    {
        $result = $this->assigns->getAssignmentsList();
        return response()->json(["error" => "0", "result" => $result]);
    }
}
