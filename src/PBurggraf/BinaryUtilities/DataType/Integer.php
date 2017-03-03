<?php

namespace PBurggraf\BinaryUtilities\DataType;

use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class Integer extends AbstractDataType
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
        return $data[0] << 24 | $data[1] << 16 | $data[2] << 8 | $data[3];
    }

    /**
     * @param int $data
     *
     * @throws EndOfFileReachedException
     */
    public function write(int $data): void
    {
        $bytes = $this->splitBytes($data);

        $bytes = $this->endianMode->applyEndianess($bytes);

        $this->assertNotEndOfFile();
        $this->setByte($this->offset++, $bytes[0]);

        $this->assertNotEndOfFile();
        $this->setByte($this->offset++, $bytes[1]);

        $this->assertNotEndOfFile();
        $this->setByte($this->offset++, $bytes[2]);

        $this->assertNotEndOfFile();
        $this->setByte($this->offset++, $bytes[3]);
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
            $bytes = $this->splitBytes($data[$i - $startBytePosition]);

            $this->assertNotEndOfFile();
            $this->setByte($this->offset++, $bytes[0]);

            $this->assertNotEndOfFile();
            $this->setByte($this->offset++, $bytes[1]);

            $this->assertNotEndOfFile();
            $this->setByte($this->offset++, $bytes[2]);

            $this->assertNotEndOfFile();
            $this->setByte($this->offset++, $bytes[3]);

        }
    }

    /**
     * @param int $data
     *
     * @return array
     */
    public function splitBytes(int $data): array
    {
        $bytes = [];

        $bytes[] = ($data & 0xff000000) >> 24;
        $bytes[] = ($data & 0x00ff0000) >> 16;
        $bytes[] = ($data & 0x0000ff00) >> 8;
        $bytes[] = ($data & 0x000000ff);

        return $bytes;
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
