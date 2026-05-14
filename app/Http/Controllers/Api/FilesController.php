<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\FileUploadLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\FileAdditionRequest;
use App\Http\Requests\Files\FileRequest;

class FilesController extends Controller
{
    private $file;

    public function __construct(FileUploadLogic $file)
    {
        $this->file = $file;
    }

    public function addFileAddition(FileAdditionRequest $request)
    {
        $data = $request->data();
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $result = $this->file->addFileAddition($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateFileAddition(FileAdditionRequest $request)
    {
        $data = $request->data();
        $result = $this->file->updateFileAddition($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateFileComment(FileRequest $request)
    {
        $data = $request->data();
        $result = $this->file->updateFileComment($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function pdf()
    {
        // $data = $request->data();
        return $this->file->downloadPdf();
        // return $result;
    }
}
