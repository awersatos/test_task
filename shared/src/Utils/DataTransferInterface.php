<?php


namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;

interface DataTransferInterface
{
    public function __construct(Request $request);
}