<?php

namespace App\Http\Controllers;

use App\Models\Repositories\Contracts\AgreementsRepositoryInterface;
use App\Models\Repositories\Contracts\DocumentsRepositoryInterface;

class DocumentPageController extends Controller
{
    private $docs;
    private $agrs;
    public $arr;

    public function __construct(DocumentsRepositoryInterface $docs, AgreementsRepositoryInterface $agrs)
    {
        $this->docs = $docs;
        $this->agrs = $agrs;
    }

    public function index($id)
    {
        return view('docpage', compact('id'));
    }

    public function list()
    {
        return view('docslist');
    }
}
