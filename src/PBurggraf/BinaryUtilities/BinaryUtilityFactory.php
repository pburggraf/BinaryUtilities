<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilityFactory
{
    /**
     * @return BinaryUtilities
     */
    public static function create(): BinaryUtilities
    {
        return new BinaryUtilities();
    }
}
