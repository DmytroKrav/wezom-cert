<?php

namespace App\ModelSorters;

use App\Models\StolenCar;
use App\Sort\ModelSorter as BaseModel;

/**
 * Class StolenCarSorter
 * @package WezomLaravel\Catalog\ModelSorters
 * @mixin StolenCar
 */
class StolenCarSorter extends BaseModel
{
    public $availableSorters = ['id', 'year', 'color_hex', 'created_at', 'gov_number']; // TODO by related models fields

    /**
     * @param string $sortOrder
     */
    protected function id(string $sortOrder)
    {
        $this->orderBy('id', $sortOrder);
    }

    /**
     * @param string $sortOrder
     */
    protected function year(string $sortOrder)
    {
        $this->orderBy('year', $sortOrder);
    }

    /**
     * @param string $sortOrder
     */
    protected function govNumber(string $sortOrder)
    {
        $this->orderBy('gov_number', $sortOrder);
    }

    /**
     * @param string $sortOrder
     */
    protected function createdAt(string $sortOrder)
    {
        $this->orderBy('created_at', $sortOrder);
    }

    /**
     * @param string $sortOrder
     */
    protected function colorHex(string $sortOrder)
    {
        $this->orderBy('color_hex', $sortOrder);
    }
}
