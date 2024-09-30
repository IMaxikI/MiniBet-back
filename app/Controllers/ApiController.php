<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\IRelatedDataRepository;

class ApiController
{
    private IRelatedDataRepository $relatedData;

    public function __construct(IRelatedDataRepository $relatedData)
    {
        $this->relatedData = $relatedData;
    }

    public function sendDataBet(): void
    {
        $data = $this->relatedData->all();

        $this->headers();

        echo json_encode($data);
    }

    private function headers(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type");
    }
}