<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\DataType;

use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class FloatingPoint extends AbstractDataType
{
    /**
     * @return array
     * @throws EndOfFileReachedException
     *
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
     * @return array
     * @throws EndOfFileReachedException
     *
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
     * @return float
     */
    private function mergeBytes(array $data): float
    {
        $string = hex2bin(str_pad(dechex($data[0]), 2, '0', STR_PAD_LEFT) . str_pad(dechex($data[1]), 2, '0', STR_PAD_LEFT) . str_pad(dechex($data[2]), 2, '0', STR_PAD_LEFT) . str_pad(dechex($data[3]), 2, '0', STR_PAD_LEFT));

        $result = unpack('G', $string);

        if (is_array($result) === false) {
            throw new \Exception();
        }

        return $result[1];
    }

    /**
     * @param int|float $data
     *
     * @throws EndOfFileReachedException
     */
    public function write($data): void
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

        for ($i = $startBytePosition; $i <= $startBytePosition - 1 + $dataLength; ++$i) {
            $bytes = $this->splitBytes($data[$i - $startBytePosition]);

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
    }

    /**
     * @param int|float $data
     *
     * @return array
     */
    public function splitBytes($data): array
    {
        $data = hexdec(bin2hex(pack('G', $data)));

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
