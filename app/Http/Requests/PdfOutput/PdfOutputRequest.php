<?php

namespace App\Http\Requests\PdfOutput;

use App\Dto\AgreementDto\AgreementUserDto;
use App\Dto\PdfOutputDto\PdfOutputDto;
use App\Dto\UserDto\UserDto;
use App\Http\Requests\BaseRequest;
use App\Dto\DocumentDto\DocumentDto;
use App\Dto\DiruserDto\DiruserDto;

class PdfOutputRequest extends BaseRequest
{
    private $documentDto;
    private $agreementUserDto;
    private $pdfOutputDto;
    private $userDto;
    private $diruserDto;

    public function __construct(PdfOutputDto $pdfOutputDto, DocumentDto $documentDto, DiruserDto $diruserDto)
    {
        // $this->agreementUserDto = $agreementUserDto;
        $this->pdfOutputDto = $pdfOutputDto;
        $this->documentDto = $documentDto;
        $this->diruserDto = $diruserDto;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->pdfOutputDto->typeTitle = $this->input('typeTitle') ?? null;
        $this->pdfOutputDto->typeId = $this->input('typeId') ?? null;
        $this->pdfOutputDto->authorSurname = $this->input('authorSurname') ?? null;
        $this->pdfOutputDto->authorFirstname = $this->input('authorFirstname') ?? null;
        $this->pdfOutputDto->authorPatronymic = $this->input('authorPatronymic') ?? null;

        $this->documentDto->id = $this->input('document')['id'];
        $this->documentDto->description = $this->input('document')['description'];
        $this->documentDto->authorId = $this->input('document')['authorId'];
        $this->documentDto->createdAt = $this->input('document')['created_at'] ?? null;
        $this->documentDto->updatedAt = $this->input('document')['updated_at'] ?? null;
        $this->documentDto->departmentId = $this->input('document')['departmentId'];
        $this->documentDto->orderNum = $this->input('document')['orderNum'] ?? null;
        $this->documentDto->deliveryId = $this->input('document')['deliveryId'];
        $this->documentDto->recorderId = $this->input('document')['recorderId'];
        $this->documentDto->baseId = $this->input('document')['baseId'] ?? null;
        $this->documentDto->baseAssignmentId = $this->input('document')['baseAssignmentId'] ?? null;
        $this->documentDto->linkedDocId = $this->input('document')['linkedDocId'] ?? null;
        $this->documentDto->typeId = $this->input('document')['typeId'];
        $this->documentDto->removed = $this->input('document')['removed'] ?? null;
        $this->documentDto->name = $this->input('document')['name'] ?? null;
        $this->documentDto->creationDate = $this->input('document')['creationDate'];
        $this->documentDto->closeDate = $this->input('document')['closeDate'];
        $this->documentDto->coExecutor = $this->input('document')['coExecutor'] ?? null;
        $this->documentDto->colName = $this->input('document')['colName'] ?? null;
        $this->documentDto->sumContract = $this->input('document')['sumContract'] ?? null;
        $this->documentDto->phases = $this->input('document')['phases'] ?? null;
        $this->documentDto->note = $this->input('document')['note'] ?? null;
        $this->documentDto->author = $this->input('document')['author'] ?? null;
        $this->documentDto->acqDate = $this->input('document')['acqDate'];
        if ($this->input('document')['executor'] != null) {
            $this->documentDto->executor = new UserDto;
            $this->documentDto->executor->id = $this->input('document')['executorUser']['id'] ?? null;
            $this->documentDto->executor->surname = $this->input('document')['executorUser']['surname'] ?? null;
            $this->documentDto->executor->firstname = $this->input('document')['executorUser']['firstname'] ?? null;
            $this->documentDto->executor->patronymic = $this->input('document')['executorUser']['patronymic'] ?? null;
        }
        $this->documentDto->addresser = $this->input('document')['addresser'] ?? null;
        $this->documentDto->customer = $this->input('document')['customer'] ?? null;
        $this->documentDto->signatory = $this->input('document')['signatory'] ?? null;
        $this->documentDto->letterExecutor = $this->input('document')['letterExecutor'] ?? null;
        $this->documentDto->count = $this->input('document')['count'] ?? null;
        $this->documentDto->delete = $this->input('document')['delete'] ?? null;
        $this->documentDto->info = $this->input('document')['info'] ?? null;
        $this->documentDto->orderable = $this->input('document')['orderable'] ?? null;
        $this->documentDto->deadline = $this->input('document')['deadline'] ?? null;
        
        $this->pdfOutputDto->document = $this->documentDto;
        $agreements = [];
        foreach ($this->input('agreements') as $item) {
            $this->agreementUserDto = new AgreementUserDto;
            $this->agreementUserDto->id = $item['id'] ?? null;
            $this->agreementUserDto->agreementId = $item['agreementId'];
            $this->agreementUserDto->userId = $item['userId'];
            $this->agreementUserDto->note = $item['note'] ?? null;
            $this->agreementUserDto->createdAt = $item['created_at'] ?? null;
            $this->agreementUserDto->updatedAt = $item['updated_at'] ?? null;
            $this->agreementUserDto->removed = $item['removed'] ?? null;
            $this->agreementUserDto->refusedAt = $item['refused_at'] ?? null;
            $this->agreementUserDto->delete = $item['delete'] ?? null;
            $this->agreementUserDto->approve = $item['approved_at'] ?? null;
            $this->agreementUserDto->viewed = $item['viewed'] ?? null;
            $this->agreementUserDto->count = $item['count'] ?? null;
            $this->agreementUserDto->order = $item['order'] ?? null;
            $this->agreementUserDto->surname = $item['surname'] ?? null;
            $this->agreementUserDto->firstname = $item['firstname'] ?? null;
            $this->agreementUserDto->patronymic = $item['patronymic'] ?? null;
            $this->userDto = new userDto;
            $this->userDto->id = $item['user']['id'] ?? null;
            $this->userDto->login = $item['user']['login'] ?? null;
            $this->userDto->surname = $item['user']['surname'] ?? null;
            $this->userDto->firstname = $item['user']['firstname'] ?? null;
            $this->userDto->patronymic = $item['user']['patronymic'] ?? null;
            $this->agreementUserDto->user = $this->userDto;
            $agreements[] = $this->agreementUserDto;
        }

        if ($this->input('diruser') != null) {
            $this->diruserDto->id = $this->input('diruser')['id'];
            $this->diruserDto->surname = $this->input('diruser')['user']['surname'] ?? null;
            $this->diruserDto->firstname = $this->input('diruser')['user']['firstname'] ?? null;
            $this->diruserDto->patronymic = $this->input('diruser')['user']['patronymic'] ?? null;
            $this->diruserDto->createdAt = $this->input('diruser')['created_at'] ?? null;
            $this->diruserDto->updatedAt = $this->input('diruser')['updated_at'] ?? null;
            $this->diruserDto->removed = $this->input('diruser')['removed'] ?? null;
            $this->diruserDto->departmentId = $this->input('diruser')['departmentId'] ?? null;
            $this->pdfOutputDto->diruser = $this->diruserDto;
        }

        $this->pdfOutputDto->agreements = $agreements;
        return $this->pdfOutputDto;
    }
}
