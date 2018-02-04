<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\EndianType;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
abstract class AbstractEndianType
{
    abstract public function applyEndianess(array $data): array;
}