<?php

namespace PBurggraf\BinaryUtilities\DataType;

use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class Byte extends AbstractDataType
{
    /**
     * @throws EndOfFileReachedException
     *
     * @return array
     */
    public function read(): array
    {
        $this->assertNotEndOfFile();

        return [$this->getByte($this->offset++)];
    }

    /**
     * @param int $length
     *
     * @throws EndOfFileReachedException
     *
     * @return array
     */
    public function readArray(int $length): array
    {
        $buffer = [];

        for ($iterator = 0; $iterator < $length; ++$iterator) {
            $this->assertNotEndOfFile();

            $buffer[] = $this->getByte($this->offset++);
        }

        return $buffer;
    }

    /**
     * @param int $data
     *
     * @throws EndOfFileReachedException
     */
    public function write(int $data): void
    {
        $this->assertNotEndOfFile();
        $this->setByte($this->offset++, $data);
    }

    /**
     * @param array $data
     *
     * @throws EndOfFileReachedException
     */
    public function writeArray(array $data): void
    {
        $dataLength = count($data);
        $startBytePosition = $this->offset;

        for ($i = $startBytePosition; $i <= $startBytePosition - 1 + $dataLength; ++$i) {
            $this->assertNotEndOfFile();
            $this->setByte($this->offset++, $data[$i - $startBytePosition]);
        }
    }

    /**
     * @return string
     */
    public function newContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function newOffset(): int
    {
        return $this->offset;
    }
}
