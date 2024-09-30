<?php

namespace App\Interfaces;

use App\Models\Structure;

interface IParser
{
    public function parse(): Structure;
}