<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Services\HallService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class HallController extends Controller
{
    public function __construct(private readonly HallService $hallService){

    }

    public function index(): ?JsonResponse
    {
        try {
            $hallsResponse = $this->hallService->getHalls();

            if (isset($hallsResponse['error'])) {
                return ResponseAction::error($hallsResponse['error']);
            }

            return ResponseAction::successData($hallsResponse['message'], $hallsResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): ?JsonResponse
    {
        try {
            $hallResponse = $this->hallService->getHall($id);

            if (isset($hallResponse['error'])) {
                return ResponseAction::error($hallResponse['error']);
            }

            return ResponseAction::successData($hallResponse['message'], $hallResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
