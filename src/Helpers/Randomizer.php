<?php

namespace Devian2011\Seeder\Helpers;

class Randomizer
{

    /**
     * @param array $data
     * @return mixed
     */
    public static function randomArrayItem(array $data)
    {
        return $data[mt_rand(0, count($data) - 1)];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function randomArrayAssocItem(array $data)
    {
        $keys = array_keys($data);
        $key = self::randomArrayItem($keys);
        return $data[$key];
    }

}
