<?php

namespace App\Interfaces;

use App\Models\Structure;

interface IImport
{
    public function import(Structure $structure);
}