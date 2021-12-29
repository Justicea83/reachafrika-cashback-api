<?php

namespace App\Http\Controllers\V1\Core;

use App\Http\Controllers\Controller;
use App\Services\Core\ICoreService;
use Illuminate\Http\JsonResponse;

class CoreInfoController extends Controller
{
    private ICoreService $coreService;
    function __construct(ICoreService $coreService){
        $this->coreService = $coreService;
    }

    public function getUserInfoByPhone(string $phone): JsonResponse
    {
       return $this->successResponse($this->coreService->getUserInfoByPhone($phone));
    }
}
