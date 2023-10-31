<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    public function getDataFromApi(): array
    {
        $firstPartBody = Http::get(config('excel.import.articles.data_uri'), [
            'per_page' => config('excel.import.articles.per_page'),
            'page' => 1,
        ])->body();
        $secondPartBody = Http::get(config('excel.import.articles.data_uri'), [
            'per_page' => config('excel.import.articles.per_page'),
            'page' => 2,
        ])->body();

        $data = array_merge(json_decode($firstPartBody, true), json_decode($secondPartBody, true));

        usort($data, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $sorted = [];
        foreach ($data as $item) {
            $sorted[0][] = $item['title']['rendered'];
            $sorted[1][] = $item['content']['rendered'];
        }

        return $sorted;
    }

    public function getDataFromFile(UploadedFile $file): array
    {
        $data = Excel::toArray(collect(), $file)[0];
        $data = array_filter($data, function ($value) {
            return !in_array(null, $value);
        });

        usort($data, function ($a, $b) {
            return strtotime($b[2]) - strtotime($a[2]);
        });

        $sorted = [];
        foreach ($data as $item) {
            $sorted[0][] = $item[0];
            $sorted[1][] = $item[1];
        }

        return $sorted;
    }
}
