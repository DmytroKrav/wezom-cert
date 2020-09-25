<?php

namespace App\Sort;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;

abstract class ModelSorter
{
    const SORT_ORDER_PARAMETER = 'sort';
    const SORT_FIELD_PARAMETER = 'sort_field';

    /**
     * Array of input to filter.
     *
     * @var array
     */
    protected $input;

    /**
     * @var QueryBuilder
     */
    protected $query;

    /**
     * @var
     */
    public $availableSorters = [];

    /**
     * ModelSorter constructor.
     *
     * @param $query
     * @param array $input
     */
    public function __construct($query, array $input = [])
    {
        $this->input = $input;
        $this->query = $query;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $resp = call_user_func_array([$this->query, $method], $args);

        // Only return $this if query builder is returned
        // We don't want to make actions to the builder unreachable
        return $resp instanceof QueryBuilder ? $this : $resp;
    }

    /**
     * Handle all filters.
     *
     * @return QueryBuilder
     */
    public function handle()
    {
        // Run input sorters
        $this->sortInput($this->input);

        return $this->query;
    }

    /**
     * @param array $input
     */
    public function sortInput(array $input)
    {
        $sortableFieldName = \Arr::get($input, 'sort_field');
        $sortableOrder = \Arr::get($input, 'sort');

        $methodName = \Str::camel($sortableFieldName);
        if ($sortableFieldName
            && in_array($sortableFieldName, $this->availableSorters)
            && method_exists($this, $methodName)
        ) {
            \Str::camel($this->{$methodName}($sortableOrder));
        }
    }
}
