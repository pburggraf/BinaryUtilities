<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities;

use PBurggraf\BinaryUtilities\DataType\AbstractDataType;
use PBurggraf\BinaryUtilities\EndianType\AbstractEndianType;
use PBurggraf\BinaryUtilities\EndianType\BigEndian;
use PBurggraf\BinaryUtilities\Exception\DataTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndianTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\InvalidDataTypeException;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilities
{
    const MODE_READ = 'read';
    const MODE_WRITE = 'write';

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
    protected $buffer = [];

    /**
     * @var int
     */
    protected $currentBit;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var string
     */
    protected $endian;

    /**
     * @var AbstractDataType[]
     */
    protected $dataTypeClasses = [];

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
     * @param string $mode
     *
     * @return BinaryUtilities
     */
    public function setEndian(string $mode): BinaryUtilities
    {
        $this->endian = $mode;

        return $this;
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
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param int $base
     *
     * @return BinaryUtilities
     */
    public function setBase(int $base = self::BASE_DECIMAL): BinaryUtilities
    {
        $this->base = $base;

        return $this;
    }

    /**
     * @param bool $clearBuffer
     *
     * @return array
     */
    public function returnBuffer(bool $clearBuffer = true): array
    {
        $buffer = $this->buffer;

        if ($clearBuffer) {
            $this->buffer = [];
        }

        return array_map([$this, 'convertToBase'], $buffer);
    }

    /**
     * @param string $dataClass
     *
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     *
     * @return BinaryUtilities
     */
    public function read(string $dataClass): BinaryUtilities
    {
        $dataType = $this->getDataType($dataClass);

        $dataType->setContent($this->content);
        $dataType->setOffset($this->offset);
        $dataType->setEndianMode($this->getEndianType($this->endian));

        $this->buffer = array_merge($this->buffer, $dataType->read());

        $this->offset = $dataType->newOffset();

        return $this;
    }

    /**
     * @param string $dataClass
     * @param int    $length
     *
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     *
     * @return BinaryUtilities
     */
    public function readArray(string $dataClass, int $length): BinaryUtilities
    {
        $dataType = $this->getDataType($dataClass);

        $dataType->setContent($this->content);
        $dataType->setOffset($this->offset);
        $dataType->setEndianMode($this->getEndianType($this->endian));

        $this->buffer = array_merge($this->buffer, $dataType->readArray($length));

        $this->offset = $dataType->newOffset();

        return $this;
    }

    /**
     * @param string $dataClass
     * @param int    $data
     *
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     *
     * @return BinaryUtilities
     */
    public function write(string $dataClass, int $data): BinaryUtilities
    {
        $dataType = $this->getDataType($dataClass);

        $dataType->setContent($this->content);
        $dataType->setOffset($this->offset);
        $dataType->setEndianMode($this->getEndianType($this->endian));

        $dataType->write($data);

        $this->content = $dataType->newContent();
        $this->offset = $dataType->newOffset();

        return $this;
    }

    /**
     * @param string $dataClass
     * @param array  $data
     *
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     *
     * @return BinaryUtilities
     */
    public function writeArray(string $dataClass, array $data): BinaryUtilities
    {
        $dataType = $this->getDataType($dataClass);

        $dataType->setContent($this->content);
        $dataType->setOffset($this->offset);
        $dataType->setEndianMode($this->getEndianType($this->endian));

        $dataType->writeArray($data);

        $this->content = $dataType->newContent();
        $this->offset = $dataType->newOffset();

        return $this;
    }

    public function save(): void
    {
        $handle = fopen($this->file, 'wb');
        fwrite($handle, $this->content);
        fclose($handle);
    }

    /**
     * @param string $dataClass
     *
     * @throws DataTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     *
     * @return AbstractDataType
     */
    private function getDataType(string $dataClass): AbstractDataType
    {
        if (!class_exists($dataClass)) {
            throw new DataTypeDoesNotExistsException();
        }

        if (!array_key_exists($dataClass, $this->dataTypeClasses)) {
            /** @var AbstractDataType $type */
            $type = new $dataClass();

            if (!$type instanceof AbstractDataType) {
                throw new InvalidDataTypeException();
            }
        } else {
            /** @var AbstractDataType $dataType */
            $type = $this->dataTypeClasses[$dataClass];
        }

        return $type;
    }

    /**
     * @param null|string $entianType
     *
     * @throws EndianTypeDoesNotExistsException
     *
     * @return AbstractEndianType
     */
    private function getEndianType(?string $entianType): AbstractEndianType
    {
        if ($entianType === null) {
            $entianType = BigEndian::class;
        }

        if (!class_exists($entianType)) {
            throw new EndianTypeDoesNotExistsException();
        }

        return new $entianType();
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
        $this->offset = 0;
        $this->endOfFile = filesize($this->file);
        $handle = fopen($this->file, 'rb');
        $this->content = fread($handle, $this->endOfFile);
        fclose($handle);
    }
}
