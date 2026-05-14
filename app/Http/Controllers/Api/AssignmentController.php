<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\AssignmentLogic;
use App\BusinessLogic\DocumentLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assignments\AssignmentControlRequest;
use App\Http\Requests\Assignments\AssignmentDeadlineRequest;
use App\Http\Requests\Assignments\AssignmentExecutorRequest;
use App\Http\Requests\Assignments\AssignmentRequest;
use App\Http\Requests\Assignments\AssignmentStatusRequest;

class AssignmentController extends Controller
{
    private $assignment;
    private $docs;

    public function __construct(AssignmentLogic $assignments, DocumentLogic $docs)
    {
        $this->assignments = $assignments;
        $this->docs = $docs;
    }

    public function getAssignmentList()
    {
        $result = $this->assignments->getAssignmentsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addNewAssignment(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->addNewAssignment($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentTypes()
    {
        $result = $this->assignments->getAssignmentTypes();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentsByAuthorId(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getAssignmentsByAuthorId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentsByExecutorId(AssignmentExecutorRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getAssignmentsByExecutorId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAssignment(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->updateAssignment($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentById(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getAssignmentById($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getNonViewedAssignmentsByExecutorId(AssignmentExecutorRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getNonViewedAssignmentsByExecutorId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentVersionsById(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocumentVersionsById(null, $data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function newDeadlineAssignment(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->newDeadlineAssignment($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getListByMainId(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getListByMainId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAssignmentStatus(AssignmentStatusRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->updateAssignmentStatus($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAssignmentStatusLog(AssignmentStatusRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getAssignmentStatusLog($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addNewControl(AssignmentControlRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->addNewControl($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateControl(AssignmentControlRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->updateControl($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
    // getAssignmentControls

    public function getAssignmentControls(AssignmentControlRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->getAssignmentControls($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAssignmentDeadline(AssignmentDeadlineRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->updateAssignmentDeadline($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addAssignmentDeadline(AssignmentDeadlineRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->addAssignmentDeadline($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAssignmentInfo(AssignmentRequest $request)
    {
        $data = $request->data();
        $result = $this->assignments->updateAssignmentInfo($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
}
