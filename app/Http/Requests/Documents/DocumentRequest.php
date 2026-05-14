<?php

namespace App\Http\Requests\Documents;

use App\Dto\DocumentDto\DocumentDto;
use App\Http\Requests\BaseRequest;
use App\Dto\FileDto\FileDto;

class DocumentRequest extends BaseRequest
{

    private $documentDto;
    private $fileDto;

    public function __construct(DocumentDto $documentDto)
    {
        $this->documentDto = $documentDto;
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
        // Дописать ошибки
        return [
            'id' => [
                'integer',
            ],
            'count' => [
                'integer',
            ],
            'file' => [
                // 'mimetypes:image/jpeg,image/png,image/gif, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf, application/zip, application/x-rar-compressed',
                'max:102400',
            ],
            'description' => [
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong data type',
            // '*.mimetypes' => 'Error. Wrong format of the file.',
            '*.max' => 'Error. Max size of the file is 100 MB.',
            'file.required' => 'You have to fill in the FILE field.',
        ];
    }

    public function data()
    {
        // print_r(json_decode($this->input('baseId')));
        $this->documentDto->id = $this->input('id');
        $this->documentDto->description = $this->input('description');
        $this->documentDto->authorId = $this->input('authorId');
        $this->documentDto->fileLink = $this->input('fileLink') ?? null;
        $this->documentDto->file = $this->file;
        $this->documentDto->createdAt = $this->input('createdAt') ?? null;
        $this->documentDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->documentDto->departmentId = $this->input('departmentId');
        $this->documentDto->orderNum = $this->input('orderNum') ?? null;
        $this->documentDto->deliveryId = $this->input('deliveryId');
        $this->documentDto->recorderId = $this->input('recorderId');
        $this->documentDto->baseId = $this->input('baseId') ?? null;
        $this->documentDto->baseAssignmentId = $this->input('baseAssignmentId') ?? null;
        $this->documentDto->linkedDocId = $this->input('linkedDocId') ?? null;
        $this->documentDto->typeId = $this->input('typeId');
        $this->documentDto->removed = $this->input('removed') ?? null;
        $this->documentDto->name = $this->input('name') ?? null;
        $this->documentDto->creationDate = $this->input('creationDate');
        $this->documentDto->closeDate = $this->input('closeDate');
        $this->documentDto->coExecutor = $this->input('coExecutor') ?? null;
        $this->documentDto->colName = $this->input('colName') ?? null;
        $this->documentDto->sumContract = $this->input('sumContract') ?? null;
        $this->documentDto->phases = $this->input('phases') ?? null;
        $this->documentDto->note = $this->input('note') ?? null;
        $this->documentDto->author = $this->input('author') ?? null;
        $this->documentDto->acqDate = $this->input('acqDate');
        $this->documentDto->executor = $this->input('executor') ?? null;
        $this->documentDto->addresser = $this->input('addresser') ?? null;
        $this->documentDto->customer = $this->input('customer') ?? null;
        $this->documentDto->signatory = $this->input('signatory') ?? null;
        $this->documentDto->letterExecutor = $this->input('letterExecutor') ?? null;
        $this->documentDto->agreeId = json_decode($this->input('agreeId'), true) ?? null;
        $this->documentDto->count = $this->input('count') ?? null;
        $this->documentDto->delete = $this->input('delete') ?? null;
        $this->documentDto->info = $this->input('info') ?? null;
        $this->documentDto->orderable = $this->input('orderable') ?? null;
        $this->documentDto->diruser = json_decode($this->input('diruser'), true) ?? null;
        $this->documentDto->fileFormat = $this->input('fileFormat') ?? null;
        $this->documentDto->deadline = $this->input('deadline') ?? null;
        $this->documentDto->fileId = $this->input('fileId') ?? null;
        $this->documentDto->statusId = $this->input('statusId') ?? null;
        $this->documentDto->ascDesc = $this->input('ascDesc') ?? 'DESC';
        $this->documentDto->outerNum = $this->input('outerNum') ?? null;
        $this->documentDto->outerDate = $this->input('outerDate') ?? null;
        if (!empty($this->file('addFiles'))) {
            $files = [];
            $fileDto = new FileDto;
            $files = $this->file('addFiles');
            for ($i = 0; $i < count($files); $i++) {
                $fileDto = new FileDto;
                $fileDto->id = $files[$i]->id ?? null;
                $fileDto->file = $files[$i];
                $fileDto->format = $files[$i]->format ?? null;
                $fileDto->createdAt = $files[$i]->createdAt ?? null;
                $fileDto->updatedAt = $files[$i]->updatedAt ?? null;
                $fileDto->removed = $files[$i]->removed ?? null;
                $fileDto->type = $files[$i]->type ?? null;
                $fileDto->comment = $this->addComments[$i] ?? null;
                $fileDto->delete = $files[$i]->delete ?? null;
                $this->documentDto->addFiles[] = $fileDto;
            }
        }
        return $this->documentDto;
    }
}
