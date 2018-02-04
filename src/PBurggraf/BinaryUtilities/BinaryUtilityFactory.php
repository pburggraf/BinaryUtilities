<?php

namespace PBurggraf\BinaryUtilities;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilityFactory
{
    public static function create()
    {
        return new BinaryUtilities();
    }
}
