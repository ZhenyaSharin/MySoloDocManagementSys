<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\DocumentDto\DocumentDto;

interface DocumentsRepositoryInterface
{
    function list(string $ascDesc);

    public function listDeliveryTypes();

    public function listDocumentTypes();

    public function add(DocumentDto $data);

    public function addWithoutAgreers(DocumentDto $data);

    public function addWithinAgreersOrder(DocumentDto $data);

    public function getById(int $id);

    public function getByIdWithInfo(int $id);

    public function getByIdVersions(int $id);

    public function listByUserId(int $id, string $ascDesc = 'DESC');

    public function listByUserIdWithCount(int $id, int $count, string $ascDesc = 'DESC');

    public function documentIntoArchive(int $id, int $statusId);

    public function remove(int $id);

    public function update(DocumentDto $data);

    public function listByTypeId(int $typeId, string $ascDesc = 'DESC');

    public function listByTypeIdWithCount(int $typeId, int $count, string $ascDesc = 'DESC');

    public function listDirusers();

    public function listDocumentStatuses();

    public function listStatuses();

    public function updateInfo(DocumentDto $data);

    public function updateInfoWithDiruser(DocumentDto $data);

    public function updateInfoWithDiruserNew(DocumentDto $data);

    public function listByStatusId(int $statusId);

    public function listByUserIdAndStatusId(int $userId, int $statusId);

    public function addWithoutAgreersWithoutFile(DocumentDto $data);

    public function listOrderDate(string $ascDesc = 'DESC');

    public function listByUserIdOrderDate(int $id, string $ascDesc = 'DESC');

    public function listByTypeIdOrderDate(int $typeId, string $ascDesc = 'DESC');

    public function listByTypeIdWithCountOrderDate(int $typeId, int $count, string $ascDesc = 'DESC');

    public function listByUserIdWithCountOrderDate(int $id, int $count, string $ascDesc = 'DESC');

    public function addWithinAgreersOrderWithoutFile(DocumentDto $data);

    public function addWithoutFile(DocumentDto $data);



    public function addAddFiles(DocumentDto $data);

    public function addWithoutAgreersAddFiles(DocumentDto $data);

    public function addWithinAgreersOrderAddFiles(DocumentDto $data);

    public function addWithoutAgreersWithoutFileAddFiles(DocumentDto $data);

    public function addWithinAgreersOrderWithoutFileAddFiles(DocumentDto $data);

    public function addWithoutFileAddFiles(DocumentDto $data);
}
