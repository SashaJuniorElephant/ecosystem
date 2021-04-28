<?php

namespace App\Entity;

class SidesOfWorld
{
    public const NORTH = 1; // 0001
    public const EAST  = 2; // 0010
    public const SOUTH = 4; // 0100
    public const WEST  = 8; // 1000

    public static function getArray($option = 0)
    {
        $enableAll = 15; // 1111
        $mask = $enableAll ^ $option;
        $returnArray = [];

        foreach (range(0, 3) as $i) {
            if (($mask >> $i) & 1) {
                $returnArray[] = 1 << $i;
            }
        }

        return $returnArray;
    }
}
