<?php

namespace App\BusinessLogic;

use App\Dto\FileDto\FileAdditionDto;
use App\Dto\FileDto\FileDto;
use App\Dto\FileDto\FileUploadDto;
use App\Exceptions\BusinessLogicException;
use App\Models\Repositories\Contracts\AgreementsRepositoryInterface;
use App\Models\Repositories\Contracts\FilesRepositoryInterface;
use App\PDFEditor\CustomPDF;
use FilippoToso\PdfWatermarker\Facades\TextWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Throwable;

class FileUploadLogic
{
    protected $customPdf;
    protected $font = 'arial';
    protected $fontFile = 'arial.php';
    protected $fontBold = 'arial-bold';
    protected $fontFileBold = 'arial-bold.php';
    protected $agrs;
    protected $file;

    public function __construct(CustomPDF $customPdf, AgreementsRepositoryInterface $agrs, FilesRepositoryInterface $file)
    {
        $this->customPdf = $customPdf;
        $this->agrs = $agrs;
        $this->file = $file;
    }

    public function fileUpload($data)
    {
        try {
            $size = config('fileupload.size');
            $thumbSizeY = config('fileupload.thumbnail_size_y');
            $thumbSizeX = config('fileupload.thumbnail_size_x');
            $visibility = config('fileupload.visibility');
            $ext = strtolower($data->getClientOriginalExtension());
            $file = [];
            $fileName = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
            $origName = Str::slug($fileName, '_');
            $name = $origName . '_' . $ext . '_' . time() . '.' . $ext;
            // echo($data->getMimeType());
            if (($data->getMimeType() == 'image/jpeg') || ($data->getMimeType() == 'image/png') || ($data->getMimeType() == 'image/gif')) {

                $nameThumb = $origName . '_' . $ext . '_' . time()  . '_thumb' . '.' . $ext;

                // $img = Image::make($data);
                // $imgNorm = $img->stream();
                // $imgThumb = $img->resize(297, 297, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->stream();
                $manager = new ImageManager(new Driver());
                $img = $manager->read($data);
                
                $fileUrl = $name;
                $fileThumbUrl = 'thumbnails/' . $nameThumb;
                // $storage = Storage::disk('public_images');
                // $storage->put($fileUrl, $imgNorm->__toString());
                // $storage->put($fileThumbUrl, $imgThumb->__toString());
                $img->save(public_path('storage/images/' . $fileUrl));
                $img->cover(297, 297)->save(public_path('storage/images/' . $fileThumbUrl));

                $nuNameArr = explode('.', $fileUrl);
                array_pop($nuNameArr);
                $nuName = implode('', $nuNameArr);

                $file['file'] = $nuName;
                $file['format'] = $ext;
            } elseif (($data->getMimeType() == 'application/msword') || ($data->getMimeType() == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') || ($data->getMimeType() == 'text/rtf') || ($data->getMimeType() == 'text/richtext') || ($data->getMimeType() == 'application/rtf') || ($data->getMimeType() == 'application/doc') || ($data->getMimeType() == 'application/octet-stream') || ($data->getMimeType() == 'application/pdf')) {
                if ($data->getMimeType() == 'application/pdf') {
                    $nameOrig = $origName . '_' . $ext . '_' . time() . '_orig' . '.' . $ext;
                    $storage = Storage::disk('public_pdf');
                    $storage->put($name, $data->getContent());
                    $storage->put($nameOrig, $data->getContent());
                    $nuNameArr = explode('.', $name);
                    array_pop($nuNameArr);
                    $nuName = implode('', $nuNameArr);

                    $file['file'] = $nuName;
                    $file['format'] = $ext;
                    $this->makeStamp($file['file']);
                } else {
                    $storage = Storage::disk('public_pdf');
                    $storage->put($name, $data->getContent());

                    $nuNameArr = explode('.', $name);
                    array_pop($nuNameArr);

                    $nuName = implode('', $nuNameArr);

                    $file['file'] = $nuName;
                    $file['format'] = $ext;

                    exec('doc2pdf ' . storage_path('/app/public/pdfs/') . $name);

                    $this->makeStamp($file['file']);
                }
            } else {
                // echo "string";
                $storage = Storage::disk('public_etc');
                $storage->put($name, $data->getContent());
                $nuNameArr = explode('.', $name);
                array_pop($nuNameArr);
                $nuName = implode('', $nuNameArr);
                $file['file'] = $nuName;
                $file['format'] = $ext;
            }
            return $file;
        } catch (Throwable $e) {
            throw new BusinessLogicException('BusinessLogicException ' . $e->getMessage());
        }
    }

    public function fileUploadAddition($data)
    {
        try {
            $visibility = config('fileupload.visibility');
            $ext = $data->getClientOriginalExtension();
            $file = [];
            $fileName = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
            $origName = Str::slug($fileName, '_');
            $name = $origName . '_' . $ext . '_' . time() . '.' . $ext;
            $storage = Storage::disk('public_add');
            $storage->put($name, $data->getContent());
            $nuNameArr = explode('.', $name);
            array_pop($nuNameArr);
            $nuName = implode('', $nuNameArr);
            $file['file'] = $nuName;
            $file['format'] = $ext;
            return $file;
        } catch (Throwable $e) {
            throw new BusinessLogicException('BusinessLogicException ' . $e->getMessage());
        }
    }

    public function makeStamp($file)
    {
        try {
            if (isset($file)) {
                // echo "string";
                $path = storage_path('/app/public/pdfs/' . $file . '.pdf');

                $pdf = $this->customPdf;

                $pdf->AddFont($this->font, '', $this->fontFile);
                $pdf->SetFont($this->font, '', 12);

                $count = $pdf->setSourceFile($path);

                $tplt = $pdf->importPage(1);

                $size = $pdf->getTemplateSize($tplt);

                if ($size['width'] > $size['height']) {
                    $posY = 120;
                    $posX = 180;
                    $pdf->AddPage('L', array($size['width'], $size['height']));
                } else {
                    $posX = 120;
                    $posY = 220;
                    $pdf->AddPage('P', array($size['width'], $size['height']));
                }

                $pdf->useTemplate($tplt);

                $cellWidth = 30;
                $cellHeight = 8;

                $pdf->SetTextColor(30, 144, 255);

                $pdf->SetDrawColor(30, 144, 255);

                $pdf->SetAlpha(0.8);

                $pdf->SetLineWidth(0.8);

                $date = date("d.m.Y");

                $pdf->Rect($posX, $posY, $cellWidth * 2, $cellHeight * 2, 'D');

                $pdf->SetXY($posX + 1, $posY + 1);

                $pdf->SetLineWidth(0.1);

                $str = $pdf->cell($cellWidth - 1, $cellHeight - 1, "Добавлено:", 'LT', 0, 'C');
                $str = $pdf->cell($cellWidth - 1, $cellHeight - 1, $date, 'RT', 0, 'C');
                $str = $pdf->Ln();
                $str = $pdf->SetX($posX + 1);
                $str = $pdf->cell(($cellWidth * 2) - 2, $cellHeight - 1, 'Система документооборота', 'LBR', 0, 'C');

                $pdf->Write(0, iconv('utf-8', 'windows-1251', $str));

                for ($i = 2; $i <= $count; $i++) {
                    $templateId = $pdf->importPage($i);
                    // get the size of the imported page
                    $size = $pdf->getTemplateSize($templateId);

                    // add a page with the same orientation and size
                    $pdf->AddPage($size['orientation'], $size);

                    // use the imported page
                    $pdf->useTemplate($templateId);
                }
                // $pdf->Output('F',  public_path('storage\pdfs\example1.pdf'));
                if ($pdf->Output('F', $path)) {
                    return true;
                }

                // $pdf->useTemplate($page, null, null, null, 210, true);

                // $pdf->SetXY(90, 170);

                // $str = "Добавлено";

                // mb_convert_encoding($str, 'UTF-8');

                // $pdf->Write(0.3, $str);

                // $pdf->Output('F', "Demotest.pdf");
                return false;
            }
            return false;
        } catch (Throwable $e) {
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function makeAgreersStamp(int $id)
    {
        $data = $this->agrs->agreementsByDocIdWithInfo($id);

        $path = storage_path('/app/public/pdfs/' . $data['file']['file'] . '.pdf');

        $pdf = $this->customPdf;

        $pdf->AddFont($this->font, '', $this->fontFile);
        $pdf->SetFont($this->font, '', 12);

        $count = $pdf->setSourceFile($path);

        $tplt = $pdf->importPage(1);

        $size = $pdf->getTemplateSize($tplt);

        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
            $posX = 10;
            $posY = 90;
        } else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
            $posX = 10;
            $posY = 190;
        }

        $pdf->useTemplate($tplt);

        $cellWidth = 60;
        $cellHeight = 10;
        $numWidth = 6;

        $pdf->SetTextColor(30, 144, 255);

        $pdf->SetDrawColor(30, 144, 255);

        $pdf->SetAlpha(0.8);

        $date = date("d.m.Y");

        $cnt = count($data['users']);

        $pdf->SetXY($posX, $posY - ($cellHeight * ($cnt - 1)));

        $str = $pdf->cell($numWidth, $cellHeight, "", 0, 0, 'L');
        $str = $pdf->cell($cellWidth, $cellHeight, "Ф.И.О.", 0, 0, 'L');
        $str = $pdf->cell($cellWidth, $cellHeight, "Дата и время согласования", 0, 0, 'C');
        foreach ($data['users'] as $item) {
            $apprTime = $item['approved_at'];
            $out = $this->formateDate($item['approved_at']);

            if ($item['order'] != null) {
                $str = $pdf->Ln();
                $str = $pdf->cell($numWidth, $cellHeight, $item['order'], 0, 0, 'C');
                $str = $pdf->cell($cellWidth, $cellHeight, $item['user']['surname'] . ' ' . $item['user']['firstname'] . ' ' . $item['user']['patronymic'], 0, 0, 'L');
                $str = $pdf->cell($cellWidth, $cellHeight, $out, 0, 0, 'C');
            } else {
                $str = $pdf->Ln();
                $str = $pdf->cell($numWidth, $cellHeight, '', 0, 0, 'L');
                $str = $pdf->cell($cellWidth, $cellHeight, $item['user']['surname'] . ' ' . $item['user']['firstname'] . ' ' . $item['user']['patronymic'], 0, 0, 'L');
                $str = $pdf->cell($cellWidth, $cellHeight, $out, 0, 0, 'C');
            }
        }
        $pdf->Write(0, iconv('utf-8', 'windows-1251', $str));

        for ($i = 2; $i <= $count; $i++) {
            $templateId = $pdf->importPage($i);
            // get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            // add a page with the same orientation and size
            $pdf->AddPage($size['orientation'], $size);

            // use the imported page
            $pdf->useTemplate($templateId);
        }

        if ($pdf->Output('I', $data['document']['file'] . '_stamped.pdf')) {
            return true;
        }
        return false;
    }

    public function makeAgreersStampWithoutUsers(int $id)
    {
        $data = $this->agrs->agreementsByDocIdWithInfo($id);

        $path = storage_path('/app/public/pdfs/' . $data['file']['file'] . '.pdf');

        $pdf = $this->customPdf;

        $pdf->AddFont($this->font, '', $this->fontFile);
        $pdf->SetFont($this->font, '', 12);

        $count = $pdf->setSourceFile($path);

        $tplt = $pdf->importPage(1);

        $size = $pdf->getTemplateSize($tplt);

        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
        } else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
        }

