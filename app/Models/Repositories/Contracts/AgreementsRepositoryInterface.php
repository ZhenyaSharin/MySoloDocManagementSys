<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\AgreementDto\AgreementDto;
use App\Dto\AgreementDto\AgreementUserDto;

interface AgreementsRepositoryInterface
{
    public function add(int $userId, int $documentId, string $deadline);

    public function listByUserId(int $id);

    public function listNonViewedByUserId(int $id);

    public function agreementsByDocId(int $id);

    public function agreementsByDocIdWithInfo(int $id);

    public function refuseAgreement(AgreementUserDto $data);

    public function approveAgreement(AgreementUserDto $data);

    public function agrResponsesByUserIdWithLimit(int $id, int $count);

    public function agrResponsesByUserId(int $id);

    public function agrListByUserId(int $id);

    public function agrListByUserIdWithCount(int $id, int $count);

    public function addAgreement(AgreementUserDto $data);

    public function agreementsAndUsersHistory(AgreementUserDto $data);

    public function agreementsAndUsersHistoryWithCount(AgreementUserDto $data);

    public function makeViewedAgreementUser(AgreementUserDto $data);

    public function removeAgreement(int $id);

    public function addAgreementAndUser(AgreementDto $data);

    public function addAgreementAndUserWithOrder(AgreementDto $data);

    public function addAgreementAndUserWithStatus(AgreementDto $data);

    public function addAgreementAndUserCompleted(AgreementDto $data);

    public function addAgreementAndUserWithStatusAndOrderCompleted(AgreementDto $data);

    public function removeAgreementUser(int $id);

    public function removeAgreementUserWithComplete(int $id, int $documentId);
}
