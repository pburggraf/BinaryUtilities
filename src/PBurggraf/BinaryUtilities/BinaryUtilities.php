<?php

declare(strict_types = 1);

namespace PBurggraf\BinaryUtilities;

use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\UnsopportedEndianTypeException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilities
{
    const MODE_READ = 'read';
    const MODE_WRITE = 'write';

    const ENDIAN_LITTLE = 'little';
    const ENDIAN_BIG = 'big';

    const BASE_BINARY = 2;
    const BASE_OCTAL = 7;
    const BASE_DECIMAL = 10;
    const BASE_HEXADECIMAL = 16;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $currentMode;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $endOfFile;

    /**
     * @var int
     */
    protected $base = self::BASE_DECIMAL;

    /**
     * @var array
     */
    protected $buffer;

    /**
     * @var int
     */
    protected $currentBit;

    /**
     * @var int
     */
    protected $currentByte;

    /**
     * @var int
     */
    protected $endian = self::ENDIAN_BIG;

    /**
     * @param string $file
     *
     * @throws FileDoesNotExistsException
     *
     * @return BinaryUtilities
     */
    public function setFile(string $file): BinaryUtilities
    {
        if (!file_exists($file)) {
            throw new FileDoesNotExistsException();
        }

        $this->file = $file;

        $this->setContent();

        return $this;
    }

    /**
     * @param int $mode
     *
     * @return BinaryUtilities
     */
    public function endian(string $mode): BinaryUtilities
    {
        $this->endian = $mode;

        return $this;
    }

    /**
     * @return int
     */
    public function endOfFile(): int
    {
        return $this->endOfFile;
    }

    /**
     * @param int $offset
     *
     * @throws EndOfFileReachedException
     *
     * @return BinaryUtilities
     */
    public function offset(int $offset): BinaryUtilities
    {
        $this->currentByte = $offset;

        $this->assertNotEndOfFile();

        return $this;
    }

    /**
     * @param int $base
     */
    public function setBase(int $base = self::BASE_DECIMAL): void
    {
        $this->base = $base;
    }

    /**
     * @return array
     */
    public function returnBuffer(): array
    {
        return $this->buffer;
    }

    /**
     * @throws EndOfFileReachedException
     * @throws UnsopportedEndianTypeException
     *
     * @return BinaryUtilities
     */
    public function readShort(): BinaryUtilities
    {
        $this->assertNotEndOfFile(2);

        $data = $this->convertToCorrectEndian([
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
        ]);

        $this->buffer[] = $this->convertToBase((int) base_convert(implode('', $data), 16, 10));

        return $this;
    }

    /**
     * @throws EndOfFileReachedException
     * @throws UnsopportedEndianTypeException
     *
     * @return BinaryUtilities
     */
    public function readInt(): BinaryUtilities
    {
        $this->assertNotEndOfFile(4);

        $data = $this->convertToCorrectEndian([
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
            str_pad(base_convert($this->getSingleByte($this->currentByte++), 10, 16), 2, '0'),
        ]);

        $this->buffer[] = $this->convertToBase((int) base_convert(implode('', $data), 16, 10));

        return $this;
    }

    /**
     * @throws EndOfFileReachedException
     *
     * @return BinaryUtilities
     */
    public function readByte(): BinaryUtilities
    {
        $this->assertNotEndOfFile();

        $value = $this->getSingleByte($this->currentByte++);

        $this->buffer[] = $this->convertToBase($value);

        return $this;
    }

    /**
     * @param int $data
     *
     * @throws EndOfFileReachedException
     *
     * @return BinaryUtilities
     */
    public function writeByte(int $data): BinaryUtilities
    {
        $this->assertNotEndOfFile();

        $this->setSingleByte($this->currentByte++, $data);

        return $this;
    }

    /**
     * @param int $length
     *
     * @throws EndOfFileReachedException
     *
     * @return BinaryUtilities
     */
    public function readByteArray(int $length): BinaryUtilities
    {
        $startBytePosition = $this->currentByte;

        for ($i = $startBytePosition; $i <= $startBytePosition - 1 + $length; ++$i) {
            $this->readByte();
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @throws EndOfFileReachedException
     *
     * @return BinaryUtilities
     */
    public function writeByteArray(array $data): BinaryUtilities
    {
        $dataLength = count($data);
        $startBytePosition = $this->currentByte;

        for ($i = $startBytePosition; $i <= $startBytePosition - 1 + $dataLength; ++$i) {
            $this->writeByte($data[$i - $startBytePosition]);
        }

        return $this;
    }

    public function save(): void
    {
        $handle = fopen($this->file, 'wb');
        fwrite($handle, $this->content);
        fclose($handle);
    }

    /**
     * @param int $position
     *
     * @return int
     */
    private function getSingleByte(int $position): int
    {
        return (int) hexdec(bin2hex($this->content[$position]));
    }

    /**
     * @param int $position
     * @param int $data
     */
    private function setSingleByte(int $position, int $data): void
    {
        $this->content[$position] = hex2bin(str_pad(dechex($data), 2, '0', STR_PAD_LEFT));
    }

    /**
     * @param int $value
     *
     * @return string
     */
    private function convertToBase(int $value): string
    {
        return base_convert($value, 10, $this->base);
    }

    private function setContent(): void
    {
        $this->currentBit = 0;
        $this->currentByte = 0;
        $this->endOfFile = filesize($this->file);

        $handle = fopen($this->file, 'rb');
        $this->content = fread($handle, $this->endOfFile);
        fclose($handle);
    }

    /**
     * @param int $length
     *
     * @throws EndOfFileReachedException
     */
    private function assertNotEndOfFile($length = 1): void
    {
        if ($this->currentByte + $length - 1 > $this->endOfFile - 1) {
            throw new EndOfFileReachedException();
        }
    }

    /**
     * @param array $data
     *
     * @throws UnsopportedEndianTypeException
     *
     * @return array
     */
    private function convertToCorrectEndian(array $data): array
    {
        switch ($this->endian) {
            case self::ENDIAN_LITTLE:
                return array_reverse($data);
            case self::ENDIAN_BIG:
                return $data;
            default:
                throw new UnsopportedEndianTypeException((string) $this->endian);
        }
    }
}
