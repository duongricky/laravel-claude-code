<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CategoryNotFoundException extends Exception
{
    public function __construct(int|string $id)
    {
        parent::__construct("Category [{$id}] not found.", 404);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
