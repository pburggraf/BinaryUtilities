<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\EndianType;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class LittleEndian extends AbstractEndianType
{
    public function applyEndianess(array $data): array
    {
        return array_reverse($data);
    }
}