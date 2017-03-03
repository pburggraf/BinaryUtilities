<?php

namespace PBurggraf\BinaryUtilities\DataType;

use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class Short extends AbstractDataType
{
    /**
     * @throws EndOfFileReachedException
     *
     * @return array
     */
    public function read(): array
    {
        $bytes = [];

        $this->assertNotEndOfFile();
        $bytes[] = $this->getByte($this->offset++);
        $this->assertNotEndOfFile();
        $bytes[] = $this->getByte($this->offset++);

        $data = $this->endianMode->applyEndianess($bytes);

        return [
            $this->mergeBytes($data),
        ];
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
            $bytes = [];

            $this->assertNotEndOfFile();
            $bytes[] = $this->getByte($this->offset++);
            $this->assertNotEndOfFile();
            $bytes[] = $this->getByte($this->offset++);

            $data = $this->endianMode->applyEndianess($bytes);

            $buffer[] = $this->mergeBytes($data);
        }

        return $buffer;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    private function mergeBytes(array $data): int
    {
        return $data[0] << 8 | $data[1];
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

        for ($i = $this->offset; $i <= $this->offset - 1 + $dataLength; ++$i) {
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
