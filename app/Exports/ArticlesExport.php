<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ArticlesExport implements FromCollection
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return collect($this->data);
    }
}
