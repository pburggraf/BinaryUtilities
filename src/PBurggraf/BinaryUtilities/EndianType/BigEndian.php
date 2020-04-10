<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\EndianType;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BigEndian extends AbstractEndianType
{
    public function applyEndianess(array $data): array
    {
        return $data;
    }
}
