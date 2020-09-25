<?php

namespace App\Traits;

use App\Sort\ModelSorter;

trait Sortable
{
    /**
     * Returns ModelSorter class to be instantiated.
     *
     * @param null|string $filter
     * @return string
     */
    public function provideSorter($sorter = null)
    {
        if ($sorter === null) {
            // Search in model location directory
            $sorter = get_class($this).'Sorter';

            try {
                $classExists = class_exists($sorter);
            } catch (\ErrorException $e) {
                $classExists = false;
            }

            // Search in ModelFilters directory
            if (!$classExists) {
                $sorter = str_replace('\\Models\\', '\\ModelSorters\\', $sorter);
            }
        }

        return $sorter;
    }

    /**
     * Creates local scope to run the sorter.
     *
     * @param $query
     * @param array $input
     * @param null|string|ModelSorter $sorter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query, array $input = [])
    {
        $sorter = $this->getModelSorterClass();

        // Create the model filter instance
        $modelSorter = new $sorter($query, $input);

        // Return the filter query
        return $modelSorter->handle();
    }

    /**
     * Returns the ModelFilter for the current model.
     *
     * @return string
     */
    public function getModelSorterClass()
    {
        return method_exists($this, 'modelSorter') ? $this->modelSorter() : $this->provideSorter();
    }
}
