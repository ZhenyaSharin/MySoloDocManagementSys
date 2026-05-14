<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\DocumentLogic;
use App\BusinessLogic\FileUploadLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acquaintances\AcquaintanceRequest;
use App\Http\Requests\Acquaintances\AcquaintancesArrayRequest;
use App\Http\Requests\Agreements\AgreementRequest;
use App\Http\Requests\Agreements\AgreementUserRequest;
use App\Http\Requests\Documents\DocumentRequest;
use App\Http\Requests\Files\FileAdditionRequest;
use App\Http\Requests\Files\FileUploadRequest;
use App\Http\Requests\PdfOutput\PdfOutputRequest;
use App\Http\Requests\Statuses\StatusRequest;
use App\Http\Requests\Users\UserRequest;
use App\Mail\SystemMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class DocumentController extends Controller
{
    private $docs;
    private $file;

    public function __construct(DocumentLogic $docs, FileUploadLogic $file)
    {
        $this->docs = $docs;
        $this->file = $file;
    }

    public function getDocumentsList(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocumentsList($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDeliveryTypesList()
    {
        $result = $this->docs->getDeliveryTypesList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDocumentTypesList()
    {
        $result = $this->docs->getDocumentTypesList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getUsersList()
    {
        $result = $this->docs->getUsersList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addNewDocument(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->addNewDocument($data);
        return response()->json(["error" => "0", "result" => $result]);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

    public function updateDocument(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->update($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDocsListByUserId(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocsListByUserId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreementListByUserId(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getAgreementListByUserId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDocumentById(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocumentById($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAgreement(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->updateAgreement($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreementResponsesByUserId(UserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getAgreementResponsesByUserId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addNewAgreementAndUser(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->addNewAgreementAndUser($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateDocumentIntoArchive(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->updateDocumentIntoArchive($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreementsByDocId(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getAgreementsByDocId($data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreementsAndUsersHistory(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getAgreementsAndUsersHistory($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getCountNonViewedAgreementsAndUsers(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getCountNonViewedAgreementsAndUsers($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    // public function openFile()
    // {
    //     $file = public_path().'\storage\docs\1629818480_doc_copy.docx';
    //     $file = File::get($file);
    //     $response = Response::make($file, 200);
    //     $response->header('Content-Type', 'application/msword');
    //     return $response;
    // }
    public function getDocsListByTypeId(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocsListByTypeId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDocumentVersionsById(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocumentVersionsById($data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getPdf()
    {
        // $data = $request->data();
        $result = $this->file->makeStamp();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreerStamp(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->file->makeAgreersStamp($data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreerList(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->file->makeAgreersList($data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAgreersListWithoutUsers(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->file->makeAgreersStampWithoutUsers($data->id);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getAcquaintancesList(AcquaintanceRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getAcquaintancesList($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addNewAcquaintances(AcquaintancesArrayRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->addNewAcquaintances($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAcquaintance(AcquaintanceRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->updateAcquaintance($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDirusersList()
    {
        $result = $this->docs->getDirusersList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function mailTo()
    {
        $result = Mail::to('admin@yourmail.ru')->send(new SystemMail('rgegrg'));
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getStatuses(StatusRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getStatuses($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateAgreementList(AgreementRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->updateAgreementList($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function removeAgreementUser(AgreementUserRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->removeAgreementUser($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
    // updateDocumentInfo

    public function updateDocumentInfo(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->updateDocumentInfo($data);
        return response()->json(["error" => "0", "result" => $result]);
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
    }

    public function getDocumentsListByStatus(DocumentRequest $request)
    {
        $data = $request->data();
        $result = $this->docs->getDocumentsListByStatus($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateDocumentFile(FileUploadRequest $request)
    {
        $data = $request->data();
        $result = $this->file->updateDocumentFile($data);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function watermark()
    {
        // $data = $request->data();
        $result = $this->file->watermark();
        // return response()->json(["error" => "0", "result" => $result]);
    }

    public function pdf(PdfOutputRequest $request)
    {
        $data = $request->data();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        date_default_timezone_set('Europe/Moscow');
        $date = date('d.m.Y H:i:s', time());

        $css = public_path('css\app.css');
        $pdf = \Pdf::loadView('pdfs.totalPdf', compact('data', 'date', 'css'))->setOptions(['defaultFont' => 'sans-serif']);

        $result = $pdf->output();

        return response()->json(["error" => "0", "result" => base64_encode($result)]);
    }
}
