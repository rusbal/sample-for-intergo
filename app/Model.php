<?php

namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Model extends EloquentModel
{
    public static function builderOrFail($id)
    {
        $builder = self::where('id', $id);

        if ($builder->exists()) {
            return $builder;
        }

//        No query results for model [App\AmazonMerchantListing] 1
        throw new ModelNotFoundException;
    }

    public static function fillableFields()
    {
        return (new static)->getFillable();
    }

    public static function selectSortAndFilter($columns, $where, $sort, $filter)
    {
        $builder = static::select($columns);
        static::applyWhereCondition($builder, $where);
        static::applySearchFilter($builder, $filter);
        static::applySortFields($builder, $sort);
        return $builder;
    }

    /**
     * Private
     */

    private static function applySortFields($builder, $sort)
    {
        if ($sort) {
            $sortFields = array_map(function($field){
                return explode('|', $field);
            }, explode(',', $sort));
        } else {
            $sortFields = static::DEFAULT_ORDER;
        }

        foreach ($sortFields as $pair) {
            $builder->orderBy($pair[0], $pair[1]);
        }
    }

    private static function applyWhereCondition($builder, $where)
    {
        /**
         * $where contains array of conditions
         *    ['id', 1]
         *    ['votes', '>', 1]
         */
        foreach ($where as $field => $condition) {
            $count = count($condition);
            if ($count == 2) {
                $builder->where($condition[0], $condition[1]);
            } elseif ($count == 3) {
                $builder->where($condition[0], $condition[1], $condition[2]);
            }
        }
    }

    private static function applySearchFilter($builder, $filter)
    {
        $filter = trim($filter);

        if (! $filter) {
            return;
        }

        $builder->where(
            function ($query) use ($filter) {
                foreach (static::SEARCH_COLUMNS as $column => $condition) {
                    if ($condition == '= int')  {
                        if (! is_numeric($filter)) {
                            continue;
                        }
                        $condition = '=';
                    }
                    $query->orWhere(
                        $column,
                        $condition,
                        ($condition === '=' ? $filter : "%{$filter}%")
                    );
                }
            }
        );
    }
}