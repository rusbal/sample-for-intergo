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
}