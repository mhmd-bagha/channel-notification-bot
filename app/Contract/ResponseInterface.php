<?php

namespace App\Contract;

use Illuminate\Http\JsonResponse;

interface ResponseInterface
{
    public function toJson(): JsonResponse;
}
