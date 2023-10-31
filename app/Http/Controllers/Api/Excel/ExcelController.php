<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Excel;

use App\Exports\ArticlesExport;
use App\Http\Controllers\Controller;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ExcelController extends Controller
{
    public function export(Request $request, ExcelService $excelService): JsonResponse|BinaryFileResponse
    {
        try {
            if ($request->hasFile('file_excel')) {
                $file = $request->file('file_excel');

                $data = $excelService->getDataFromFile($file);
            } else {
                $data = $excelService->getDataFromApi();
            }
            return Excel::download(new ArticlesExport($data), 'articles.xlsx', \Maatwebsite\Excel\Excel::XLSX, ['Content-Type' => 'text/xlsx']);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
