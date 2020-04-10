<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\EndianType;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
abstract class AbstractEndianType
{
    /**
     * @param array $data
     *
     * @return array
     */
    abstract public function applyEndianess(array $data): array;
}
