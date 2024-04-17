<?php

namespace App\Utilities;

use App\Contract\ResponseInterface;
use Illuminate\Http\JsonResponse;

class ResponseUtility extends JsonResponse implements ResponseInterface
{
    public bool|int $isSucceed = true;
    public string|null $message = '';
    public array|null|object $datas = null;

    public function toJson(): JsonResponse
    {
        $responseData = [
            'isSucceed' => $this->isSucceed,
            'message' => $this->message,
            'data' => $this->datas
        ];

        return response()->json($responseData);
    }
}
