<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonRequestQueue extends Model
{
    protected $fillable = [
        'store_name', 'class', 'type', 'request_id', 'response'
    ];

    public static function queueOne($storeName, $class, $type, $requestId, $data)
    {
        $builder = self::where([
            'store_name' => $storeName,
            'class' => $class,
            'type' => $type
        ]);

        if ($builder->exists()) {
            return $builder->first();
        }

        return self::log($storeName, $class, $type, $requestId, $data);
    }

    public static function log($storeName, $class, $type, $requestId, $data)
    {
        return self::create([
            'store_name' => $storeName,
            'class' => $class,
            'type' => $type,
            'request_id' => $requestId,
            'response' => serialize($data)
        ]);
    }
}