        $pdf->useTemplate($tplt);

        $cellWidth = 50;
        $cellHeight = 10;
        $posX = 20;
        $posY = 190;

        $pdf->SetTextColor(30, 144, 255);

        $pdf->SetDrawColor(30, 144, 255);

        $pdf->SetAlpha(0.8);

        $date = date("d.m.Y");

        $cnt = count($data['users']);

        $pdf->SetXY($posX, $posY - ($cellHeight * ($cnt - 1)));

        $pdf->SetLineWidth(0.8);

        $str = $pdf->cell($cellWidth, $cellHeight, "БЕЗ СОГЛАСОВАНИЯ", 1, 0, 'C');

        $pdf->Write(0, iconv('utf-8', 'windows-1251', $str));

        for ($i = 2; $i <= $count; $i++) {
            $templateId = $pdf->importPage($i);
            // get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            // add a page with the same orientation and size
            $pdf->AddPage($size['orientation'], $size);

            // use the imported page
            $pdf->useTemplate($templateId);
        }

        if ($pdf->Output('I', $data['document']['file'] . '_stamped.pdf')) {
            return true;
        }
        return false;
    }

    public function makeAgreersList(int $id)
    {
        // Формирование страницы PDF
        $data = $this->agrs->agreementsByDocIdWithInfo($id);

        $path = storage_path('/app/public/pdfs/test1.pdf');

        $pdf = $this->customPdf;

        $pdf->AddFont($this->fontBold, '', $this->fontFileBold);
        $pdf->AddFont($this->font, '', $this->fontFile);
        $pdf->SetFont($this->fontBold, '', 14);

        $pdf->setSourceFile($path);

        $tplt = $pdf->importPage(1);

        $size = $pdf->getTemplateSize($tplt);

        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
        } else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
        }
        // $pdf->AddPage($size['orientation'], $size);

        $pdf->SetY(20);

        $str = $pdf->cell($size['width'] - 20, 10, 'ЛИСТ СОГЛАСОВАНИЯ', 0, 1, 'C');
        $cellHeightInfo = 8;
        $cellHeightTh = 10;
        $cellHeightTr = 10;
        $noteCellWidthTr = 80;

        $dateMain = $this->formateDate($data['created_at']);

        $pdf->SetXY(10, 40);
        $pdf->SetFont($this->fontBold, '', 11);
        $str = $pdf->cell(27, $cellHeightInfo, 'К документу: ', 0, 0, 'L');

        $pdf->SetFont($this->font, '', 11);
        $str = $pdf->cell('', $cellHeightInfo, $data['document']['description'], 0, 1, 'L');
        // strlen(
        $descLen = strlen($data['document']['description']);
        // echo($descLen);
        $pdf->SetFont($this->fontBold, '', 11);
        $str = $pdf->cell(32, $cellHeightInfo, 'Тип документа: ', 0, 0, 'L');
        $pdf->SetFont($this->font, '', 11);
        $str = $pdf->cell('', $cellHeightInfo, $data['documentType']['title'], 0, 1, 'L');

        $pdf->SetFont($this->fontBold, '', 11);
        $str = $pdf->cell(81, $cellHeightInfo, 'Дата и время отправки на согласование: ', 0, 0, 'L');
        $pdf->SetFont($this->font, '', 11);
        $str = $pdf->cell('', $cellHeightInfo, $dateMain, 0, 1, 'L');

        $pdf->SetFont($this->fontBold, '', 11);
        $str = $pdf->cell(55, $cellHeightInfo, 'Автор карточки документа: ', 0, 0, 'L');
        $pdf->SetFont($this->font, '', 11);
        $str = $pdf->cell('', $cellHeightInfo, $data['author']['surname'] . ' ' . $data['author']['firstname'] . ' ' . $data['author']['patronymic'], 0, 1, 'L');

        $pdf->SetXY(10, 100);

        $pdf->SetFont($this->fontBold, '', 11);

        // $str = $pdf->cell(30, $cellHeightTh, 'Должность', 'LTB', 0, 'C');
        $str = $pdf->cell(40, $cellHeightTh, 'Ф.И.О.', 'LTB', 0, 'C');
        $str = $pdf->cell(40, $cellHeightTh, 'Дата решения', 'LTB', 0, 'C');
        $str = $pdf->cell(30, $cellHeightTh, 'Статус', 'LTB', 0, 'C');
        $str = $pdf->cell($noteCellWidthTr, $cellHeightTh, 'Примечание', 1, 0, 'C');

        $pdf->SetFont($this->font, '', 11);

        $tableHeight = $pdf->GetY();
        // всё переделать, как на видео https://youtu.be/utjJe90MeEw
        $h = 0;
        $rows = 0;

        for ($i = 0; $i < count($data['users']); $i++) {
            $out = $this->formateDate($data['users'][$i]['approved_at']);
            if (isset($data['users'][$i]['note'])) {
                $note = $data['users'][$i]['note'];
                $noteAlign = 'L';
            } else {
                $note = '...';
                $noteAlign = 'C';
            }
            if ($pdf->GetStringWidth($data['users'][$i]['note']) < $noteCellWidthTr) {
                $rows = 1;
                $noteAlign = 'C';
            } else {
                $textLen = strlen($data['users'][$i]['note']);
                $errMargin = 10;
                $startChar = 0;
                $maxChar = 0;
                $textArr = [];
                $tmpStr = '';
                $noteAlign = 'L';

                // $rows = 8;
                while ($startChar < $textLen) {
                    while ($pdf->GetStringWidth($tmpStr) < ($noteCellWidthTr - $errMargin) && ($startChar + $maxChar) < $textLen) {
                        $maxChar++;
                        $tmpStr = substr($note, $startChar, $maxChar);
                    }
                    $startChar = $startChar + $maxChar;
                    array_push($textArr, $tmpStr);
                    // $rows++;
                    $maxChar = 0;
                    $tmpStr = '';
                }
                // echo '<pre>';
                // print_r($textArr);
                // echo '</pre>';
                $rows = count($textArr);
            }
            $rowHeight = $cellHeightTr * $rows;
            // echo $rowHeight;
            // Строка ячеек
            $str = $pdf->Ln();
            $str = ($i > 0) ? $pdf->SetY($tableHeight + $cellHeightTr + $h + ($cellHeightTh - $cellHeightTr)) : $pdf->SetY($tableHeight + $cellHeightTh);

            $str = $pdf->cell(40, $rowHeight, $data['users'][$i]['user']['surname'] . ' ' . mb_substr($data['users'][$i]['user']['firstname'], 0, 1) . '. ' . mb_substr($data['users'][$i]['user']['patronymic'], 0, 1) . '.', 'LB', 0, 'C');
            $str = $pdf->cell(40, $rowHeight, $out, 'LB', 0, 'C');
            $pdf->SetFont($this->fontBold, '', 11);
            $str = $pdf->cell(30, $rowHeight, 'Одобрено', 'LB', 0, 'C');
            $pdf->SetFont($this->font, '', 11);
            $str = $pdf->MultiCell($noteCellWidthTr, $cellHeightTr, $note, 1, $noteAlign, false);
            // $str = $pdf->MultiCell($noteCellWidthTr, $cellHeightTr, "This is a multi-line text string\nNew line\nNew line", 1, $noteAlign, false);
            $h += $rowHeight;
        }

        $heightAfter = $pdf->GetY();

        $pdf->SetFont($this->fontBold, '', 11);
        $pdf->SetXY(20, $heightAfter + 10);
        $pdf->Write(0, 'Для замечаний:');

        $pdf->SetXY(10, $heightAfter + 20);
        // print_r($str);
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');
        $str = $pdf->cell('', $cellHeightInfo, '', 'B', 1, 'C');

        $pdf->Write(0, iconv('utf-8', 'windows-1251', $str));

        $pdf->Output('I', $data['document']['file'] . '_list.pdf');
    }

    public function addFileAddition(FileAdditionDto $data)
    {
        try {
            $data->files = $this->additionFilesCycle($data->files);
            if ($data->documentId != null) {
                $result = $this->file->addDocFileAddition($data);
            } else if ($data->assignmentId != null) {
                $result = $this->file->addAssignFileAddition($data);
            } else if ($data->feedbackId != null) {
                $result = $this->file->addFeedbackFileAddition($data);
            } else if ($data->agreementAndUserId != null) {
                $result = $this->file->addAgreementAndUserFileAddition($data);
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateFileAddition(FileAdditionDto $data)
    {
        try {
            if (isset($data->delete)) {
                if (isset($data->documentId)) {
                    return $this->file->removeByDocumentId($data->documentId);
                } else if (isset($data->assignmentId)) {
                    return $this->file->removeByAssignmentId($data->assignmentId);
                } else if (isset($data->id)) {
                    return $this->file->remove($data->id);
                }
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateFileComment(FileDto $data)
    {
        try {
            return $this->file->updateFileComment($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateDocumentFile(FileUploadDto $data)
    {
        try {
            $fileData = $this->fileUpload($data->file);
            if (isset($fileData)) {
                $data->fileLink = $fileData['file'];
                $data->fileFormat = $fileData['format'];
                return $this->file->updateFile($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function formateDate($data)
    {
        try {
            if ($data != null) {
                $datetime = date("Y-m-d H:i:s", strtotime("+3 hours", strtotime($data)));
                $date = explode(' ', $datetime)[0];
                $time = explode(' ', $datetime)[1];
                $dateDot = explode('-', $date)[2] . '.' . explode('-', $date)[1] . '.' . explode('-', $date)[0];
                return $dateDot . ' ' . $time;
            } else {
                return '...';
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function watermark()
    {
        try {
            TextWatermarker::input(storage_path('/app/public/pdfs/test_img2.pdf'))
                ->output('laravel-text.pdf')
                ->position(Position::BOTTOM_CENTER, -50, -10)
                ->asBackground()
                ->pageRange(3, 4)
                ->text('Hello World')
                ->angle(25)
                ->font(public_path('fonts/Lato-Regular.ttf'))
                ->size('25')
                ->color('#CC00007F')
                ->resolution(300) // 300 dpi
                ->save();
        } catch (Throwable $e) {
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function additionFilesCycle(array $arr)
    {
        foreach ($arr as &$item) {
            $fileData = $this->fileUploadAddition($item->file);
            $item->fileLink = $fileData['file'];
            $item->format = $fileData['format'];
        }
        return $arr;
    }
}
