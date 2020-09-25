<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StolenCarExport implements FromCollection
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return $this->data;
    }
}
