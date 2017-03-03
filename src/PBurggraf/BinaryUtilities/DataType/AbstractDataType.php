<?php

namespace PBurggraf\BinaryUtilities\DataType;

use PBurggraf\BinaryUtilities\EndianType\AbstractEndianType;
use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
abstract class AbstractDataType
{
    /**
     * @var array
     */
    protected $content;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var AbstractEndianType
     */
    protected $endianMode;

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @param AbstractEndianType $endianType
     */
    public function setEndianMode(AbstractEndianType $endianType): void
    {
        $this->endianMode = $endianType;
    }

    /**
     * @param int $position
     *
     * @return int
     */
    protected function getByte(int $position): int
    {
        return (int) hexdec(bin2hex($this->content[$position]));
    }

    /**
     * @param int $position
     * @param int $data
     */
    protected function setByte(int $position, int $data): void
    {
        $this->content[$position] = hex2bin(str_pad(dechex($data), 2, '0', STR_PAD_LEFT));
    }

    /**
     * @throws EndOfFileReachedException
     */
    protected function assertNotEndOfFile(): void
    {
        if ($this->offset > strlen($this->content) - 1) {
            throw new EndOfFileReachedException();
        }
    }

    /**
     * @return array
     */
    abstract public function read(): array;

    /**
     * @param int $length
     *
     * @return array
     */
    abstract public function readArray(int $length): array;

    /**
     * @param int $data
     */
    abstract public function write(int $data): void;

    /**
     * @param int[] $data
     */
    abstract public function writeArray(array $data): void;

    /**
     * @return int
     */
    abstract public function newOffset(): int;

    /**
     * @return string
     */
    abstract public function newContent(): string;
}
